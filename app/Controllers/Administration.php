<?php

namespace App\Controllers;

use App\Libraries\Layout;
use App\Libraries\Traductions;

class Administration extends BaseController
{
    private $data;
    private $layout;

    public function __construct()
    {
        $this->data = [
            'top_content' => array('layout/default/header', 'layout/default/sidebar'),
            'bottom_content' => 'layout/default/footer',
            'content' => 'layout/default/content',
        ];
        $this->layout = new Layout();
        $this->layout->load_assets('default');
    }

    public function traductions()
    {
        $trad = new Traductions($this->db);

        $uri = $this->request->uri;

        $traductions = false;
        $alert = false;
        $post = $this->request->getPost();

        //Particular traduction
        if ($token = $uri->getSegment(3)):
            $edit = true;
        if ($traductions = $trad->get_traductions(['token' => $token])):
            $breadcrumb = [
                'traductions_list' => 'Traduction list',
                '' => 'Traduction #'.$token
            ]; else:
                return redirect()->route('traductions_list');
        endif; else:
            $edit = false;
        $breadcrumb = [
                '' => 'Traduction list'
            ];
        endif;

        /**
         * Datas
         */
        $form_selectController = $trad->form_selectController($post);

        /*         * **
         * POST
         */

        if (isset($post['submit_zone'])):
            if ($this->validate($form_selectController)):
                $traductions = $trad->get_traductions(['id_zone' => $post['id_zone']]);
        endif;
        endif;

        $format_trad = $trad->formatTraductions($traductions);


        //Traduction edit
        if (isset($post['edit_trad'])):
            if ($trad->editTraduction($post, $format_trad)):
                $alert = ['class' => 'success', 'content' => 'Editing the traduction was realize with success']; else:
                $alert = ['class' => 'danger', 'content' => 'An error occured'];
        endif;
        endif;

        /*         * ***
         * View
         */

        $this->data += [
            'page_title' => 'Traductions',
            'language' => '',
            'form_select' => $form_selectController,
            'traductions' => $format_trad,
            'edit' => $edit,
            'token' => $token,
            'post' => $post,
            'alert' => $alert,
            'validation' => $this->validation,
            'breadcrumb' => $breadcrumb
        ];
        return $this->layout->view('traductions/list', $this->data);
    }
    
    /**
     * Generate traductions files
     */
    public function traductions_gen()
    {
        $trad = new Traductions($this->db);
        if ($gen = $trad->genTraductionsFiles()):
//            echo '<pre style="background-color:#fff; color:#000">';
//            print_r($gen);
//            echo '</pre>';
            return redirect()->route('traductions_list');
        endif;
    }
}
