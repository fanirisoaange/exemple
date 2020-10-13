<?php

namespace App\Controllers;

class Functions extends BaseController
{
    public function changeLanguage($lang)
    {
        $this->session->set('lang', $lang);
        return redirect()->back();
    }
}
