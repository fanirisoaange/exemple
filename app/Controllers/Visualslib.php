<?php

namespace App\Controllers;

use App\Libraries\Layout;
use App\Libraries\HTMLPurifierLib;

class Visualslib extends BaseController
{

    private $data;
    private $layout;

    public function __construct()
    {
        parent::initController(
            \Config\Services::request(),
            \Config\Services::response(),
            \Config\Services::logger()
        );

        $this->data = [
            'top_content' => [
                'layout/default/header',
                'layout/default/sidebar'
            ],
            'bottom_content' => 'layout/default/footer',
            'content' => 'layout/default/content',
        ];
        $this->layout = new Layout();
        $this->layout->load_assets('default');
        $this->layout->add_css([
            LIBRARY . 'fancybox/jquery.fancybox.min',
            LIBRARY . 'codemirror/lib/codemirror',
            LIBRARY . 'codemirror/theme/dracula',
            LIBRARY . 'codemirror/addon/display/fullscreen',
            ASSETS . 'css/summernote.min',
        ]);
        $this->layout->add_js([
            LIBRARY . 'fancybox/jquery.fancybox.min',
            LIBRARY . 'codemirror/lib/codemirror',
            LIBRARY . 'codemirror/mode/xml/xml',
            LIBRARY . 'codemirror/mode/javascript/javascript',
            LIBRARY . 'codemirror/mode/css/css',
            LIBRARY . 'codemirror/mode/htmlmixed/htmlmixed',
            LIBRARY . 'codemirror/addon/selection/active-line',
            LIBRARY . 'codemirror/addon/display/fullscreen',
            LIBRARY . 'jquery-validation/jquery.validate.min',
            LIBRARY . 'jquery-validation/additional-methods.min',
            ASSETS . 'js/visualsLib',
            ASSETS . 'js/summernote.min'
        ]);

        $this->VLM = model('VisualsLibModel', true, $this->db);
    }

    /**
     * Library
     */
    public function index()
    {
        $users = [];
        $companyFeatureSeletected = null;
        $user_id = (int)session('user_id');
        if (!$user_id) {
            throw new Exception("Invalid User");
        }
        $visuals = $this->VLM->getVisuals();
        $company_features = $this->VLM->getCompanyFeatures();
        $data = array(
            'title' => trad('Visuals Library', 'visual'),
            'metadescription' => trad('List of visuals', 'visual'),
            'content_only' => false,
            'no_js' => false,
            'nofollow' => true,
            'top_content' => array('layout/default/header', 'layout/default/sidebar'),
            'bottom_content' => 'layout/default/footer',
            'content' => 'layout/default/content',
            'page_title' => '<i class="nav-icon far fa-building"></i> ' . trad('Visuals', 'visual'),
            'visuals' => $visuals,
            'company_features' => $company_features,
            'companyFeatureSeletected' => $companyFeatureSeletected,
            'users' => $users
        );

        $layout = new Layout();
        $layout->load_assets('default');
        $layout->add_js([
            ASSETS . 'js/visualsLib'
        ]);
        return $layout->view('visualsLib/index', $data);
    }

    /**
     * Liste des Visual par Ajax
     */
    public function getVisualByAjax(int $id_category = null)
    {
        $id_company = $this->session->current_main_company;
        $post = json_decode($this->request->getPost('data'));
        $users = [];
        $companyFeatureSeletected = null;
        $user_id = (int)session('user_id');
        if (!$user_id) {
            throw new Exception("Invalid User");
        }
        $filters = array('id_category' => $id_category, 'id_company' => $id_company);
        if ($post->features) {
            $features = explode (",", $post->features);
            $filters['features'] = $features;
        }
        $visuals = $this->VLM->getVisuals($filters);
        $company_features = $this->VLM->getCompanyFeatures();
        $title = ($id_category == 3) ? 'Visuals(email)' : 'Visuals(sms)';
        $data = array(
            'title' => trad('Visuals Library', 'visual'),
            'metadescription' => trad('List of visuals', 'visual'),
            'content_only' => false,
            'no_js' => false,
            'nofollow' => true,
            'top_content' => array('layout/default/header', 'layout/default/sidebar'),
            'bottom_content' => 'layout/default/footer',
            'content' => 'layout/default/content',
            'page_title' => '<i class="nav-icon far fa-building"></i> ' . trad($title, 'visual'),
            'visuals' => $visuals,
            'company_features' => $company_features,
            'companyFeatureSeletected' => $companyFeatureSeletected,
            'users' => $users
        );
        return json_encode($data);
    }

    /**
     * Manage Visual
     */

    public function manage($id_visual = false)
    {
        $post = $this->request->getPost();
        $thumbnail = false;
        $category = false;
        // $data_visual = false;

        //EDit visual
        if ($id_visual) :
            $get_visual = $this->VLM->getVisuals(['id_visual' => $id_visual]);
            if (!$get_visual) :
                return redirect()->to(route_to('visual_manage'));
            else :
                $data_visual = $post ? $post : $get_visual;
                /* echo '<p style="background-color:#fff; color:#000; margin: 50px">';
                    print_r($data_visual);
                    echo '</p>'; */
            endif;
            $action = 'edit';
            $category = $data_visual['id_category'];
            //Recherche Thumbnail
            $regen = $get_visual['id_category'] != $data_visual['id_category'] ? true : false;
        else :
            //On vérifie que le visuel existe
            $data_visual = $post;
            $data_visual['main_company_id'] = $this->session->get('current_main_company');
            $action = 'add';
            $category = false;
            // var_dump("Here on add");die;
        endif;

        /**
         * Form Visual
         */
        $form_visual = $this->VLM->formVisual($data_visual);
        $formVisualCode = $this->VLM->formVisualCode($category, $data_visual);

        if (isset($post['add_visual'])) :
            if ($this->validate($form_visual + $formVisualCode)) :
                $upd_id = !empty($post['id_visual']) ? ['id_visual' => $post['id_visual']] : false;
                $send = $this->utils->insertOrUpdate($post, 'visuals', $upd_id);
                if ($send['action'] == 'insert' && !empty($send['insertId'])) :
                    return redirect()->to(route_to('visual_manage') . '/' . $send['insertId']);
                else :
                    return redirect()->to(route_to('visual_manage') . '/' . $id_visual);
                endif;
            endif;
        endif;
        //        echo $this->validation->listErrors() ;

        /**
         * Features
         */

        $features = $this->VLM->getCompanyFeatures(['main_company_id' => $this->session->current_main_company]);
        $form_features = $this->VLM->form_visual_features($features, $post);
        $form_features2 = $this->VLM->form_features($features, $post);

        /**
         * Visibility
         */

        $formVisibility = $this->VLM->formVisualVisibility();

        /**
         * View
         */

//        var_dump($formVisualCode);die;

        $this->data += [
            'page_title' => '<i class="nav-icon far fa-images"></i> Visuals Library',
            'post' => $post,
            'validation' => $this->validation,
            'form_visual' => $form_visual,
            'formVisualCode' => $formVisualCode,
            'form_features' => $form_features,
            'form_visibility' => $formVisibility,
            'action' => $action,
            'id_visual' => $id_visual,
            'visual' => $data_visual,
            'thumbnail' => $thumbnail,
            'form_feat' => $form_features2
        ];

        return $this->layout->view(
            'visualsLib/manage_visual',
            $this->data
        );
    }

    /**
     * Manage Categories
     * @return type
     */
    public function categories()
    {
        /**
         * Post
         */
        $post = $this->request->getPost();
        if ($post) :
            $id_category = !empty($post['id_category']) ? [
                'id_category' => $post['id_category']
            ] : false;
            $send = $this->utils->insertOrUpdate($post, 'visuals_categories', $id_category);
        endif;

        /**
         * Get Categories
         */
        $cat = $this->VLM->formatCategory();

        /**
         * View
         */
        $this->data += [
            'page_title' => '<i class="nav-icon far fa-images"></i> Visuals Library',
            'categories' => $cat,
            'post' => $post
        ];
        return $this->layout->view(
            'visualsLib/category',
            $this->data
        );
    }

    /**
     * Manage features
     * Only for Company level 0
     */
    public function features()
    {

        /**
         * Post
         */
        $post = $this->request->getPost();

        /**
         * Data
         */
        $features = $this->VLM->getCompanyFeatures(['main_company_id' => $this->session->current_main_company]);

        /**
         * Form New
         */
        $form_new_feature = $this->VLM->formFeatures($post);

        if (isset($post['add_feature']) || isset($post['edit_feature'])) :
            if ($this->validate($form_new_feature)) :
                //Si ok on valide
                $id_feature = !empty($post['id_client_feature']) ? [
                    'id_client_feature' => $post['id_client_feature']
                ] : false;
                if ($send = $this->utils->insertOrUpdate($post, 'visuals_company_features', $id_feature)) :
                    return redirect()->back();
                endif;
            endif;
        endif;

        /**
         * View
         */
        $this->data += [
            'page_title' => '<i class="nav-icon far fa-images"></i> Visuals Library',
            'post' => $post,
            'new_feature' => $form_new_feature,
            'features' => $features,
            'validation' => $this->validation
        ];
        return $this->layout->view(
            'visualsLib/features',
            $this->data
        );
    }

    public function test()
    {


        //$html = file_get_contents(ROOTPATH . 'public/test/kit-vente-unique-fevrier-CL.html');

        $html = 'syntax <strong onclick="alert(\'coucou\')">error</small> <myowntag>my text</myowntag><?php echo "coucou"; ?><script type="text/javascript">alert("coucou");</script>';

        $config = array(
            'clean' => true,
            'drop-proprietary-attributes' => true,
            'output-xhtml' => true,
            'word-2000' => true,
            'wrap' => '0',
            'indent' => true,
            'doctype' => 'transitional'
        );

        // Tidy
        $tidy = new \tidy;
        $tidy->parseString(
            $html,
            $config,
            'utf8'
        );
        $tidy->cleanRepair();

        //        if ($tidy->errorBuffer) {
        //            echo "There are some errors!<br />";
        //            $errors = explode("\n", $tidy->errorBuffer);
        //
        //            foreach ($errors as $error) {
        //                echo $error . "<br />";
        //            }
        //        } else {
        //            echo 'There are no errors.';
        //        }
        // Affichage
        //        echo '<xmp>'.$html.'</xmp>';
        //        echo '<br /><br /><br />';
        echo '############################################################################################################################';
        echo '<br /><br /><br />';
        $pattern = "/(?:<[^>]+\s)(on\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[>\"']))+.)[\"']?/";
        echo '<xmp>' . preg_replace(
                $pattern,
                '',
                $tidy
            ) . '</xmp>';


        preg_match_all(
            "/(?:<[^>]+\s)(on\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[>\"']))+.)[\"']?/",
            $tidy,
            $match
        );

        echo '<xmp><pre style="background-color:#fff; color:#000">';
        print_r($match);
        echo '</pre></xmp>';
    }

    public function test2()
    {
        $html = 'syntax <strong onclick="alert(\'coucou\')">error</small> <myowntag>my text</myowntag><?php echo "coucou"; ?><script type="text/javascript">alert("coucou");</script>';


        $purifier = new HTMLPurifierLib();
        $clean_html = $purifier->cleanHTML($html);
        echo '<xmp>' . $clean_html . '</xmp>';
    }
}
