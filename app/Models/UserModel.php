<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\CountryModel;
use IonAuth\Models\IonAuthModel;

class UserModel extends Model
{
    protected $super_admin = false;
    protected $cardata_admin = false;
    protected $cardata_user = false;

    //ok
    public function selectUsers($columns = 'users.*')
    {
        $builder = $this->db->table('users');

        return $builder->select($columns)
                        ->join('users_groups_companies', 'users_groups_companies.user_id = users.id', 'left')
                        ->groupBy('users.id')
                        ->orderBy('last_name ASC, first_name ASC')
                        ->get()
                        ->getResultArray();
    }

    //ok
    public function selectUser($user_id, $columns = '*')
    {
        return $this->db->table('users')
                        ->select($columns)
                        ->getWhere(['id' => $user_id])
                        ->getRowArray();
    }

    public function selectUserByEmail(string $mailUser, $columns = '*')
    {
        return $this->db->table('users')
                        ->select($columns)
                        ->getWhere(['email' => $mailUser])
                        ->getRowArray();
    }

    public function listUserByCompany(int $companyId, $columns = '*')
    {
        return $this->db->table('users')
                        ->join('users_groups_companies', 'users.id = users_groups_companies.user_id', 'left')
                        ->join('companies', 'companies.id = users_groups_companies.company_id', 'left')
                        ->select($columns)
                        ->where('companies.id', $companyId)
                        ->get()
                        ->getResultArray();
    }

    //check
    public function selectUserCompanies($user_id, $columns = '*',$adminAccess)
    {
        helper(['permission']);
        if($adminAccess && ( isAdmin() || isCardata() )){
            $company_model = new CompanyModel;
            return $company_model->selectMainCompanies('*');
        }else{           
            $builder = $this->db->table('users_groups_companies');

            return $builder->join('groups', 'groups.id = users_groups_companies.group_id', 'left')
                        ->join('companies', 'companies.id = users_groups_companies.company_id', 'left')
                        ->join('companies_to_company', 'companies_to_company.company_id = companies.id', 'left')
                        ->select($columns)
                        ->where('users_groups_companies.user_id', $user_id)
                        ->orderBy('companies.commercial_name ASC')
                        ->get()
                        ->getResultArray();
        }
    }


    //check duplicate permission model
    public function selectUserGroupsCompanies($user_id, $check_user = false, $columns = '*')
    {
        $builder = $this->db->table('users_groups_companies');

        return $builder->select($columns)
                        ->where('user_id', $user_id)
                        ->orderBy('group_id ASC, company_id ASC')
                        ->get()
                        ->getResultArray();
    }

    //ok
    public function selectGroups($id = null, $columns = '*')
    {
        $builder = $this->db->table('groups');

        return $builder->select($columns)
                        ->orderBy('name ASC')
                        ->get()
                        ->getResultArray();
    }

    public function getUsersInCompany(int $companyId)
    {
        $data = [];
        $users = $this->listUserByCompany($companyId, 'users.id, users.first_name, users.last_name, users.email, users.status');
        $data['columns'] = ['', 'ID', trad('Firstname', 'global'), trad('Lastname', 'global'), trad('Email', 'global'), trad('Status', 'global'),];
        if (!empty($users)) {
            foreach ($users as $value) {
                $data['users'][] = [
                    'id'          => $value['id'],
                    'firstname'   => $value['first_name'],
                    'lastname'    => $value['last_name'],
                    'email'       => $value['email'],
                    'status'      => $value['status'],
                    'status_name' => $this->userStatus()[$value['status']],
                ];
            }
        }

        return $data;
    }

    //ok
    public function getUserDetail($user_id)
    {
        $user = $this->selectUser($user_id);
        if (!empty($user)) {
            $user['status_name'] = $this->userStatus()[$user['status']];
            $user['created'] = date('d/m/Y H:i:s', $user['created']);
            $user['updated'] = date('d/m/Y H:i:s', $user['updated']);
        }
        return $user;
    }

    //check duplicate permission model
    public function getUserGroupsCompanies($user_id)
    {
        $data = [];
        $ugc = $this->selectUserGroupsCompanies($user_id);
        if (!empty($ugc)) {
            foreach ($ugc as $v) {
                $data[$v['group_id']][] = $v['company_id'];
            }
        }

        return $data;
    }

    public function getUserMainCompanies(){
        helper(['permission']);
        if (isAdmin() || isCardata()) {
            return model('CompanyModel')->getMainCompanies();
        }
        elseif (session('user_id')) {
            return model('PermissionModel')->selectUserMainCompanies((int)session('user_id'));
        }
        return [];
    }

    //check
    public function getUsersCompanies($user_id,$withChildrens = false,$adminAccess = false,$idsOnly = false)
    {
        $country_model = new CountryModel;
        $company_model = new CompanyModel;
        $user_companies = $this->selectUserCompanies($user_id,'companies.*,companies_to_company.main_id',$adminAccess);
        $user_companies_id=[];
        foreach ($user_companies as $k => $company) {
            $user_companies[$k]['country_name'] = $country_model->getCountryName($company['country_id'])['name'];
            $user_companies_id[] = $company['id'];
            if($withChildrens){
                $user_companies[$k]['position'] = $company_model->positionCompany($company['id']);
                $user_companies[$k]['children'] = $company_model->selectCompanieChildrenRecursive($company['id'],$user_companies[$k]['position']+1,$user_companies_id);
            }
        }

        return $idsOnly ? $user_companies_id : $user_companies;
    }
    
    //ok
    public function saveUser($post, $user_id = null)
    {
        $user_upsert = false;
        $groups_companies_insert = false;
        $ionauth_model = new IonAuthModel;
        
        $user = [
            'username' => strtolower($post['username']),
            'email' => strtolower($post['email']),
            'first_name' => $post['first_name'],
            'last_name' => $post['last_name'],
            'phone' => $post['phone'],
            //'status' => $post['status'],
            //'active' => $user_active,
            'updated' => time(),
        ];
        if (isMemberAdmin()) {
            $userActive = ($post['status'] == 1 ? 1 : 0);
            $user['status'] = $post['status'];
            $user['active'] = $userActive;
        }
        if (!empty($post['password'])) {
            $user['password'] = $ionauth_model->hashPassword($post['password']);
        }

        if (!empty($user_id)) {//update user
            if ($this->db->table('users')->update($user, ['id' => $user_id])) {
                $user_upsert = true;
            }
        } else {//insert user
            $user['created'] = time();
            if ($this->db->table('users')->insert($user)) {
                $user_id = (int) $this->db->insertID();
                $user_upsert = true;
            }
        }
        if (isMemberAdmin()) {
            //user's companies
            if ($user_upsert) {
                $groups_companies = [];
                foreach ($post['group_companies'] as $k => $v) {
                    foreach ($v['companies'] as $company_id) {
                        $groups_companies[$user_id . $v['group'] . $company_id] = [
                            'user_id' => $user_id,
                            'group_id' => $v['group'],
                            'company_id' => $company_id,
                        ];
                    }
                }
                $this->db->table('users_groups_companies')->delete(['user_id' => $user_id]);
                if ($this->db->table('users_groups_companies')->insertBatch($groups_companies)) {
                    $groups_companies_insert = true;
                }
            }
        }

        if ($user_upsert && $groups_companies_insert) {
            return true;
        }

        return false;
    }

    //ok
    public function getGroups()
    {
        $user_id = (int) session('user_id');
        $groups = $this->selectGroups();

        if (!empty($groups)) {
            foreach ($groups as $k => $group) {
                $groups[$k]['name'] = trad($group['name'], 'user');
                $groups[$k]['description'] = trad($group['description'], 'user');
            }
        }
        return $groups;
    }

    public function userStatus()
    {
        return [
            -2 => trad('Banned', 'global'),
            -1 => trad('Deleted', 'global'),
            0 => trad('New', 'global'),
            1 => trad('Verified', 'global'),
            2 => trad('Pending', 'global'),
        ];
    }

    public function getAccountantCompany(int $companyId, array $users): array
    {
        $builder = $this->db->table('users_groups_companies');

        $result = $builder->select('users.id, users.email, users.first_name, users.last_name')
        ->join('users', 'users_groups_companies.user_id = users.id', 'INNER')
        ->where('users_groups_companies.company_id', $companyId)
        ->where('users.status', 1)->whereIn('users_groups_companies.group_id', [1,2,3,10,11,20,21,30,31])
        ->get()->getResultArray();
        $users = array_merge($users, $result);

        $parentCompany = $this->db->table('companies_to_company')->select('parent_id')->where('company_id', $companyId)->get()->getRowArray();
        if ((int)$parentCompany['parent_id'] > 0) {
            $users = $this->getAccountantCompany((int)$parentCompany['parent_id'], $users);
        }

        return $users;
    }

     public function getUsersByCompany(int $companyId)
    {
        $builder = $this->db->table('users_groups_companies');

        return $builder->select('users.id, users.email, users.first_name, users.last_name')
        ->join('users', 'users_groups_companies.user_id = users.id', 'INNER')
        ->where('users_groups_companies.company_id', $companyId)
        ->where('users.status', 1)->get()->getResultArray();
    }

    public function deleteUser($id): void
    {
        $this->db->table('users')->delete(['id' => $id]);
    }
}
