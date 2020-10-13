<?php

namespace App\Controllers;

use App\Libraries\Layout;

class User extends BaseController
{

    /**
     * User model
     *
     * @var \Models\UserModel
     */
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new \App\Models\UserModel();
        $this->companyModel = new \App\Models\CompanyModel();
        helper(['order']);
    }

    public function list()
    {
        if (!(isMember() || isMemberAccounting())) {
            return accessDenied();
        }
        $id = isset($_SESSION['current_sub_company'])
            ? $_SESSION['current_sub_company'] : null;
        $users = [];
        $users['columns'] = ['', 'ID', trad('Firstname', 'global'), trad('Lastname', 'global'), trad('Email', 'global'), trad('Status', 'global'),];
        if ($id) {
            $users = $this->userModel->getUsersInCompany($id);
        }
        $options = [
            'title'           => trad('User', 'user'),
            'metadescription' => trad('List of users', 'user'),
            'content_only'    => false,
            'no_js'           => false,
            'nofollow'        => true,
            'top_content'     => array('layout/default/header', 'layout/default/sidebar'),
            'bottom_content'  => 'layout/default/footer',
            'content'         => 'layout/default/content',
            'page_title'      => '<i class="fas fa-users"></i> '.trad('User list', 'user'),
            'users'           => $users,
            'user_modal'      => view('users/modal'),
            'companyId'       => isset($_SESSION['current_main_company']) ? $_SESSION['current_main_company'] : null,
        ];
        $layout = new Layout();
        $layout->load_assets('default');
        $layout->add_css([
            LIBRARY . 'datatables-bs4/css/dataTables.bootstrap4.min',
            LIBRARY . 'datatables-responsive/css/responsive.bootstrap4.min'
        ]);
        $layout->add_js([
            LIBRARY . 'datatables/jquery.dataTables.min',
            LIBRARY . 'datatables-bs4/js/dataTables.bootstrap4.min',
            LIBRARY . 'datatables-responsive/js/dataTables.responsive.min',
            LIBRARY . 'datatables-responsive/js/responsive.bootstrap4.min',
            ASSETS . 'js/users',
            ASSETS . 'js/helper',
        ]);

        return $layout->view('users/list', $options);
    }

    public function create()
    {
        if (!isHeadAdmin()) {
            return accessDenied();
        }
    }

    public function detail($id)
    {
        $options = array(
            'title' => trad('User', 'user'),
            'metadescription' => trad('User', 'user'),
            'content_only' => false,
            'no_js' => false,
            'nofollow' => true,
            'top_content' => array('layout/default/header', 'layout/default/sidebar'),
            'bottom_content' => 'layout/default/footer',
            'content' => 'layout/default/content',
            'page_title' => '<i class="far fa-user"></i> '.trad('User', 'user'),
            'user_detail' => $this->userModel->getUserDetail($id),
            'groups' => $this->userModel->getGroups(),
            'companies' => $this->companyModel->getCompanies(),
            'user_groups_companies' => $this->userModel->getUserGroupsCompanies($id),
        );
        $layout = new Layout();
        $layout->load_assets('default');
        $layout->add_js([
            ASSETS . 'js/users'
        ]);

        return $layout->view('users/detail', $options);
    }

    public function edit($id)
    {
        if (!userHasEditUserAccess($id)) {
            return accessDenied();
        }
        if (!empty($this->request->getPost())) {
            $save = $this->save($id);
        }

        $options = array(
            'title' => trad('Edit user', 'user'),
            'metadescription' => trad('User', 'user'),
            'content_only' => false,
            'no_js' => false,
            'nofollow' => true,
            'top_content' => array('layout/default/header', 'layout/default/sidebar'),
            'bottom_content' => 'layout/default/footer',
            'content' => 'layout/default/content',
            'page_title' => '<i class="far fa-user"></i> '.trad('Edit user', 'user'),
            'user_detail' => $this->userModel->getUserDetail($id),
            'user_status' => $this->userModel->userStatus(),
            'groups' => $this->userModel->getGroups(),
            'companies' => $this->userModel->getUsersCompanies($id),
            'user_groups_companies' => $this->userModel->getUserGroupsCompanies($id),
            'user_id' => $id,
        );
        $layout = new Layout();
        $layout->load_assets('default');
        $layout->add_js([
            LIBRARY . 'select2/js/select2.full.min',
            ASSETS . 'js/users'
        ]);
        $layout->add_css([
            LIBRARY . 'icheck-bootstrap/icheck-bootstrap.min',
            LIBRARY . 'select2/css/select2.min',
            LIBRARY . 'select2-bootstrap4-theme/select2-bootstrap4.min',
        ]);


        return $layout->view('users/edit', $options);
    }

    public function getAccountantCompanyByAjax(int $companyTo)
    {
        return json_encode(model('UserModel')->getAccountantCompany($companyTo, []));
    }

    public function ajaxUserDelete(int $id)
    {
        if (!isHeadAdmin()) {
            return accessDenied();
        }
        $this->userModel->deleteUser($id);
        return json_encode(['message' => trad('User deleted successfully')]);
    }

    private function save($user_id)
    {
        $message = $this->userModel->saveUser($this->request->getPost(), $user_id);

        return $message;
    }
}
