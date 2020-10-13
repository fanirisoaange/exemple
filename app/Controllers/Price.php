<?php

namespace App\Controllers;

use App\Libraries\Layout;
use App\Enum\ProductServices;
use App\Enum\ProductTypes;

class Price extends BaseController
{
    protected $priceModel;

    public function __construct()
    {
        $this->priceModel = model('PriceModel', true, $this->db);
        $this->companyModel = model('CompanyModel', true, $this->db);
        helper(['permission']);
    }

    public function list()
    {
        if(!isAdmin())
            return redirect()->to(base_url() . '/dashboard');

        $id = isset($_SESSION['current_main_company']) ? $_SESSION['current_main_company'] : 0;
        $canEdit = $id ? 1 : isAdmin();

        $m = '';
        if (isset($_SESSION['message'])) {
            $m = $_SESSION['message'];
            unset($_SESSION['message']);
        }

        $data = array(
            'title' => trad('Price list', 'price'),
            'metadescription' => trad('List of prices', 'company'),
            'content_only' => false,
            'no_js' => false,
            'nofollow' => true,
            'top_content' => array('layout/default/header', 'layout/default/sidebar'),
            'bottom_content' => 'layout/default/footer',
            'content' => 'layout/default/content',
            'page_title' => '<i class="nav-icon far fa-building"></i> '.trad('Price list', 'price'),
            //'companies' => $this->companyModel->getCompanies($company_main_id, $company_parent_id, $company_id),
            'companies' => '',
            //'main_company' => $this->companyModel->getCompany($company_main_id, 'companies.id, companies.fiscal_name'),
            'main_company' => '',
            'message' => $m,
            'types' => ProductTypes::getAll(),
            'services' => ProductServices::getAll(),
            'id' => $id,
            'prices' => $this->priceModel->get($id),
            'prices_def' => $this->priceModel->get(0),
            'canEdit' => $canEdit,
            'company_name' => $this->companyModel->selectCompanySimple($id)['fiscal_name']
        );

        $layout = new Layout();
        $layout->load_assets('default');
        $layout->add_js([
            ASSETS . 'js/price'
        ]);

        return $layout->view('price/list', $data);
    }
    public function edit() {
        
        if(!isAdmin())
            return redirect()->to(base_url() . '/dashboard');

        $id = isset($_SESSION['current_main_company']) ? $_SESSION['current_main_company'] : 0;
        $canEdit = $id ? 1 : isAdmin();

        if(!$canEdit)
            return redirect()->to('list');

        switch($this->request->getMethod()) {
            case "get":
                 $data = array(
                    'title' => trad('Price list', 'price'),
                    'metadescription' => trad('List of prices', 'company'),
                    'content_only' => false,
                    'no_js' => false,
                    'nofollow' => true,
                    'top_content' => array('layout/default/header', 'layout/default/sidebar'),
                    'bottom_content' => 'layout/default/footer',
                    'content' => 'layout/default/content',
                    'page_title' => '<i class="nav-icon far fa-building"></i> '.trad('Price list', 'price'),
                    //'companies' => $this->companyModel->getCompanies($company_main_id, $company_parent_id, $company_id),
                    'companies' => '',
                    //'main_company' => $this->companyModel->getCompany($company_main_id, 'companies.id, companies.fiscal_name'),
                    'main_company' => '',
                    'types' => ProductTypes::getAll(),
                    'services' => ProductServices::getAll(),
                    'prices' => $this->priceModel->get($id),
                    'prices_def' => $this->priceModel->get(0),
                    'company_id' => $id,
                    'company_name' => $this->companyModel->selectCompanySimple($id)['fiscal_name']
                 );

                $layout = new Layout();
                $layout->load_assets('default');
                $layout->add_js([
                    ASSETS . 'js/price'
                ]);

                return $layout->view('price/edit', $data);
            break;
            case "post":
                $this->priceModel->edit($this->request->getPost("price"), $id);
                $this->session->set('message', "Price updated successfully.");
                return redirect()->to("list/" . $id);
            break;
        }
       
    }

    public function getDefaultPrice()
    {
        $data = json_decode($this->request->getPost('data'));
        if (property_exists($data, 'companyTo') && property_exists($data, 'type') && property_exists($data, 'service')) {
            $price = $this->priceModel->getDefaultPrice($data);

            return json_encode(['price' => $price]);
        }

        throw new Exception(trad("Parameter missing"));
    }
}
