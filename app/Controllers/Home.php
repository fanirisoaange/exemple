<?php

namespace App\Controllers;

use App\Libraries\Layout;
use App\Libraries\Utils;
use App\Libraries\Traductions;

class Home extends BaseController
{
    public function index()
    {
        return redirect()->route('dashboard');
    }

    //--------------------------------------------------------------------

    public function test()
    {
        $options = array(
            'title' => 'test',
            'metadescription' => 'Description trop bien',
            'content_only' => false,
            'no_js' => false,
            'nofollow' => true,
            'top_content' => array('layout/default/header', 'layout/default/sidebar'),
            'bottom_content' => 'layout/default/footer',
            'content' => 'layout/default/content',
            'page_title' => 'Test de page'
        );
        $test = new Layout();
        $test->load_assets('default');
        $test->add_js(['library/html2canvas/html2canvas','library/html2canvas/canvas2image']);
        $test->add_js('assets/js/test');

        echo $test->view('test_view', $options);
    }

    public function test_db()
    {
        $utils = new Traductions();
        $fields = $utils->get_traductions(['id_trad' => 'test-test']);

        echo '<pre style="background-color:#fff; color:#000">';
        print_r($fields);
        echo '</pre>';
    }

    public function capture()
    {
    }
    
}
