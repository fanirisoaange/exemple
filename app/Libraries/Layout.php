<?php

namespace App\Libraries;

class Layout {

    private $var = [
        'title' => '',
        'metadescription' => '',
        'content_only' => false,
        'no_js' => false,
        'nofollow' => false,
        'layout' => 'default/layout',
        'top_content' => false,
        'content' => false,
        'bottom_content' => false,
        'css' => array(),
        'js' => array(),
        'body_id' => '',
        'body_class' => 'hold-transition sidebar-mini layout-navbar-fixed',
        'view' => false,
        'breadcrumb' => false,
        'page_title' => false,
        'sidebar_nav' => false,
        'sidebar_selected' => false,
        'languages' => false
    ];
    private $assets = array();
    private $profile = 'cardata';

    /*
      |===============================================================================
      | Construct
      |===============================================================================
     */

    public function __construct() {
        if (ENVIRONMENT == 'development'):
            $this->var['nofollow'] = true;
        endif;
        //Load profiles
        //$this->profile = 'cardata'; //Actual profile        
        $profiles = new \Config\Profiles($this->profile);

        $this->assets = $profiles->assets;
        $this->var['sidebar_nav'] = $profiles->sidebar_nav;


        $languages = new \Config\Languages();
        $this->var['languages'] = $languages->langs;
    }

    /*
      |===============================================================================
      | Method to load view in a template
      |   . view
      |===============================================================================
     */

    public function view($view, $data = array()) {
        if (is_array($data) && count($data) > 0):
            foreach ($data as $k => $v) :
                $this->var[$k] = $v;
            endforeach;
        endif;
        $this->var['view'] = $view;
        return view('layout/' . $this->var['layout'], $this->var);
    }

    /*
      |===============================================================================
      | MÃƒÂ©thods to add CSS and JavaScript
      |   . add_css
      |   . add_js
      |===============================================================================
     */

    public function load_assets($page) {
        //Style par défaut
        if (array_key_exists($page, $this->assets)):
            if (array_key_exists('css', $this->assets[$page])):
                $this->add_css($this->assets['default']['css']);
            endif;
            if (array_key_exists('js', $this->assets[$page])):
                $this->add_js($this->assets['default']['js']);
            endif;
        endif;
    }

    public function add_css($nom) {
        if (is_string($nom) AND!empty($nom) AND file_exists(ROOTPATH . 'public/' . $nom . '.css')) {
            $this->var['css'][] = DIRECTORY_SEPARATOR . $nom . '.css';

            return true;
        } else if (is_array($nom)) {
            foreach ($nom as $value) {
                if (file_exists(ROOTPATH . 'public/' . $value . '.css')):
                    $this->var['css'][] = DIRECTORY_SEPARATOR . $value . '.css';
                endif;
            }
            return true;
        }
        return false;
    }

    public function add_js($nom) {

        if (is_string($nom) AND!empty($nom) AND file_exists(ROOTPATH . 'public/' . $nom . '.js')) {
            $this->var['js'][] = DIRECTORY_SEPARATOR . $nom . '.js';
            return true;
        } else if (is_array($nom)) {
            foreach ($nom as $value) {
                if (file_exists(ROOTPATH . 'public/' . $value . '.js')):
                    $this->var['js'][] = DIRECTORY_SEPARATOR . $value . '.js';
                endif;
            }
            return true;
        }
        return false;
    }

}
