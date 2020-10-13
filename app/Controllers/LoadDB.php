<?php

namespace App\Controllers;

use CodeIgniter\Database\MySQLi\Connection;

class LoadDB extends BaseController
{
    public function index()
    {
        $ionAuth = new \IonAuth\Libraries\IonAuth();
        
        try {

            $lines = file(FCPATH.'../documentation/db.sql');
            /** @var Connection $db */
            $templine = '';
            foreach ($lines as $line) {
                if (substr($line, 0, 2) == '--' || $line == '') {
                    continue;
                }

                $templine .= $line;

                if (substr(trim($line), -1, 1) == ';') {
                    $this->db->query($templine);
                    $templine = '';
                }
            }
            $_SESSION['current_main_company'] = 0;
            $_SESSION['current_sub_company'] = 0;

            $ionAuth->logout();
            return redirect()->route('login');

        } catch (\Exception $e) {
            echo '<pre style="background-color:#fff; color:#000">';
            print_r($e);
            echo '</pre>';
        }
    }
}
