<?php

namespace App\Controllers;

use App\Exception\AccessDeniedException;
use App\Libraries\Layout;
use App\Models\InvoiceModel;
use CodeIgniter\HTTP\RedirectResponse;
use Spipu\Html2Pdf\Html2Pdf;
use App\Enum\PaymentMethodStatus;
use App\Enum\InvoiceStatus;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\SetupIntent;
use Stripe\PaymentIntent;

class Invoice extends BaseController
{
    private $data;
    private $layout;

    public function __construct()
    {
        parent::initController(
            \Config\Services::request(),
            \Config\Services::response(),
            \Config\Services::logger()
        );

        $this->data = [
            'top_content' => [
                'layout/default/header',
                'layout/default/sidebar'
            ],
            'bottom_content' => 'layout/default/footer',
            'content' => 'layout/default/content',
        ];
        $this->layout = new Layout();
        $this->layout->load_assets('default');
        $this->layout->add_js(ASSETS . 'js/visualsLib');
        $this->layout->add_js(ASSETS . 'js/invoice');

        $this->companyModel = model('CompanyModel', true, $this->db);
        $this->invoiceModel = model('InvoiceModel', true, $this->db);
        $this->notificationModel = model('NotificationModel', true, $this->db);
        Stripe::setApiKey(getenv('stripe.api_key'));
    }

    public function index()
    {
        helper(["order", "custom"]);
        $id = isset($_SESSION['current_main_company']) ? $_SESSION['current_main_company'] : 0;
        $sid= isset($_SESSION['current_sub_company']) ? $_SESSION['current_sub_company'] : 0;
        $sub = array();
        if($id) {
            foreach($this->companyModel->selectCompanies($id) as $sc) {
                $sub[name_dept($sc['zip_code'])][] = $sc;
            }
        }

         $this->layout->add_css([
            LIBRARY . 'datatables-bs4/css/dataTables.bootstrap4.min',
            LIBRARY . 'datatables-responsive/css/responsive.bootstrap4.min'
        ]);
        $this->layout->add_js([
            LIBRARY . 'datatables/jquery.dataTables.min',
            LIBRARY . 'datatables-bs4/js/dataTables.bootstrap4.min',
            LIBRARY . 'datatables-responsive/js/dataTables.responsive.min',
            LIBRARY . 'datatables-responsive/js/responsive.bootstrap4.min'
        ]);
        $this->data += [
            'page_title' => '<i class="nav-icon fas fa-file-invoice"></i> Invoices list',
            'invoices' => $this->invoiceModel->getAll($sid),
            'companyId' => $id,
            'message' => session_message(),
            'user_sub_companies' => $sub,
            'user_sub_company' => $sid
        ];
        
        return $this->layout->view('invoice/list', $this->data);
    }

    public function create()
    {
        if (!userHasCompanyAccess()) {
            return redirect()->to(base_url().'/dashboard');
        }

        $id = isset($_SESSION['current_sub_company']) ? $_SESSION['current_sub_company'] : 0;
        if(!$id)
            return redirect()->to(base_url() . '/invoice');

        if ($this->request->isAJAX()) {
            $data = json_decode($this->request->getPost('data'));
            $order = $this->orderModel->createOrder($data);
            return json_encode(
                [
                    'orderId' => $order['id'],
                    'message' => trad('Order created successfully'),
                ]
            );
        }
        $data = [
            'top_content' => [
                'layout/default/header',
                'layout/default/sidebar'
            ],
            'bottom_content' => 'layout/default/footer',
            'content' => 'layout/default/content',
        ];
        $this->layout = new Layout();
        $this->layout->load_assets('default');
        $this->layout->add_js(ASSETS . 'js/visualsLib');
        $this->layout->add_js(
            [
                ASSETS.'js/invoice',
                ASSETS.'js/control-fields',
            ]
        );
        $data += [
            'page_title' => '<i class="nav-icon fas fa-file-invoice"></i> Create invoice',
            'breadcrumb' => [
                route_to('invoice') => trad('Invoice List'),
                '' => trad('Create invoice')
            ],
            'orders' => $this->invoiceModel->getAllOrdersUnbilled($id)
        ];

        return $this->layout->view('invoice/create', $data);
    }

    public function createSingle(int $company_id,string $date = '') { //If date unspecified, run all
        if($date) {
            $this->invoiceModel->create($date, 1, $company_id);
            $this->invoiceModel->getOrdersByMonth($date, $company_id);;
        }
    }

    public function dailyCron()
    {
        if ($_SERVER['REMOTE_ADDR'] != getenv('trusted_ip')) {
            throw new AccessDeniedException();
        }

        $date = date("Y-m");
        foreach ($this->invoiceModel->getDistinctCompanyId($date) as $i) {
            echo 'processing company ' . $i['company_id'];
            if ( ! isset($i['company_id'])) {
                throw new \Exception('Invalid company array');
            }
            $this->invoiceModel->create(
                $date,
                InvoiceModel::INVOICE_FROM_COMPANY_ID,
                (int)$i['company_id']
            );
            echo '..... done <br />---------------------------------------<br />';
        }

        echo 'Processing failed invoices --------------- <br>';
       
        $this->invoiceModel->processUpdatedFailedInvoices();
    }

    public function createOne() : RedirectResponse {
        if($d = $this->request->getGet("date")) {
            foreach($this->invoiceModel->getDistinctCompanyId() as $i) {
                $this->createSingle($i['company_id'], $d);
            }
        }
        return redirect()->to(base_url() . '/invoice');
    }

    public function createManual() : RedirectResponse {

        if (!userHasCompanyAccess()) {
            return redirect()->to(base_url().'/dashboard');
        }

        $id = isset($_SESSION['current_main_company']) ? $_SESSION['current_main_company'] : 0;
        if(!$id)
            return redirect()->to(base_url() . '/invoice');

        $id = $this->invoiceModel->createOne($this->request->getPost("date"), 1, $this->request->getPost("companyTo"), $this->request->getPost("subtotal"), $this->request->getPost("orders"));

        $this->notificationModel->sendEnvoiceNotif($id, $this->request->getPost("companyTo"));

        $this->session->set('message', "Invoice created successfully !");
        return redirect()->to(base_url() . "/invoice");
    }

    public function show(int $id = 0) {
        if(!$id || !$invoice = $this->invoiceModel->get($id))
            return redirect()->to(base_url() . '/invoice');

        $this->layout->add_js([
            LIBRARY . 'html2canvas/html2canvas.min'
        ]);

        

        $this->data += [
            'page_title' => '<i class="nav-icon fas fa-file-invoice"></i> Invoice ' . $id,
            'breadcrumb' => [
                '/invoice' => 'Invoice List',
                '' => 'Invoice #' . $id
            ],
            'invoice' => $invoice,
            'session_message' => session_message()
        ];

        if($invoice['status'] == InvoiceStatus::CANCELLED && $invoice['error_code'] == "authentication_required") {
            $pi = PaymentIntent::retrieve($invoice['stripe_payment_id'])->toArray();
            $this->data['client_secret'] = $pi['client_secret'];
        }

        return $this->layout->view('invoice/show', $this->data);
    }

    public function pdf(int $id = 0) {

        if(!userHasCompanyAccess()) {
            return redirect()->to(base_url().'/dashboard');
        }

        if(!$id)
            return redirect()->to(base_url() . '/invoice'); 

        $data = ['invoice' => $this->invoiceModel->get($id)];
        $content = view('invoice/pdf', $data);
        $pdf = new Html2Pdf("p","A4","fr");
        $pdf->pdf->SetAuthor('Cardata');
        $pdf->pdf->SetTitle('Facture ' . $id);
        $pdf->writeHTML($content);
        $pdf->Output('invoice' . $id . '.pdf', 'D');
        die;
    }

    public function setSubCompany() {
        $_SESSION['current_sub_company'] = $this->request->getPost("id");
        echo json_encode(["success" => 200]);
    }

    public function authenticationSuccess() {
        $this->invoiceModel->authenticationSuccess($this->request->getPost("paymentIntent"));
        $_SESSION['message'] = 'Payment successfull';
        echo json_encode(["success" => 200]);
    }
}
