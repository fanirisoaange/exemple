<?php

namespace App\Models;

use CodeIgniter\Model;

class CountryModel extends Model
{
    public function selectCountries($columns = '*')
    {
        return $this->db->table('countries')
                        ->select($columns)
                        ->orderBy('favorite DESC, name ASC')
                        ->get()
                        ->getResultArray();
    }

    public function selectCountry($id, $columns = '*')
    {
        return $this->db->table('countries')
                        ->select($columns)
                        ->getWhere(['id' => $id])
                        ->getRowArray();
    }

    public function getCountries()
    {
        return $this->selectCountries();
    }

    public function getCounriesOptions()
    {
        $options = [];
        $countries = $this->selectCountries('id, name');
        if (!empty($countries)) {
            foreach ($countries as $country) {
                $options[$country['id']] = $country['name'];
            }
        }

        return $options;
    }

    public function getCountry($id)
    {
        return $this->selectCountry($id);
    }

    public function getCountryName($id)
    {
        return $this->selectCountry($id, 'name');
    }
}
