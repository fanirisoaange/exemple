<?php

namespace App\Models;

use App\Enum\OrderStatus;
use App\Enum\Notifications;
use CodeIgniter\Model;

class OrderModel extends Model
{
    public function __construct()
    {
        parent::__construct();

        $this->ionAuth = new \IonAuth\Libraries\IonAuth();
        $this->builder = $this->db->table("order");
    }

    public function getOrdersByCompany(int $id, string $columns = '*'): array
    {
        $data = [];
        $orders = $this->builder
            ->select($columns)
            ->orderBy('order_at DESC')
            ->join('companies as c', 'order.company_to = c.id', 'LEFT')
            ->where('order.company_to', $id)
            ->groupBy('order.id')
            ->get()
            ->getResultArray();
        foreach ($orders as $order) {
            $products = $this->getProducts((int) $order['id'], 'op.name, op.quantity, op.price');
            $order['products'] = $products;
            $data[] = $order;
        }

        return $data;
    }

    public function getProducts(int $id, string $columns = '*'): array
    {
        return $this->builder
            ->select($columns)
            ->join('order_product as op', 'op.order_id = order.id', 'INNER')
            ->where('op.order_id', $id)
            ->get()
            ->getResultArray();
    }

    public function getOrderDetail(int $id, string $columns = '*'): array
    {
        $order = $this->selectOrder($id, $columns);
        if (! empty($order)) {
            $order['order_at'] = \DateTime::createFromFormat(
                'Y-m-d H:i:s',
                $order['order_at']
            )->format('d/m/Y H:i:s');
            $products = $this->getProducts((int) $order['id'], 'op.name, op.quantity, op.product_price_type, op.price');
            $order['products'] = $products;
        }

        return $order;
    }

    public function selectOrder(int $id, string $columns): array
    {
        return $this->builder
            ->select($columns)
            ->join('companies as c', 'order.company_to = c.id', 'LEFT')
            ->getWhere(['order.id' => $id])
            ->getRowArray();
    }

    public function validateOrder(int $id): void
    {
        $date = new \DateTime();
        $date = $date->format('Y-m-d H:i:s');
        $order = $this->selectOrder($id,'order.id, order.progress_status');
        $progressStatus = (int)$order['progress_status'] === OrderStatus::DRAFT
            ? OrderStatus::PENDING : OrderStatus::ACCEPTED;
        $orderData = [
            'progress_status' => $progressStatus,
            'order_at'        => $date,
        ];
        if($progressStatus == OrderStatus::ACCEPTED)
            $orderData['accepted_at'] = $date;
        
        $this->db->table('order')->update($orderData, ['id' => $id]);
        model("NotificationModel")->sendNotif($id, Notifications::ORDER_STATUS_CHANGED_TO_ACCEPTED);
    }

    public function candelOrder($id): void
    {
        $orderData = ['progress_status' => OrderStatus::CANCELLED];
        $this->db->table('order')->update($orderData, ['id' => $id]);
        model("NotificationModel")->sendNotif($id, Notifications::ORDER_STATUS_CHANGED_TO_CANCELED);
    }

    public function draftOrder($id): void
    {
        $orderData = ['progress_status' => OrderStatus::DRAFT];
        $this->db->table('order')->update($orderData, ['id' => $id]);       
    }

    public function sendOrder($id): void
    {
        $orderData = ['progress_status' => OrderStatus::PENDING];
        $this->db->table('order')->update($orderData, ['id' => $id]);
    }

    public function deleteOrder($id): void
    {
        $this->db->table('order_product')->delete(['order_id' => $id]);
        $this->db->table('order')->delete(['id' => $id]);
        model("NotificationModel")->sendNotif($id, Notifications::ORDER_STATUS_CHANGED_TO_CANCELED);
    }

    public function createOrder($data): array
    {
        if (checkFormatDate($data->cmdDate)) {
            $cmdDate = $data->cmdDate;
            $cmdDate = new \DateTime($cmdDate);
            $cmdDate = $cmdDate->format('Y-m-d H:i:s');
        } else {
            $cmdDate = date('Y-m-d H:i:s', time());
        }

        $this->db->transStart();
        
        $order = [
            'progress_status' => OrderStatus::DRAFT,
            'company_id'      => $data->companyFrom,
            'company_to'      => $data->companyTo,
            'order_at'        => $cmdDate,
        ];
        $this->db->table('order')->insert($order);
        $orderId = $this->db->insertID();
        
        foreach ($data->products as $item) {
            $product = [
                'order_id'           => $orderId,
                'name'               => $item->name,
                'quantity'           => $item->quantity,
                'product_price_type' => $item->unity,
                'price'              => $item->cpm,
            ];
            $this->db->table('order_product')->insert($product);
        }
        $this->db->transComplete();

        return $this->selectOrder($orderId,'order.id, order.progress_status');
    }    

    public function updateOrder(string $orderId, $data): void
    {
        $orderId = (int)$orderId;
        if ($orderId == 0) {
            throw new \Exception(sprintf('Invalid order id %d', $$orderId));
        }
        $this->db->table('order_product')->delete(['order_id' => $orderId]);
        foreach ($data->products as $item) {
            $product = [
                'order_id'           => $orderId,
                'name'               => $item->name,
                'quantity'           => $item->quantity,
                'product_price_type' => $item->unity,
                'price'              => $item->cpm,
            ];
            $this->db->table('order_product')->insert($product);
        }
    }

    public function get(int $id)
    {
        $res =$this->builder->where('id', $id)->get()->getRowArray();

        if(!$res)
            return null;

        $res['from'] = $this->db->table('companies')->select("id, fiscal_name, address_1, zip_code, city_display, phone_number, email")->where('id', $res['company_id'])->get()->getRowArray();
        $res['to'] = $this->db->table('companies')->select("id, fiscal_name, address_1, zip_code, city_display, phone_number, email")->where('id', $res['company_to'])->get()->getRowArray();

        $res['orderProducts'] = $this->db->table("order_product")->where('order_id', $res['id'])->get()->getResultArray();
        $res['vat'] = 120.00;

        return $res;
    }

    public function getCompanyChilds(int $id, array $listCompany): array
    {
        $companyChilds = $this->db->table('companies_to_company')->select('company_id')
        ->where('parent_id', $id)->where('company_id !=', $id)
        ->get()->getResultArray();
        foreach ($companyChilds as $companyChild) {
            $company = $this->db->table('companies')->select('*')->where('id', (int)$companyChild['company_id'])->get()->getRowArray();
            $listCompany[] = $company;
            $listCompany = $this->getCompanyChilds((int)$company['id'], $listCompany);
        }

        return $listCompany;
    }

    public function getListCompanyToOrder(int $id): array
    {
        $listCompany = [];
        $listCompany[] = $this->db->table('companies')->select('*')->where('id', $id)->get()->getRowArray();
        
        return $this->getCompanyChilds($id, $listCompany);
    }


}
