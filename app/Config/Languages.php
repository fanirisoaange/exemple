<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Languages extends BaseConfig {

    public $langs = array(
        'en' => array(
            'lang_abbr' => 'en',
            'label' => 'English'
        ),
        'fr' => array(
            'lang_abbr' => 'fr',
            'label' => 'French',
        ),
        'es' => array(
            'lang_abbr' => 'es',
            'label' => 'Spanish',
        ),
//        'de' => array(
//            'lang_abbr' => 'de',
//            'label' => 'Deutch',
//        ),
    );

}
