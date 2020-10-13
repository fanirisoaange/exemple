<?php

namespace App\Controllers;

class Migrate extends \CodeIgniter\Controller
{
    public function index()
    {
        $migrate = \Config\Services::migrations();
        try {
            $migrate->latest();
        } catch (\Exception $e) {
            echo '<pre style="background-color:#fff; color:#000">';
            print_r($e);
            echo '</pre>';
        }
    }
}
