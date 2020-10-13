<?php

namespace App\Libraries;

class Permissions {

    protected $session;
    protected $request;
    protected $ionAuth;
    protected $permissionModel;

    public function __construct() {
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
        $this->ionAuth = new \IonAuth\Libraries\IonAuth();
        $this->permissionModel = model('PermissionModel', true, $this->db);
    }

    public function isLoggedIn() {
        if (empty($this->session->get('user_id'))) {
            return false;
        }

        return true;
    }

    public function isInGroup($groups) {
        return $this->permissionModel->isInGroup($this->session->get('user_id'), $groups);
    }

    public function setCurrentMainCompany($post_main_company = null) {

        if (isset($post_main_company)) {
            $main_company = $post_main_company;
        } else if (isAdmin() || isCardata()) {
            $main_company = 0;
        } else {
            $main_company = (int) $this->session->main_companies[0];
        }

        $this->session->set(['current_main_company' => $main_company]);

        return true;
    }

    public function getCurrentMainCompany(){
        return $this->session->get('current_main_company') ?? 0;
    }

    public function setDefaultCurrentSubMainCompany(){
        $main_companies = $this->permissionModel->getUserMainCompaniesId($this->session->get('user_id'));
        if (count($main_companies) == 1) {
            $_SESSION['current_sub_company'] = (int)$main_companies[0];
        }
    }

    public function setMainCompanies() {
        $data = [];
        $data['main_companies'] = $this->permissionModel->getUserMainCompaniesId($this->session->get('user_id'));

        $this->session->set($data);
    }

    public function hasGroupPermission($permission_var) {
        if ($this->isInGroup([1, 2])) {
            return true;
        } else if ($this->permissionModel->hasGroupPermission($this->session->get('user_id'), $this->session->get('current_main_company'), $permission_var)) {
            return true;
        }

        return false;
    }

    public function isInCompany() {
        return count($this->permissionModel->getUserCompany($this->session->get('user_id'))) > 0 ? true : false;
    }
}
