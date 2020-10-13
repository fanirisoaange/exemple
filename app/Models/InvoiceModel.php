<?php

namespace App\Models;

use App\Enum\InvoiceStatus;
use App\Enum\OrderStatus;
use App\Enum\PaymentMethodStatus;
use CodeIgniter\Model;
use IonAuth\Libraries\IonAuth;
use Stripe\Exception\CardException;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class InvoiceModel extends Model
{
    public const INVOICE_FROM_COMPANY_ID = 1;

    private $user;

    public function __construct()
    {
        parent::__construct();

        $this->ionAuth = new IonAuth();
        $this->builder = $this->db->table("invoice");
        Stripe::setApiKey(getenv('stripe.api_key'));
    }

    public function create(string $date, int $from, int $to)
    {
        $odate = explode("-", $date);
        if ($odate[1] == '01') {
            $odate[1] = '12';
            $odate[0] = intval($odate[0]) - 1;
        } else {
            $odate[1] = intval($odate[1]) - 1;
            if ($odate[1] < 10) {
                $odate[1] = '0'.$odate[1];
            }
        }
        $odate = implode("-", $odate);

        $orders = $this->db->query(
            "SELECT * FROM order_product WHERE order_id IN (SELECT id FROM `order` WHERE order_at LIKE '"
            .$odate."%' AND company_id = ".$to." AND progress_status = "
            .OrderStatus::ACCEPTED." AND bill_id IS NULL)"
        )->getResultArray();

        if (count($orders)) {

            $subtotal = 0;
            foreach ($orders as $o) {
                $subtotal += $o["price"] * $o['quantity'];
            }

            $total = $subtotal * 1.2;
            $stripeId = '';
            $errorCode = '';
            $paymentMethod = '';
            $paidAt = null;

            $pm = $this->db->table('payment_method')->where(
                [
                    'company_id' => $to,
                    'active'     => 1,
                    'status'     => PaymentMethodStatus::VERIFIED,
                ]
            )->get()->getRowArray();
            if ( ! $pm) {
                $status = InvoiceStatus::INVALID_PAYMENT_METHOD;
                $orderStatus = OrderStatus::CANCELLED;
            } else {
                $paymentMethod = $pm['type'];
                $stripeCustomerId = model(
                    'CompanyModel',
                    true,
                    $this->db
                )->getStripeCustomerId($to);

                try {
                    $stripeId = PaymentIntent::create(
                        [
                            'amount'         => round($total * 100),
                            'currency'       => 'eur',
                            'customer'       => $stripeCustomerId,
                            'payment_method' => $pm['stripe_id'],
                            'off_session'    => true,
                            'confirm'        => true,
                        ]
                    );
                    $stripeId = $stripeId['id'];
                    $status = InvoiceStatus::PAID;
                    $orderStatus = OrderStatus::PAID;
                    $paidAt = date("Y-m-d H:i:s");
                } catch (CardException $e) {
                    $status = InvoiceStatus::CANCELLED;
                    $orderStatus = OrderStatus::CANCELLED;
                    $errorCode = $e->getError()->code;
                    $stripeId = $e->getError()->payment_intent->id;
                    if ($errorCode != "authentication_required") {
                        $this->db->table('payment_method')->set(
                            ["status" => PaymentMethodStatus::REFUSED]
                        )->where("id", $pm['id'])->update();
                    }
                }
            }
            $this->builder->insert(
                [
                    "invoice_date"          => $date."-01",
                    'status'                => $status,
                    'from'                  => $from,
                    'to'                    => $to,
                    'subtotal'              => $subtotal,
                    'payment_method'        => $paymentMethod,
                    'vat'                   => $subtotal * 0.2,
                    'total'                 => $total,
                    'stripe_payment_id'     => $stripeId,
                    'stripe_payment_method' => $pm ? $pm['stripe_id'] : null,
                    'error_code'            => $errorCode,
                    'created_at'            => date("Y-m-d H:i:s"),
                    "updated_at"            => date("Y-m-d H:i:s"),
                    'paid_at'               => date('Y-m-d H:i:s'),
                ]
            );
            $invoice_id = $this->db->insertId();
            $this->db->query(
                "UPDATE `order` SET bill_id = ".$invoice_id
                .", progress_status = ".$orderStatus." WHERE order_at LIKE '"
                .$odate."%' AND company_id = ".$to." AND progress_status = "
                .OrderStatus::ACCEPTED
            );
        }
    }

    public function createOne(
        string $date,
        int $from,
        int $to,
        float $subtotal,
        array $orders
    ) {
        $this->builder->insert(
            [
                "invoice_date" => $date,
                'status'       => InvoiceStatus::ACCEPTED,
                'from'         => $from,
                'to'           => $to,
                'subtotal'     => $subtotal,
                'vat'          => $subtotal * 0.2,
                'total'        => $subtotal * 1.2,
            ]
        );
        $this->db->query(
            "UPDATE `order` SET bill_id = ".$this->db->insertId()
            ." WHERE id IN (".implode(",", $orders).")"
        );
        return $this->db->insertId();
    }

    public function get(int $id)
    {
        $res = $this->builder->where('id', $id)->get()->getRowArray();

        if ( ! $res) {
            return null;
        }

        $res['from'] = $this->db->table('companies')->select(
            "fiscal_name, address_1, zip_code, city_display, phone_number, email"
        )->where('id', $res['from'])->get()->getRowArray();
        $res['to'] = $this->db->table('companies')->select(
            "fiscal_name, address_1, zip_code, city_display, phone_number, email"
        )->where('id', $res['to'])->get()->getRowArray();

        $orders = $this->db->table('order')->where('bill_id', $id)->get()
            ->getResultArray();
        $res["orders"] = [];
        foreach ($orders as $o) {
            array_push(
                $res["orders"],
                $this->db->table("order_product")->where('order_id', $o['id'])
                    ->get()->getResultArray()
            );
        }

        return $res;
    }

    public function getAll(int $id): array
    {
        return $this->builder->orderBy("invoice_date", "DESC")->where('to', $id)
            ->get()->getResultArray();
    }

    public function getAllOrdersUnbilled(int $id): array
    {
        $orders = $this->db->table('order')->orderBy('order_at', "DESC")->where(
            [
                'company_to'      => $id,
                "bill_id"         => null,
                'progress_status' => OrderStatus::ACCEPTED,
            ]
        )->get()->getResultArray();
        foreach ($orders as $k => $v) {
            $p = $this->db->query(
                'SELECT COUNT(*) as c, SUM(price * quantity) as p FROM order_product WHERE order_id = '
                .$v['id']
            )->getRow();
            $orders[$k]['total'] = $p->p;
            $orders[$k]['products'] = $p->c;
        }

        return $orders;
    }

    /**
     * Return all companies which have at least one order for the previous month
     * 
     * @param $date
     * @return array
     */
    public function getDistinctCompanyId($date): array
    {
        $odate = explode("-", $date);
        if ($odate[1] == '01') {
            $odate[1] = '12';
            $odate[0] = intval($odate[0]) - 1;
        } else {
            $odate[1] = intval($odate[1]) - 1;
            if ($odate[1] < 10) {
                $odate[1] = '0'.$odate[1];
            }
        }
        $odate = implode("-", $odate);

        return $this->db->query(
            "SELECT DISTINCT company_id FROM `order` WHERE order_at LIKE '"
            .$odate."%' AND progress_status = " . OrderStatus::ACCEPTED
        )->getResultArray();
    }

    public function processUpdatedFailedInvoices()
    {
        $invoices = $this->db->query(
            'SELECT * FROM invoice as i WHERE ((status = '
            .InvoiceStatus::CANCELLED
            .' AND error_code != "authentication_required") OR status = '
            .InvoiceStatus::INVALID_PAYMENT_METHOD
            .') AND updated_at < (SELECT updated_at FROM payment_method WHERE active = 1 AND status = '
            .PaymentMethodStatus::VERIFIED.' AND company_id = i.to)'
        )->getResultArray();

        foreach ($invoices as $i) {
            $pm = $this->db->table('payment_method')->where(
                [
                    'company_id' => $i['to'],
                    'active'     => 1,
                    'status'     => PaymentMethodStatus::VERIFIED,
                ]
            )->get()->getRowArray();

            $errorCode = '';
            try {
                $paymentIntent = PaymentIntent::retrieve(
                    $i['stripe_payment_id']
                );
                $stripeId = $paymentIntent->confirm(
                    [
                        'payment_method' => $pm['stripe_id'],
                    ]
                );

                $pi = $paymentIntent->toArray();
                $stripeId = $stripeId['id'];
                if ($pi['status'] == 'succeeded') {
                    $status = InvoiceStatus::PAID;
                    $orderStatus = OrderStatus::PAID;
                } else {
                    $status = InvoiceStatus::CANCELLED;
                    $orderStatus = OrderStatus::CANCELLED;
                    $errorCode = $i['error_code'];
                }
            } catch (CardException $e) {
                $status = InvoiceStatus::CANCELLED;
                $orderStatus = OrderStatus::CANCELLED;
                $errorCode = $e->getError()->code;
                $stripeId = $e->getError()->payment_intent->id;
                $this->db->table('payment_method')->set(
                    ["status" => PaymentMethodStatus::REFUSED]
                )->where("id", $pm['id'])->update();
            }
            $this->db->query(
              "UPDATE `order` SET progress_status = "
              .$orderStatus." WHERE bill_id = ".$i['id']
            );
            $this->db->table('invoice')->set(
                [
                    "status"            => $status,
                    'stripe_payment_id' => $stripeId,
                    "updated_at"        => date("Y-m-d H:i:s"),
                    'error_code'        => $errorCode
                ]
            )->where("id", $i['id'])->update();
        }
    }

    public function authenticationSuccess(string $id)
    {
        $this->builder->set(
            ["status" => InvoiceStatus::PAID, "paid_at" => date("Y-m-d H:i:s")]
        )->where("stripe_payment_id", $id)->update();
        $this->db->query(
            "UPDATE `order` SET progress_status = ".OrderStatus::PAID
            ." WHERE bill_id IN (SELECT id FROM invoice WHERE stripe_payment_id = '"
            .$id."')"
        );
    }

    public function getBudget(?int $id, ?string $startDate = null, ?string $endDate = null)
    {
        $currentDate = new \DateTime();
        $endDate = $endDate ? $endDate : $currentDate->format('Y-m-d');
        $startDate = $startDate ? $startDate : $currentDate->format('Y').'-01-01';
        $this->builder->select('SUM(invoice.subtotal) AS budget')->where('invoice.invoice_date BETWEEN "'.$startDate.'" AND "'.$endDate.'"');
        if ($id) {
            $this->builder->where('to', $id);
        }
        return $this->builder->get()->getResultArray()[0]['budget'];
    }
}
