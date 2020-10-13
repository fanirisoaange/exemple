<?php
namespace App\Controllers;

use App\Libraries\Layout;
use App\Libraries\Utils;
use App\Libraries\Traductions;

class Work extends BaseController
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
    }

    public function index()
    {
        $tabMethode = get_class_methods($this);
        asort($tabMethode);
        foreach ($tabMethode as $k => $v) :
            if (!in_array($v, ['__construct', 'index', 'initController', 'forceHTTPS', 'cachePage', 'loadHelpers', 'validate'])):
                echo '<a href="/work/'.$v.'">'.$v.'</a><br />';
        endif;

        endforeach;
    }

    public function price()
    {
        /**
         * View
         */
        $this->data += [
            'page_title' => '<i class="fas fa-coins"></i> Price list',
        ];
        return $this->layout->view('work/price', $this->data);
    }

    public function invoice_list()
    {
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
        /**
         * View
         */
        $this->data += [
            'page_title' => '<i class="nav-icon fas fa-file-invoice"></i> Invoices list',
        ];
        return $this->layout->view('work/invoice_list', $this->data);
    }

    public function invoice()
    {
        /**
         * View
         */
        $this->data += [
            'page_title' => '<i class="nav-icon fas fa-file-invoice"></i> Invoice #007612',
            'breadcrumb' => [
                '/work/invoice_list' => 'Invoice List',
                '' => 'Invoice #007612'
            ]
        ];
        return $this->layout->view('work/invoice', $this->data);
    }

    public function order_list()
    {
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
        /**
         * View
         */
        $this->data += [
            'page_title' => '<i class="nav-icon fas fa-file-invoice"></i> Purchase orders list',
        ];
        return $this->layout->view('work/order_list', $this->data);
    }

    public function create_order()
    {
        /**
         * View
         */
        $this->data += [
            'page_title' => '<i class="nav-icon fas fa-file-invoice"></i> Create purchase order',
            'breadcrumb' => [
                '/work/order_list' => 'Purchase order List',
                '' => 'Create order'
            ]
        ];
        return $this->layout->view('work/create_order', $this->data);
    }

    public function purchase_order()
    {
        /**
         * View
         */
        $this->data += [
            'page_title' => '<i class="nav-icon fas fa-file-invoice"></i> Purchase order',
            'breadcrumb' => [
                '/work/order_list' => 'Purchase order List',
                '' => 'Purchase order #1234'
            ]
        ];
        return $this->layout->view('work/purchase_order', $this->data);
    }

    public function payment_method()
    {
        /**
         * View
         */
        $this->data += [
            'page_title' => '<i class="far fa-credit-card"></i> Payment methods',
        ];
        return $this->layout->view('work/payment_method', $this->data);
    }
}
