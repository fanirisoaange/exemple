<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\CountryModel;
use IonAuth\Models\IonAuthModel;
use App\Enum\ProductServices;
use App\Enum\ProductTypes;

class PriceModel extends Model
{
    public function __construct()
    {
        parent::__construct();

        $this->ionAuth = new \IonAuth\Libraries\IonAuth();
        $this->builder = $this->db->table("product_price");
    }

    public function initCompany(int $company_id) {
        foreach(ProductServices::getAll() as $sk => $sv) {
            foreach(ProductTypes::getAll() as $tk => $tv) {
                $this->builder->insert(["service" => $sk, "type" => $tk, "company_id" => $company_id, "price" => 0.00]);
            }
        }
    }

    public function get(int $company_id) : array {
        $company_id = $company_id == 0 ? null : $company_id;
        $res = array();
        $data = $this->builder->where('company_id', $company_id)->get()->getResultArray();
        foreach($data as $d) {
            if(!isset($res[$d['service']]))
                $res[$d['service']] = array();
            $res[$d['service']][$d['type']] = $d['price'];
        }
        return $res;
    }

    public function edit(array $data, int $company_id) { // data => 2D array Service > Type
        $company_id = $company_id == 0 ? null : $company_id;
        foreach($data as $d1 => $d1v) {
            foreach($d1v as $d2 => $d2v) {
                if($d2v != '') {
                    if(!$this->builder->where(["type" => $d2, "service" => $d1, "company_id" => $company_id])->get()->getResult())
                        $this->builder->insert(["type" => $d2, "service" => $d1, "company_id" => $company_id, "price" => $d2v]);
                    else
                        $this->builder->set(["price" => $d2v])->where(["type" => $d2, "service" => $d1, "company_id" => $company_id])->update();
                }
            }
        }
    }

    public function getDefaultPrice(object $data): float
    {
        $params = [
            'company_id' => (int)$data->companyTo,
            "type"       => (int)$data->type,
            "service"    => (int)$data->service
        ];
        $price = $this->builder->where($params)->get()->getRowArray();
        if (!$price) {
            $params['company_id'] = null;
            $price = $this->builder->where($params)->get()->getRowArray();
        }
        $price = $price ? $price['price'] : null;

        return $price ? (float)$price : 0.00;
    }
}
