<?php

namespace App\Controllers;

use App\Libraries\Layout;
use App\Libraries\Utils;
use App\Libraries\Traductions;
use App\Enum\PaymentMethodStatus;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\SetupIntent;
use Stripe\PaymentMethod as StripePaymentMethod;

class PaymentMethod extends BaseController
{
    protected $companyModel;

    public function __construct()
    {
        $this->companyModel = model('CompanyModel', true, $this->db);
        $this->paymentMethodModel = model('PaymentMethodModel', true, $this->db);
        $this->ionAuth = new \IonAuth\Libraries\IonAuth();
        Stripe::setApiKey(getenv('stripe.api_key'));
        helper(["payment_method", "custom"]);
    }
    public function index()
    {
        $id = isset($_SESSION['current_main_company']) ? $_SESSION['current_main_company'] : NULL;
      
        $options = array(
            'title' => 'test',
            'metadescription' => trad('payment methods'),
            'content_only' => false,
            'no_js' => false,
            'nofollow' => true,
            'top_content' => array('layout/default/header', 'layout/default/sidebar'),
            'bottom_content' => 'layout/default/footer',
            'content' => 'layout/default/content',
            'page_title' => 'Payment method',
            'companyId' => $id,
            'session_message' => session_message(),
            'data' => $this->paymentMethodModel->getByCompanyId($id)
        );
        $layout = new Layout();
        $layout->load_assets('default');
        $layout->add_js([ASSETS . "js/payment_method"]);

        echo $layout->view('payment_method/index', $options);
    }

    public function add() {
        session_message();
        $id = isset($_SESSION['current_main_company']) ? $_SESSION['current_main_company'] : NULL;
        $sid = $this->companyModel->getStripeCustomerId($id);
        if($sid) {
                $c = $this->companyModel->getCompany($id);
                $customer = Customer::create(['email' => $c['email'], 'name' => $c['fiscal_name'], 'address' => ['line1' => $c['address_1'], 'city' => $c['city'], 'postal_code' => $c['zip_code']], 'phone' => $c['phone_number']]);
                $this->companyModel->setStripeCustomerId($id, $customer['id']);
                $sid = $customer['id'];
        }

        $setup_intent = SetupIntent::create([
          'customer' => $sid,
        ]);
        $client_secret = $setup_intent->client_secret;

        $options = array(
            'title' => 'test',
            'metadescription' => trad('payment methods'),
            'content_only' => false,
            'no_js' => false,
            'nofollow' => true,
            'top_content' => array('layout/default/header', 'layout/default/sidebar'),
            'bottom_content' => 'layout/default/footer',
            'content' => 'layout/default/content',
            'page_title' => 'Payment method',
            'client_secret' => $client_secret
        );
        $layout = new Layout();
        $layout->load_assets('default');
        $layout->add_js([ASSETS . "js/payment_method"]);
       
        echo $layout->view('payment_method/add', $options);
    }

    public function setupIntent() {
        $id = isset($_SESSION['current_main_company']) ? $_SESSION['current_main_company'] : NULL;
        $sid = $this->companyModel->getStripeCustomerId($id);
        if(!$sid) {
                $c = $this->companyModel->getCompany($id);
                $customer = Customer::create(['email' => $c['email'], 'name' => $c['fiscal_name'], 'address' => ['line1' => $c['address_1'], 'city' => $c['city'], 'postal_code' => $c['zip_code']], 'phone' => $c['phone_number']]);
                $this->companyModel->setStripeCustomerId($id, $customer['id']);
                $sid = $customer['id'];
        }
        $setup_intent = SetupIntent::create([
          'customer' => $sid,
        ]);
        $client_secret = $setup_intent->client_secret;
        echo json_encode(["status" => 200, 'customer_id' => $sid, "client" => $client_secret]);
    }

    public function create() {
        $id = isset($_SESSION['current_main_company']) ? $_SESSION['current_main_company'] : NULL;
        if($id === null)
            return json_encode(["error" => "Unauthorized"]);

        $pmid = $this->request->getPost('pm');
        $pm = StripePaymentMethod::retrieve($pmid, [])->toArray();

        if($pm) {
            $exp = ($pm['card']['exp_month'] < 10 ? '0' . $pm['card']['exp_month'] : $pm['card']['exp_month']) . "/" . substr(strval($pm['card']['exp_year']), -2);
            $last_four = $pm['card']['last4'];

            $create = $this->paymentMethodModel->create($id, $pmid, $this->request->getPost("type"), $exp, $last_four);
            ob_start();
            payment_method_status(PaymentMethodStatus::VERIFIED);
            $status = ob_get_clean();
            $_SESSION['message'] = 'Payment method added successfully';
            return json_encode(["success" => true]);
        }
        else
            return json_encode(['error' => 'Invalid payment method ID']);
    }

    public function setActive() : string {
        $id = isset($_SESSION['current_main_company']) ? $_SESSION['current_main_company'] : NULL;
        if($id === null)
            return json_encode(["error" => "Unauthorized"]);

        if($this->request->getPost("id")) {
            $this->paymentMethodModel->setActive($id, $this->request->getPost("id"));
            return json_encode(["success" => true]);
        }
        return json_encode(["error" => 'Missing payment method ID']);
    }

    public function delete() : string {
        $id = isset($_SESSION['current_main_company']) ? $_SESSION['current_main_company'] : NULL;
        if($id === null)
            return json_encode(["error" => "Unauthorized"]);

        if($this->request->getPost("id")) {
            if($this->paymentMethodModel->deleteOne($id, $this->request->getPost("id")))
                return json_encode(["success" => true]);
            else
                return json_encode(["error" => "Unauthorized"]);
        }
        return json_encode(["error" => 'Missing payment method ID']);
    }
}
