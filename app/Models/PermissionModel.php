<?php

namespace App\Models;

use CodeIgniter\Model;

class PermissionModel extends Model
{
    protected $session;

    public function __construct()
    {
        parent::__construct();

        helper(['cookie', 'date']);
        $this->session = session();
    }

    public function countInGroup($user_id, $group_id, $main_company_id = null)
    {
        $builder = $this->db->table('users_groups_companies');

        if (!is_null($main_company_id)) {
            $builder->where('main_company_id', $main_company_id);
        }

        if (is_array($group_id)) {
            $builder->whereIn('group_id', $group_id);
        } else {
            $builder->where('group_id', $group_id);
        }

        return $builder->selectCount('id', 'total')
                        ->where('user_id', $user_id)
                        ->get()
                        ->getRowArray();
    }

    public function selectUserMainCompanies($user_id, $columns = 'companies.id, companies.fiscal_name')
    {
        $builder = $this->db->table('users_groups_companies');

        return $builder->select($columns)
                        ->distinct('users_groups_companies.main_company_id')
                        ->join('companies', 'companies.id = users_groups_companies.main_company_id', 'left')
                        ->where('users_groups_companies.user_id', $user_id)
                        ->orderBy('companies.fiscal_name ASC')
                        ->get()
                        ->getResultArray();
        //echo $this->db->getLastQuery()->getQuery();
    }

    //duplicate user model
    public function selectUserGroupsCompanies($user_id, $columns = '*')
    {
        $builder = $this->db->table('users_groups_companies');

        return $builder->select($columns)
                        ->where('user_id', $user_id)
                        ->get()
                        ->getResultArray();
    }

    public function selectGroups($columns = '*')
    {
        $builder = $this->db->table('groups');

        return $builder->select($columns)
                        ->get()
                        ->getResultArray();
    }

    public function selectPermissions($columns = '*')
    {
        $builder = $this->db->table('permissions');

        return $builder->select($columns)
                        ->get()
                        ->getResultArray();
    }

    public function selectGroupPermission($group_id, $permission_id, $columns = '*')
    {
        $builder = $this->db->table('groups_permissions');

        return $builder->select($columns)
                        ->where('group_id', $group_id)
                        ->where('permission_id', $permission_id)
                        ->get()
                        ->getRowArray();
    }

    public function countGroupPermissionByVar($user_id, $main_company, $permission_var)
    {
        $builder = $this->db->table('permissions');
        return $builder->selectCount('groups_permissions.id', 'total')
                        ->join('groups_permissions', 'groups_permissions.permission_id=permissions.id', 'left')
                        ->join('users_groups_companies', 'users_groups_companies.group_id=groups_permissions.group_id')
                        ->where('users_groups_companies.user_id', $user_id)
                        ->where('users_groups_companies.main_company_id', $main_company)
                        ->where('permissions.var', $permission_var)
                        ->get()
                        ->getRowArray();
    }

    public function getUserMainCompaniesId($user_id)
    {
        $main_companies = $this->selectUserMainCompanies($user_id);
        if (!empty($main_companies)) {
            $main_ids = [];

            foreach ($main_companies as $main_company) {
                $main_ids[] = $main_company['id'];
            }

            sort($main_ids);

            return $main_ids;
        }

        return null;
    }

    //duplicate user model
    public function getUserGroupsCompanies($user_id)
    {
        $data = null;
        $groups_companies = $this->selectUserGroupsCompanies($user_id);
        if (!empty($groups_companies)) {
            foreach ($groups_companies as $value) {
                $data[$value['main_company_id']][$value['group_id']][] = $value['company_id'];
            }
        }

        return $data;
    }

    public function isInGroup($user_id, $group_id, $main_company_id = null)
    {
        $res = $this->countInGroup($user_id, $group_id, $main_company_id);
        if (!empty($res['total']) && (int) $res['total'] >= 1) {
            return true;
        }

        return false;
    }

    public function hasGroupPermission($user_id, $main_company, $permission_var)
    {
        $permission = $this->countGroupPermissionByVar($user_id, $main_company, $permission_var);
        if (!empty($permission['total'])) {
            return true;
        }

        return false;
    }

    public function getGroupsPermissions()
    {
        $data = [];
        $groups = $this->selectGroups();
        foreach ($groups as $group) {
            if ($group['id'] != 1 && $group['id'] != 2) {
                $data['groups'][] = ['id' => $group['id'], 'name' => trad($group['name'], 'permission'), 'description' => $group['description']];
                $permissions = $this->selectPermissions();
                foreach ($permissions as $permission) {
                    $data['permissions'][$permission['id']] = ['id' => $permission['id'], 'name' => $permission['module'] . '::' . $permission['action'], 'var' => $permission['var'], 'comments' => $permission['comments']];
                    $group_permission = $this->selectGroupPermission($group['id'], $permission['id'], 'id');
                    $data['groups_permissions'][$permission['id']][$group['id']] = !empty($group_permission) ? true : false;
                }
            }
        }

        return $data;
    }

    public function updateGroupsPermissions($post)
    {
        $res = ['status' => 'error', 'msg' => trad('Error when triying to update the permission')];
        if ($this->db->table('groups_permissions')->delete(['group_id' => $post['group_id'], 'permission_id' => $post['permission_id']])) {
            if (!empty($post['checked']) && $post['checked'] === 'true') {
                if ($this->db->table('groups_permissions')->insert(['group_id' => $post['group_id'], 'permission_id' => $post['permission_id']])) {
                    $res = ['status' => 'success', 'msg' => trad('Permission updated')];
                }
            } else {
                $res = ['status' => 'success', 'msg' => trad('Permission updated')];
            }
        }

        return $res;
    }

    public function formPermission($data)
    {
        if (!empty($data)) {
            $data = trim_data($data);
        }

        $form = [
            'form_action' => ['field' => 'form_action', 'label' => trad('form_action', 'permission'), 'post' => isset($data['form_acion']) ? $data['form_action'] : 'create', 'rules' => 'required'],
            'id' => ['field' => 'form_action', 'label' => trad('id', 'permission'), 'post' => isset($data['id']) ? $data['id'] : '0', 'rules' => 'required'],
            'action' => ['field' => 'action', 'label' => trad('Action', 'permission'), 'post' => isset($data['action']) ? ucwords($data['action']) : '', 'rules' => 'required'],
            'module' => ['field' => 'module', 'label' => trad('Module', 'permission'), 'post' => isset($data['module']) ? ucwords($data['module']) : '', 'rules' => 'required'],
            'var' => ['field' => 'var', 'label' => trad('Var name', 'permission'), 'post' => isset($data['var']) ? $data['var'] : '', 'rules' => 'required'],
            'comments' => ['field' => 'comments', 'label' => trad('comments', 'permission'), 'post' => isset($data['comments']) ? $data['comments'] : '', 'rules' => 'string'],
        ];

        return $form;
    }

    public function savePermission($post)
    {
        if ($post['form_action'] === 'create' && isset($post['var'])) {
            $post['id'] = md5(strtolower(preg_replace('/[^A-Za-z0-9_-]/', '', $post['var'])));
        }

        $post['updated'] = time();

        $form_action = $post['form_action'];
        unset($post['form_action']);

        if (!empty($form_action) && $form_action === 'update' && $this->db->table('permissions')->update($post, ['id' => $post['id']])) {
            return true;
        } else {
            $post['created'] = time();
            if ($this->db->table('permissions')->insert($post)) {
                return true;
            }
        }

        return false;
    }

    public function getUserCompany($userId, $columns = 'companies.id')
    {
        $builder = $this->db->table('users_groups_companies');

        return $builder->select($columns)
            ->distinct('users_groups_companies.main_company_id')
            ->join('companies', 'companies.id = users_groups_companies.main_company_id', 'left')
            ->where('users_groups_companies.user_id', $userId)
            ->where('users_groups_companies.main_company_id', $_SESSION['current_main_company'])
            ->get()
            ->getResultArray();
    }
}
