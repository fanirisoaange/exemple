<?php

namespace App\Libraries;

class Utils {

    private $db;

    /*
      |===============================================================================
      | Construct
      |===============================================================================
     */

    public function __construct($db) {
        $this->db = $db;
    }

    /*
     * Get All table fields
     */

    public function table_fields($table) {
        $retour = false;
        $builder = $this->db->table('INFORMATION_SCHEMA.COLUMNS');

        $query = $builder->select('COLUMN_NAME')
                ->where('TABLE_NAME', $table)
                ->groupBy('COLUMN_NAME')
                ->get();

        if ($result = $query->getResultArray()):
            $retour = array();
            foreach ($result as $k => $v):
                $retour[] = $v['COLUMN_NAME'];
            endforeach;
        endif;

        return $retour;
    }

    /*
     * Return fields who can be inserted in a table
     */

    public function valid_fields($table, $data) {
        $champs = $this->table_fields($table);
        $data_insert = array();
        foreach ($data as $k => $v) :
            if (in_array($k, $champs)):
                $data_insert[$k] = $v;
            endif;
        endforeach;
        return $data_insert;
    }

    /**
     * Generic insert or update 
     */
    public function insertOrUpdate($post, $table, $id = false) {
        $retour = false;
        if ($id && is_array($id)):
            $post['updated'] = time();
        else:
            $post['created'] = time();
        endif;

        if ($data = $this->valid_fields($table, $post)):
            $builder = $this->db->table($table);
            /*
             * Update
             */
            if ($id && is_array($id)):
                $builder->where($id);
                $retour = [
                    'action' => 'update',
                    'valid' => false,
                ];
                if ($builder->update($data)):
                    $retour['valid'] = true;
                endif;
            /**
             * Insert
             */
            else:
                $retour = [
                    'action' => 'insert',
                    'valid' => false
                ];
                if ($builder->insert($data)):
                    $retour['valid'] = true;
                    $retour['insertId'] = $this->db->insertID();
                endif;
            endif;
        endif;
        return $retour;
    }

}
