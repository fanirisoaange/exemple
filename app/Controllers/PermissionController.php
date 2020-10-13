<?php

namespace App\Controllers;

use App\Libraries\Layout;

class PermissionController extends BaseController
{
    protected $permissionModel;

    public function __construct()
    {
        $this->validation = \Config\Services::validation();
        $this->permissionModel = model('PermissionModel', true, $this->db);

        helper(['form', 'url', 'html', 'text', 'custom']);
    }

    public function listGroupsPermissions()
    {
        $post = $this->request->getPost();
        $form_permission = $this->permissionModel->formPermission($post);

        if (!empty($post)) {
            if ($this->validate($form_permission)) {
                if ($this->permissionModel->savePermission(trim_data($post))) {
                    $this->session->setFlashdata('permission_msg', ['status' => 'success', 'msg' => trad('Permission saved', 'permission')]);
                    return redirect()->route('groups_permissions_list');
                } else {
                    $permission_msg = ['status' => 'error', 'msg' => trad('Permission not saved', 'permission')];
                }
            }
        }

        if (!empty($this->session->getFlashdata('permission_msg'))) {
            $permission_msg = $this->session->getFlashdata('permission_msg');
        }

        $form_view_data = [
            'form' => $form_permission,
            'validation' => $this->validation,
            'permission_msg' => !empty($permission_msg) ? $permission_msg : null,
        ];

        $data = array(
            'title' => trad('Groups Permissions', 'permission'),
            'metadescription' => trad("List of the group permission's ", 'permission'),
            'content_only' => false,
            'no_js' => false,
            'nofollow' => true,
            'top_content' => array('layout/default/header', 'layout/default/sidebar'),
            'bottom_content' => 'layout/default/footer',
            'content' => 'layout/default/content',
            'page_title' => trad('Groups Permissions', 'permission'),
            'groups_permissions' => $this->permissionModel->getGroupsPermissions(),
            'form_permission' => view('permissions/form_permission', $form_view_data), //$this->permissionModel->addForm(),
        );

        $layout = new Layout();
        $layout->load_assets('default');
        $layout->add_css([
            LIBRARY . 'icheck-bootstrap/icheck-bootstrap.min',
            LIBRARY . 'datatables-bs4/css/dataTables.bootstrap4.min',
            LIBRARY . 'datatables-responsive/css/responsive.bootstrap4.min',
        ]);
        $layout->add_js([
            LIBRARY . 'datatables/jquery.dataTables.min',
            LIBRARY . 'datatables-bs4/js/dataTables.bootstrap4.min',
            LIBRARY . 'datatables-responsive/js/dataTables.responsive.min',
            LIBRARY . 'datatables-responsive/js/responsive.bootstrap4.min',
            ASSETS . 'js/permissions',
        ]);

        return $layout->view('permissions/group/list', $data);
    }

    public function updateGroupsPermissions()
    {
        $post = $this->request->getPost();
        $update = $this->permissionModel->updateGroupsPermissions($post);

        echo json_encode($update);
    }

    public function test()
    {
        exit(varDump($this->permissions->test()));
    }
}
