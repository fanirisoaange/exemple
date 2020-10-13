<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AppAuth implements FilterInterface {

    public function before(RequestInterface $request) {
        $auth = service('auth');

        if (!$auth->loggedIn()) {
            return redirect('login');
        }
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response) {
        // Do something here
    }

}
