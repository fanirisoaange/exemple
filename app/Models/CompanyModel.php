<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Enum\CompanyStatus;
use App\Enum\CompanyType;
use App\Models\UserModel;

class CompanyModel extends Model
{
    public function selectCompanyStatus($columns = '*')
    {
        return $this->db->table('status_list')
                        ->select($columns)
                        ->where('type', 'company')
                        ->orderBy('name ASC')
                        ->get()
                        ->getResultArray();
    }

    public function selectCompanySimple($company_id, $columns = '*')
    {
        $builder = $this->db->table('companies');

        return $builder->select($columns)
                        ->getWhere(['id' => $company_id])
                        ->getRowArray();
        //$builder->getCompiledSelect();
    }

    public function selectCompany($companyId, $columns = null)
    {
        if (is_null($columns)) {
            $columns = 'companies.*,companies_to_company.main_id, companies_to_company.parent_id, countries.name AS country_name, status_list.name AS status_name';
        }
        return $this->db->table('companies')
                        ->select($columns)
                        ->join('companies_to_company', 'companies_to_company.company_id=companies.id', 'left')
                        ->join('countries', 'countries.id = country_id', 'left')
                        ->join('status_list', 'status_list.type="company" AND status_list.code=companies.status', 'left')
                        ->getWhere(['companies.id' => $companyId])
                        ->getRowArray();
    }

    public function selectCompanies($main_id = null, $parent_id = null, $company_id = null, $columns = null, $companies_filter = null)
    {
        $builder = $this->db->table('companies_to_company');
        if (is_null($columns)) {
            $columns = 'companies.*,companies_to_company.main_id,companies_to_company.parent_id, countries.name AS country_name, status_list.name AS status_name ';
        }
        if (!is_null($main_id)) {
            $builder->where('companies_to_company.main_id', $main_id);
        }
        if (!is_null($parent_id)) {
            $builder->where('companies_to_company.parent_id', $parent_id);
        }

        if (!is_null($company_id)) {
            $builder->where('companies_to_company.company_id', $company_id);
        }

        if($companies_filter){
            $builder->whereIn('companies_to_company.company_id', $companies_filter);
        }

        return $builder->join('companies', 'companies.id = companies_to_company.company_id', 'left')
                        ->join('countries', 'countries.id=companies.country_id', 'left')
                        ->join('status_list', 'status_list.type="company" AND status_list.code=companies.status', 'left')
                        ->select($columns)
                        ->orderBy('companies_to_company.parent_id ASC, companies.fiscal_name ASC, companies.commercial_name ASC')
                        ->get()
                        ->getResultArray();
    }

    public function selectMainCompanies($columns = 'companies.*',array $filter=['companies_to_company.parent_id'=>0])
    {
        $builder = $this->db->table('companies_to_company');
        return $builder->select($columns)
                        ->join('companies', 'companies.id=companies_to_company.main_id', 'left')
                        ->where($filter)
                        ->orderBy('companies.commercial_name ASC')
                        ->groupBy('companies.id')
                        ->get()
                        ->getResultArray();
    }

    public function countChildrenCompanies($company_id, $column = 'parent_id', $companies_filter = null)
    {
        $builder = $this->db->table('companies_to_company');
        $builder->selectCount($column, 'total')
                        ->where($column, $company_id);
        if($companies_filter){
            $builder->whereIn('companies_to_company.company_id', $companies_filter);
        }
        return $builder->get()->getRowArray();
    }

    public function selectCompanieChildrenRecursive($company_id,$default_position_company=0, &$companiesId = null){
        $companies = $this->selectCompanies(null,$company_id);
        if(!$default_position_company) $default_position_company =$this->positionCompany($company_id)+1;

        foreach ($companies as $key => &$company) {
            $company['position'] = $default_position_company;
            if($companiesId !== null)
                $companiesId[] = $company['id'];
            $company['children'] = $this->selectCompanieChildrenRecursive($company['id'],$default_position_company+1,$companiesId);
        }
        return $companies;
    }

    public function selectCompanyBilling($company_id, $columns = 'companies_billing.*, countries.name AS country_name, status_list.name AS status_name ')
    {
        $builder = $this->db->table('companies_billing');
        return $builder->select($columns)
                        ->where('company_id', $company_id)
                        ->join('countries', 'countries.id = companies_billing.country_id', 'left')
                        ->join('status_list', 'status_list.type="company" AND status_list.code=companies_billing.status', 'left')
                        ->get()
                        ->getRowArray();
    }

    public function selectCompanyLocalization($company_id, $columns = 'company_localization.*')
    {
        $builder = $this->db->table('company_localization');
        return $builder->select($columns)
                        ->where('company_id', $company_id)
                        ->get()
                        ->getResultArray();
    }

    public function getCompany($company_id, $columns = null)
    {
        return $this->selectCompany($company_id, $columns);
    }

    public function getStripeCustomerId(int $company_id) : ?string {
        return $this->db->table('companies')->select("stripe_customer_id")->where('id', $company_id)->get()->getRowArray()['stripe_customer_id'];
    }
    public function setStripeCustomerId(int $company_id, string $stripe_id) {
        $this->db->table('companies')->set(["stripe_customer_id" => $stripe_id])->where('id', $company_id)->update();
    }
    public function getCompanyBilling($company_id, $columns = 'companies_billing.*, countries.name AS country_name, status_list.name AS status_name ')
    {
        return $this->selectCompanyBilling($company_id, $columns);
    }

    public function getCompanies($main_id = null, $parent_id = null, $company_id = null, $user_id = null)
    {
        $user_model = new UserModel;
        $userCompaniesId = [];
        $userMainCompaniesId = [];

        if($user_id){
            $userCompaniesId = $user_model->getUsersCompanies($user_id, true, true, true);
            $userCompaniesId = array_merge($userCompaniesId, $this->getParents($userCompaniesId));
            foreach ($user_model->getUserMainCompanies() as $key => $mainCompany) {
                $userMainCompaniesId[] = $mainCompany['id'];
            }
        }

        if (empty($main_id)) {
            $companies = $this->selectCompanies($main_id, $parent_id, $company_id, null, $userMainCompaniesId);
            if (!empty($companies) && empty($main_id)) {
                foreach ($companies as $kc => $company) {
                    $companies[$kc]['children_total'] = $this->countChildrenCompanies($company['id'], 'main_id', $userCompaniesId )['total'];
                }
            }
        } else {
            $companies = $this->getNestedCompaniesArray($main_id, 'parent_id', 'companies.id, companies.fiscal_name, companies.city_display, status_list.name AS status_name, companies_to_company.main_id, companies_to_company.parent_id, companies_to_company.company_id', $userCompaniesId);
        }
        return $companies;
    }

    public function getParents(array $companies_id, $Idsonly = true){

        $parents = $this->db->table('companies')
            ->select('companies.*')
            ->join('companies_to_company', 'companies_to_company.parent_id=companies.id', 'left')
            ->groupBy('companies.id')
            ->whereIn('companies_to_company.company_id',$companies_id)
            ->get()
            ->getResultArray();

        return !$Idsonly ? $parents : array_map(function($company){ return $company['id']; }, $parents);

    }

    public function getMainCompanies(array $filter=[])
    {
        return $this->selectMainCompanies('companies.id, fiscal_name, commercial_name',$filter);
    }

    public function getMainCompaniesOptions($only_companies = false)
    {
        $options = [];
        $companies = $this->selectMainCompanies('companies.id, fiscal_name, commercial_name');

        if (!$only_companies) {
            $options[0] = 'MAIN COMPANY';
        }
        foreach ($companies as $company) {
            $options[$company['id']] = $company['fiscal_name'];
        }

        return $options;
    }

    public function getChildrenCompaniesOptions($main_id = null, $ajax = false)
    {
        $data = [];
        $data[(string) '0'] = trad('No parent', 'company');
        if (!is_null($main_id)) {
            $mainCompany = $this->selectCompany((int)$main_id);
            $data[($ajax ? ' ' : '') . $main_id] = $mainCompany['fiscal_name'];
            $companies = $this->getNestedCompaniesArray($main_id, 'parent_id', 'companies.id, companies.fiscal_name, companies_to_company.main_id, companies_to_company.parent_id, companies_to_company.company_id');

            if (!empty($companies)) {
                foreach ($companies as $company1) {
                    $data[($ajax ? ' ' : '') . $company1['company_id']] = $company1['fiscal_name'];
                    if (isset($company1['children'])) {
                        foreach ($company1['children'] as $company2) {
                            $data[($ajax ? ' ' : '') . $company2['company_id']] = '&nbsp&nbsp;|_ ' . $company2['fiscal_name'];
                            if (isset($company2['children'])) {
                                foreach ($company2['children'] as $company3) {
                                    $data[($ajax ? ' ' : '') . $company3['company_id']] = '&nbsp;&nbsp;&nbsp&nbsp|_ ' . $company3['fiscal_name'];
                                }
                            }
                        }
                    }
                }
            }
        }

        return $data;
    }

    public function getNestedCompaniesArray($parent_id = null, $parent_index = 'parent_id', $columns = null, $company_filter = [])
    {

        $data = null;
        $companies = $this->selectCompanies($parent_id, null, null, $columns, $company_filter);

        if (!empty($companies)) {
            $data = buildRecursiveArray($companies, $parent_id, $parent_index);
        }
        //exit(varDump($data));
        return $data;
    }

    public function getCompanyStatus()
    {
        return $this->selectCompanyStatus();
    }

    public function getCompanyStatusOptions()
    {
        $options = [];
        $status = $this->selectCompanyStatus('code, name');
        if (!empty($status)) {
            foreach ($status as $s) {
                $options[$s['code']] = $s['name'];
            }
        }

        return $options;
    }

    public function formCompany($data = null)
    {
        if (!empty($data)) {
            $data = trim_data($data);
        }

        $countries = $country_model = new CountryModel;
        //|is_unique[companies.fiscal_name]
        $form = [
            'main_id' => ['field' => 'main_id', 'label' => trad('Main company', 'company'), 'post' => isset($data['main_id']) ? $data['main_id'] : '', 'options' => $this->getMainCompaniesOptions(), 'rules' => 'required'],
            'parent_id' => ['field' => 'parent_id', 'label' => trad('Parent company', 'company'), 'post' => isset($data['parent_id']) ? $data['parent_id'] : '', 'options' => $this->getChildrenCompaniesOptions(isset($data['main_id']) ? $data['main_id'] : null), 'rules' => 'string'],
            'fiscal_name' => ['field' => 'fiscal_name', 'label' => trad('Fiscal name', 'company'), 'post' => isset($data['fiscal_name']) ? $data['fiscal_name'] : '', 'rules' => 'required'],
            'commercial_name' => ['field' => 'commercial_name', 'label' => trad('Commercial name', 'company'), 'post' => isset($data['commercial_name']) ? $data['commercial_name'] : '', 'rules' => 'string'],
            'address_1' => ['field' => 'address_1', 'label' => trad('Address', 'company'), 'post' => isset($data['address_1']) ? $data['address_1'] : '', 'rules' => 'required'],
            'address_2' => ['field' => 'address_2', 'label' => trad('Additional address', 'company'), 'post' => isset($data['address_2']) ? $data['address_2'] : '', 'rules' => 'string'],
            'zip_code' => ['field' => 'zip_code', 'label' => trad('ZIP', 'company'), 'post' => isset($data['zip_code']) ? $data['zip_code'] : '', 'rules' => 'required'],
            'city' => ['field' => 'city', 'label' => trad('City', 'company'), 'post' => isset($data['city']) ? $data['city'] : '', 'rules' => 'required'],
            'city_display' => ['field' => 'city_display', 'label' => trad('City alias', 'company'), 'post' => isset($data['city_display']) ? $data['city_display'] : '', 'rules' => 'string'],
            'country_id' => ['field' => 'country_id', 'label' => trad('Country', 'company'), 'post' => isset($data['country_id']) ? $data['country_id'] : '', 'options' => $countries->getCounriesOptions(), 'rules' => 'required'],
            'vat_number' => ['field' => 'vat_number', 'label' => trad('VAT number', 'company'), 'post' => isset($data['vat_number']) ? format_vat_number($data['vat_number']) : '', 'rules' => 'string'],
            'vat' => ['field' => 'vat', 'label' => trad('VAT', 'company'), 'post' => isset($data['vat']) ? $data['vat'] : '', 'options' => $this->listVat(), 'rules' => 'required'],
            'dealer_ship_id' => ['field' => 'dealer_ship_id', 'label' => trad('Dealer ship ID', 'company'), 'post' => isset($data['dealer_ship_id']) ? $data['dealer_ship_id'] : '', 'rules' => 'string'],
            'site_number' => ['field' => 'site_number', 'label' => trad('Site number', 'company'), 'post' => isset($data['site_number']) ? $data['site_number'] : '', 'rules' => 'string'],
            'phone_number' => ['field' => 'phone_number', 'label' => trad('Phone', 'company'), 'post' => isset($data['phone_number']) ? $data['phone_number'] : '', 'rules' => 'required'],
            'email' => ['field' => 'email', 'label' => trad('E-mail', 'company'), 'post' => isset($data['email']) ? $data['email'] : '', 'rules' => 'valid_email', 'rules' => 'string' . (!empty($data['email']) ? '|valid_emails' : null)],
            'website' => ['field' => 'website', 'label' => trad('Website', 'company'), 'post' => isset($data['website']) ? $data['website'] : '', 'rules' => 'string' . (!empty($data['website']) ? '|valid_url' : null)],
            'orias' => ['field' => 'orias', 'label' => trad('Orias', 'company'), 'post' => isset($data['orias']) ? $data['orias'] : '', 'rules' => 'string'],
            //'billing' => ['field' => 'billing', 'label' => trad('Use as billing address', 'company'), 'post' => isset($data['billing']) ? $data['billing'] : 1, 'rules' => 'string'],
            'comments' => ['field' => 'comments', 'label' => trad('Comments', 'company'), 'post' => isset($data['comments']) ? $data['comments'] : '', 'rules' => 'string'],
            'status' => ['field' => 'status', 'label' => trad('Status', 'company'), 'post' => isset($data['status']) ? $data['status'] : '0', 'options' => $this->getCompanyStatusOptions(), 'rules' => 'string'],
        ];
        return $form;
    }

    public function formCompanyBilling($data = null)
    {
        if (!empty($data)) {
            $data = trim_data($data);
        }
        $arrayKey = [];

        if (is_array($data)) {
            foreach ($data as $k=>$v) {
                if (strpos($k, "billing_") === false) {
                    $arrayKey[] = 'billing_'.$k;
                } else {
                    $arrayKey[] = $k;
                }
            }
            $data = array_combine($arrayKey, array_values($data));
        }

        $countries = $country_model = new CountryModel;
        //|is_unique[companies.fiscal_name]
        $form = [
            'billing_fiscal_name' => ['field' => 'billing_fiscal_name', 'label' => trad('Fiscal name', 'company'), 'post' => isset($data['billing_fiscal_name']) ? $data['billing_fiscal_name'] : '', 'rules' => 'required'],
            'billing_address_1' => ['field' => 'billing_address_1', 'label' => trad('Address', 'company'), 'post' => isset($data['billing_address_1']) ? $data['billing_address_1'] : '', 'rules' => 'required'],
            'billing_address_2' => ['field' => 'billing_address_2', 'label' => trad('Additional address', 'company'), 'post' => isset($data['billing_address_2']) ? $data['billing_address_2'] : '', 'rules' => 'string'],
            'billing_zip_code' => ['field' => 'billing_zip_code', 'label' => trad('ZIP', 'company'), 'post' => isset($data['billing_zip_code']) ? $data['billing_zip_code'] : '', 'rules' => 'required'],
            'billing_city' => ['field' => 'billing_city', 'label' => trad('City', 'company'), 'post' => isset($data['billing_city']) ? $data['billing_city'] : '', 'rules' => 'required'],
            'billing_country_id' => ['field' => 'billing_country_id', 'label' => trad('Country', 'company'), 'post' => isset($data['billing_country_id']) ? $data['billing_country_id'] : '', 'options' => $countries->getCounriesOptions(), 'rules' => 'required'],
            'billing_vat_number' => ['field' => 'billing_vat_number', 'label' => trad('VAT number', 'company'), 'post' => isset($data['billing_vat_number']) ? format_vat_number($data['billing_vat_number']) : '', 'rules' => 'string'],
            'billing_vat' => ['field' => 'billing_vat', 'label' => trad('VAT', 'company'), 'post' => isset($data['billing_vat']) ? $data['billing_vat'] : '', 'options' => $this->listVat(), 'rules' => 'required'],
            'billing_phone_number' => ['field' => 'billing_phone_number', 'label' => trad('Phone', 'company'), 'post' => isset($data['billing_phone_number']) ? $data['billing_phone_number'] : '', 'rules' => 'required'],
            'billing_email' => ['field' => 'billing_email', 'label' => trad('E-mail', 'company'), 'post' => isset($data['billing_email']) ? $data['billing_email'] : '', 'rules' => 'valid_email', 'rules' => 'string' . (!empty($data['email']) ? '|valid_emails' : null)],
            'billing_comments' => ['field' => 'billing_comments', 'label' => trad('Comments', 'company'), 'post' => isset($data['billing_comments']) ? $data['billing_comments'] : '', 'rules' => 'string'],
        ];
        return $form;
    }

    public function listVat()
    {
        return [
            '0' => trad('Vat excluded', 'company'),
            '20.00' => trad('Vat FR', 'company'),
            '21.00' => trad('Vat ES', 'company'),
        ];
    }

    public function saveCompany($post, $company_id = null, $billing_id = null, array $csvFile)
    {
        $res = false;
        $post = trim_data($post);
        $data = $this->setCompanyPostData($post);

        $main_id = preg_replace('/[^0-9]/', '', $data['company']['main_id']);
        $parent_id = !empty($data['company']['parent_id']) ? preg_replace('/[^0-9]/', '', $data['company']['parent_id']) : 0;

        unset($data['company']['main_id']);
        unset($data['company']['parent_id']);

        $companies_to_company = ['main_id' => (!empty($main_id) ? $main_id : $company_id), 'parent_id' => $parent_id, 'company_id' => $company_id];

        if (!empty($company_id) && $this->updateCompany($company_id, $data['company'], $companies_to_company, $csvFile)) {
            $res = true;
        } else {
            $company_id = $this->createCompany($data['company'], $companies_to_company, $csvFile);
            if (!empty($company_id)) {
                $res = true;
            }
        }

        if ($res === true && !empty($data['billing'])) {
            $data['billing']['company_id'] = $company_id;
            if ($this->saveCompanyBilling($data['billing'], $billing_id)) {
                $res = true;
            } else {
                $res = false;
            }
        }

        return $res;
    }

    public function sendDatasApi($method, $action = '', $post = null, $idClientDatawork = null)
    {
        switch ($action) {
            case 'creation':
                $idClientNational = $post['parent_id'] > 0 ? $this->selectCompany((int)$post['parent_id'])['id_client_datawork'] : null;
                $dataCreateCompany = [
                    'nom'                => $post['commercial_name'],
                    'type_client'        => $post['parent_id'] > 0 ? CompanyType::getDescriptionById(CompanyType::LOCAL) : CompanyType::getDescriptionById(CompanyType::NATIONAL),
                    'id_client_national' => $idClientNational,
                ];
                $rep = connectApi($method, '/'.$action, $dataCreateCompany);
                break;
            case 'modification':
                $statut = ($post['status']) ? CompanyStatus::getDescriptionById(CompanyStatus::ACTIF) : CompanyStatus::getDescriptionById(CompanyStatus::INACTIF);
                $dataCreateCompany = [
                    'nom'  => $post['commercial_name'],
                    'etat' => $statut,
                ];
                $rep = connectApi($method, '/'.$idClientDatawork.'/'.$action , $dataCreateCompany);
                break;
            default:
                $rep = connectApi('POST', '', []);
        }
        return json_decode($rep, true);
    }

    public function positionCompany(int $companyId): int
    {
        $columns = 'companies_to_company.parent_id, countries.name AS country_name, status_list.name AS status_name';
        $company = $this->db->table('companies')
                        ->select($columns)
                        ->join('companies_to_company', 'companies_to_company.company_id=companies.id', 'left')
                        ->join('countries', 'countries.id = country_id', 'left')
                        ->join('status_list', 'status_list.type="company" AND status_list.code=companies.status', 'left')
                        ->getWhere(['companies.id' => $companyId])
                        ->getRowArray();
        if ($company['parent_id'] == 0) {
            return 1;
        } else {
            $parentCompany = $this->selectCompany($company['parent_id'], 'companies_to_company.parent_id, companies.id');
            if ($parentCompany['parent_id'] == 0) {
                return 2;
            }
            return 3;
        }
    }

    public function getMainCompany(int $companyId)
    {
        $campaign = $this->db->table('companies_to_company')
                ->select('companies.*')
                ->join('companies', 'companies.id = companies_to_company.company_id', 'right')
                ->getWhere(['companies_to_company.company_id'=>$companyId])
                ->getRowArray();
        return $campaign;
    }

    private function setCompanyPostData($post, $create = false)
    {
        $data = [];
        if (empty($post['billing'])) {
            $post['billing'] = 0;
        }

        foreach ($post as $k => $v) {
            if (strpos($k, "billing_") === false) {
                $data['company'][$k] = $v;
            } else {
                $data['billing'][str_replace('billing_', '', $k)] = $v;
            }
        }

        $data['company']['updated'] = time();
        $data['billing']['updated'] = time();

        if ($create) {
            $data['company']['created'] = time();
            $data['billing']['created'] = time();
        }

        return $data;
    }

    private function updateCompany($company_id, $company, $companies_to_company, array $csvFile)
    {
        unset($company['file']);
        $this->db->transStart();
        $this->db->table('companies')->update($company, ['id' => $company_id]);
        $this->db->table('companies_to_company')->update($companies_to_company, ['company_id' => $company_id]);

        if(count($csvFile)){
            $this->db->table('company_localization')->delete(['company_id' => $company_id]);

            foreach ($csvFile as $csv) {
                $this->db->table('company_localization')->insert($csv);
            }
        }
        $this->db->transComplete();

        if ($this->db->transStatus() !== false) {
            return true;
        }

        return false;
    }

    private function createCompany($company, $companies_to_company, array $csvFile)
    {
        $this->db->transStart();
        $this->db->table('companies')->insert($company);
        $company_id = (int) $this->db->insertID();
        if (empty($companies_to_company['main_id'])) {
            $companies_to_company['main_id'] = $company_id;
        }
        $companies_to_company['company_id'] = $company_id;
        $this->db->table('companies_to_company')->insert($companies_to_company);

        foreach ($csvFile as $csv) {
            $this->db->table('company_localization')->insert($csv);
        }

        $this->db->transComplete();

        if ($this->db->transStatus() !== false) {
            return $company_id;
        }

        return false;
    }

    private function saveCompanyBilling($data, $billing_id = null)
    {
        if (!empty($billing_id) && $this->db->table('companies_billing')->update($data, ['id' => $billing_id])) {
            return true;
        } elseif ($this->db->table('companies_billing')->insert($data)) {
            return true;
        }

        return false;
    }
}
