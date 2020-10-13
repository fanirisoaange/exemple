<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\CountryModel;
use IonAuth\Models\IonAuthModel;
use App\Enum\PaymentMethodStatus;

class PaymentMethodModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->ionAuth = new \IonAuth\Libraries\IonAuth();
        $this->builder = $this->db->table("payment_method");
    }

    public function create(int $company_id, string $payment_method, string $type, string $expiry, int $last_four) : array {

        $active = $this->builder->where(['company_id' => $company_id, 'active' => 1])->get()->getRowArray() ? 0 : 1;

         $this->builder->insert(["company_id" => $company_id, 'stripe_id' => $payment_method, 'type' => $type, 'expiry' => $expiry, "last_four" => $last_four, 'status' => PaymentMethodStatus::VERIFIED, 'active' => $active, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
         return ['id' => $this->db->insertId(), 'active' => $active];
    }

    public function getByCompanyId(int $company_id) : array {
        return $this->builder->where(["company_id" => $company_id])->get()->getResultArray();
    }

    public function setActive(int $company_id, int $pm_id) {
        $this->builder->set(["active" => 0])->where("company_id", $company_id)->update();
        $this->builder->set(["active" => 1, "updated_at" => date("Y-m-d H:i:s")])->where("id",$pm_id)->update();
    }

    public function deleteOne(int $company_id, int $pm_id) : bool {
        if(!$this->builder->where(['company_id' => $company_id, 'id' => $pm_id])->get()->getRowArray())
            return false;
        $this->builder->delete(['id' => $pm_id]);
        return true;
    }
}

  
