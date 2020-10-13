<?php

namespace App\Controllers;

use App\Libraries\Layout;
use App\Helpers\Helper;
use CodeIgniter\Files\File;
use App\Models\UserModel;

class CompanyController extends BaseController
{
    protected $companyModel;
    protected $dataApi = [];
  
    public function __construct()
    {
        $this->companyModel = model('CompanyModel', true, $this->db);
    }

    public function list()
    {      
        $user_model = new UserModel;

        $company_main_id = !empty($this->request->getPostGet('company_main_id')) ? $this->request->getPostGet('company_main_id') : null;
        $companyId = !empty($this->request->getPostGet('company_id')) ? $this->request->getPostGet('company_id') : null;
        $company_parent_id = !empty($this->request->getPost('company_parent_id')) ? $this->request->getPost('company_parent_id') : 0;

        $user_id = (int) session('user_id');
        if(!$user_id){
            throw new Exception("Invalid User");
        }

        $data = array(
            'title' => trad('Companies', 'company'),
            'metadescription' => trad('List of companies', 'company'),
            'content_only' => false,
            'no_js' => false,
            'nofollow' => true,
            'top_content' => array('layout/default/header', 'layout/default/sidebar'),
            'bottom_content' => 'layout/default/footer',
            'content' => 'layout/default/content',
            'page_title' => '<i class="nav-icon far fa-building"></i> '.trad('Companies', 'company'),
            'companies' => $this->companyModel->getCompanies($company_main_id, $company_parent_id, $companyId, $user_id),
            'main_company' => $this->companyModel->getCompany($company_main_id, 'companies.id, companies.fiscal_name'),
        );

        $layout = new Layout();
        $layout->load_assets('default');
        $layout->add_js([
            ASSETS . 'js/companies'
        ]);

        return $layout->view('companies/list', $data);
    }

    public function create()
    {
        $post = $this->request->getPost();
        $formCompany = $this->companyModel->formCompany($post);
        $formCompanyBilling = $this->companyModel->formCompanyBilling($post);          
        if (!empty($post)) {
            $forms = empty($post['billing']) ? array_merge($formCompany, $formCompanyBilling) : $formCompany;
            if ($this->validate($forms)) {
                $resultat = model('CompanyModel')->sendDatasApi('POST',  'creation', $post);
                if ($resultat['resultat']) {
                    $post['id_client_datawork'] = $resultat['id_client'];
                    $companyUpdate = $this->companyModel->saveCompany($post);
                } else {
                    $companyMsg = ['status' => 'error', 'msg' => trad('Company not saved', 'company')];
                }
            }
 
            if (!empty($companyUpdate)) {
                return redirect()->route('company_list');
            } else {
                $companyMsg = ['status' => 'error', 'msg' => trad('Company not saved', 'company')];
            }
        }

        $formCompanyViewData = [
            'form'         => $formCompany,
            'form_title'   => trad('New company', 'company'),
            'validation'   => $this->validation,
            'company_msg'  => !empty($companyMsg) ? $companyMsg : null,
            'created_date' => null,
            'updated_date' => null,
        ];

        $formcompanyBillingViewData = [
            'form'         => $formCompanyBilling,
            'validation'   => $this->validation,
            'created_date' => null,
            'updated_date' => null,
        ];

        $data = array(
            'title'               => trad('Company', 'company'),
            'metadescription'     => trad('Create a new company', 'company'),
            'content_only'        => false,
            'no_js'               => false,
            'nofollow'            => true,
            'top_content'         => array('layout/default/header', 'layout/default/sidebar'),
            'bottom_content'      => 'layout/default/footer',
            'content'             => 'layout/default/content',
            'page_title'          => '<i class="nav-icon far fa-building"></i> '.trad('Company', 'company'),
            'form_company'        => view('companies/form_company', $formCompanyViewData),
            'form_compay_billing' => view('companies/form_company_billing', $formcompanyBillingViewData),
        );


        $layout = new Layout();
        $layout->load_assets('default');
        $layout->add_js([
            ASSETS . 'js/companies'
        ]);

        return $layout->view('companies/create', $data);
    }

    public function detail($companyId)
    {
        $company = $this->companyModel->getCompany($companyId);
        $companyBilling = $this->companyModel->getCompanyBilling($companyId);
        $companyGeolocalisations = $this->companyModel->selectCompanyLocalization($companyId);
        
        $data = array(
            'title' => trad('Company', 'company'),
            'metadescription' => trad('Detail', 'company'),
            'content_only' => false,
            'no_js' => false,
            'nofollow' => true,
            'top_content' => array('layout/default/header', 'layout/default/sidebar'),
            'bottom_content' => 'layout/default/footer',
            'content' => 'layout/default/content',
            'page_title' => '<i class="nav-icon far fa-building"></i> '.trad('Company', 'company'),
            'company' => $company,
            'company_billing' => $companyBilling,
            'company_localizations' => $companyGeolocalisations,
            'main_company' => $this->companyModel->selectCompanySimple($company['main_id'], 'fiscal_name'),
            'parent_company' => $this->companyModel->selectCompanySimple($company['parent_id'], 'fiscal_name'),
        );

        $layout = new Layout();
        $layout->load_assets('default');
        $layout->add_js([
            ASSETS . 'js/companies'
        ]);

        return $layout->view('companies/detail', $data);
    }

    public function edit($companyId)
    {
        $post = $this->request->getPost();
        $company = $this->companyModel->getCompany($companyId);
        $idClientDatawork = $company['id_client_datawork'];
        $formCompany = $this->companyModel->formCompany((!empty($post) ? $post : $company));
        $companyBilling = $this->companyModel->getCompanyBilling($companyId);
        $formCompanyBilling = $this->companyModel->formCompanyBilling((!empty($post) ? $post : $companyBilling));
        if (!empty($post)) {
            $forms = !empty($post['billing']) ? array_merge($formCompany, $formCompanyBilling) : $formCompany;
            // upload CSV file
            $csvFile = $this->uploadCsvFile($companyId);

            if ($this->validate($forms)) {
                $billingId = !empty($companyBilling['id']) ? $companyBilling['id'] : null;
                if ($idClientDatawork > 0) {
                    $resultat = model('CompanyModel')->sendDatasApi('POST', 'modification', $post, $idClientDatawork);
                    if ($resultat['resultat']) {
                        $companyUpdate = $this->companyModel->saveCompany($post, $companyId, null, $csvFile);
                    }
                } else {
                    $companyUpdate = $this->companyModel->saveCompany($post, $companyId, $billingId, $csvFile);
                }
            }

            if (!empty($companyUpdate)) {
                $companyMsg = ['status' => 'success', 'msg' => trad('Company updated', 'company')];
            } else {
                $companyMsg = ['status' => 'error', 'msg' => trad('Company not updated', 'company')];
            }
        }

        $formCompanyViewData = [
            'form'         => $formCompany,
            'form_title'   => trad('Edit company', 'company'),
            'validation'   => $this->validation,
            'created_date' => $company['created'],
            'updated_date' => $company['updated']
        ];

        $formcompanyBillingViewData = [
            'form'         => $formCompanyBilling,
            'validation'   => $this->validation,
            'created_date' => !empty($companyBilling['created']) ? $companyBilling['created'] : '',
            'updated_date' => !empty($companyBilling['updated']) ? $companyBilling['updated'] : '',
        ];

        $data = array(
            'title'               => trad('Company', 'company'),
            'metadescription'     => trad('Create a new company', 'company'),
            'content_only'        => false,
            'no_js'               => false,
            'nofollow'            => true,
            'top_content'         => array('layout/default/header', 'layout/default/sidebar'),
            'bottom_content'      => 'layout/default/footer',
            'content'             => 'layout/default/content',
            'page_title'          => '<i class="nav-icon far fa-building"></i> '.trad('Company', 'company'),
            'form_company'        => view('companies/form_company', $formCompanyViewData),
            'form_compay_billing' => view('companies/form_company_billing', $formcompanyBillingViewData),
            'company_msg'         => !empty($companyMsg) ? $companyMsg : null,
        );
        
        $layout = new Layout();
        $layout->load_assets('default');
        $layout->add_js([
            ASSETS . 'js/companies',
        ]);

        return $layout->view('companies/edit', $data);
    }

    public function getChildrenCompanies()
    {
        $main_id = $this->request->getPost('main_id');
        if (!empty($main_id)) {
            $children = $this->companyModel->getChildrenCompaniesOptions($main_id, true);

            echo json_encode($children, JSON_HEX_QUOT);
        }

        return false;
    }

    public function uploadCsvFile(int $companyId)
    {
        $count = 0;
        $data = [];
        $file = $this->request->getFile('file');
        $extension = $file->getClientExtension();
        if(!$file->getSize()) return $data;
        if ("csv" !== $extension) {
            throw new \Exception(trad("Please enter a valid file with CSV extension"));
        }

        $fp = fopen($file,'r') or die("can't open file");
        $csv = array_map("str_getcsv", file($file,FILE_SKIP_EMPTY_LINES));
        $keys = array_shift($csv);

        while($row = fgetcsv($fp,1024))
        {
            $count++;
            if($count == 1) {
                continue;
            }

            for($i = 0, $j = count($row); $i < $j; $i++) {
                if (empty($row[$i])) {
                    throw new \Exception(sprintf(
                        "column %s in row %s with name %s doesn't empty", $i, $count, $keys[$i])
                    );
                }
                if(count($row)<4) throw new \Exception(sprintf("the number of columns does not match"));
                if(!preg_match("/\d{8}/",$row[0])) throw new \Exception(sprintf("column %s in row %s the IRIS code must be 8 digits : %s ", 0, $i, $row[0]));
                if(!preg_match("/\d{4}/",$row[2])) throw new \Exception(sprintf("column %s in row %s the INSEE code must be 4 digits : %s", 2, $i, $row[2]));

                $insertCsv = array();
                $insertCsv['iris'] = $row[0];
                $insertCsv['name'] = $row[1];
                $insertCsv['insee'] = $row[2];
                $insertCsv['hexacle'] = $row[3];
            }

            $data[] = array(
                'company_id'  => $companyId,
                'postal_code' => null,
                'insee'       => $insertCsv['insee'],
                'iris'        => $insertCsv['iris'],
                'hexacle'     => $insertCsv['hexacle'],
            );
        }

        fclose($fp) or die("can't close file");
        return $data;
    }
}
