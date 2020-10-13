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
            ASSETS . 'js/visualsLib'
        ]);

        $this->VLM = model('VisualsLibModel', true, $this->db);
    }

    /**
     * Library
     */
    public function index()
    {
$this->userModel = new \App\Models\UserModel();
        echo '<pre style="background-color:#fff; color:#000">';
        print_r($this->session->get());
        echo '</pre>';
        
        echo 'test<pre style="background-color:#fff; color:#000">';
        print_r(getUserCompanies());
        echo '</pre>';
//        $get_visual = $this->VLM->getVisuals();
//        echo '<pre style="background-color:#fff; color:#000">';
//        print_r($get_visual);
//        echo '</pre>';
    }

    /**
     * Manage Visual
     */
    public function manage($id_visual = false)
    {
        $post = $this->request->getPost();
        $thumbnail = false;
        $parent_category_id = false;
        //EDit visual
        if ($id_visual):
            if (!$get_visual = $this->VLM->getVisuals(['id_visual' => $id_visual])):

                return redirect()->to(route_to('visual_manage'));
            else:
                $data_visual = $post ? $post : $get_visual;
            endif;
            $action = 'edit';
            if ($cat = $this->VLM->getCategory(['id_category' => $data_visual['id_category']])):
                $parent_category_id = $cat['id_parent'];
                //Recherche Thumbnail
                $regen = $get_visual['id_category'] != $data_visual['id_category'] ? true : false;

            endif;
        else:
            //On vérifie que le visuel existe
            $data_visual = $post;
            $data_visual['main_company_id'] = $this->session->get('current_main_company');
            $action = 'add';
            $parent_category_id = false;
        endif;
     
        /**
         * Form Visual
         */
        $form_visual = $this->VLM->formVisual($data_visual);
        $formVisualCode = $this->VLM->formVisualCode($parent_category_id, $data_visual);
//        echo '<pre style="background-color:#fff; color:#000">';
//        print_r($post);
//        echo '</pre>';
        if (isset($post['add_visual'])):
            if ($this->validate($form_visual + $formVisualCode)):
                $upd_id = !empty($post['id_visual']) ? ['id_visual' => $post['id_visual']] : false;

                $send = $this->utils->insertOrUpdate($post, 'visuals', $upd_id);
                if ($send['action'] == 'insert' && !empty($send['insertId'])):
                    return redirect()->to(route_to('visual_manage') . '/' . $send['insertId']);
                else:
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


        /**
         * Visibility
         */
        $formVisibility = $this->VLM->formVisualVisibility();

        /**
         * View
         */
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
            'thumbnail' => $thumbnail
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
        if ($post):
            $id_category = !empty($post['id_category']) ? [
                'id_category' => $post['id_category']] : false;
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

        if (isset($post['add_feature']) || isset($post['edit_feature'])):
            if ($this->validate($form_new_feature)):
                //Si ok on valide
                $id_feature = !empty($post['id_client_feature']) ? [
                    'id_client_feature' => $post['id_client_feature']] : false;
                if ($send = $this->utils->insertOrUpdate($post, 'visuals_company_features', $id_feature)):
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
