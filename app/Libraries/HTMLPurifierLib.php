<?php

namespace App\Libraries;

require_once("HTMLpurifier/HTMLPurifier.auto.php");


class HTMLPurifierLib {
    public function cleanHTML($html) {
        $config = \HTMLPurifier_Config::createDefault();
        $config->set('HTML.TidyLevel', 'light'); 
        
        $purifier = new \HTMLPurifier($config);
        $clean_html = $purifier->purify($html);
        
        return $clean_html;
    }
}
