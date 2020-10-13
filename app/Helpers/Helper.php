<?php
 /**
  * we put here all helpers variable/methode to be  have  a clean code
  */

namespace App\Helpers;

use GuzzleHttp\Client;

class Helper 
{
  const URLAPI = 'https://test.manager3.datawork.agency/fr/api/client';
       
  private function getTokenApi()
  {
    $time = time();
    return $token = base64_encode($time.':'.hash('sha256', getenv('api.key').':'.getenv('api.password').':'.$time));
  }

  public function apiConnectioRequest($method, $uri = '', $datas = null)
  {
    $defaultData = [
      ['name' => 'api_cle', 'contents' => getenv('api.cle')],
      ['name' => 'token', 'contents' => $this->getTokenApi()],
    ];
    $dataSend = ($datas) ? array_merge($defaultData, $datas) : $defaultData;
    $client = new Client(['verify' => false]);
    $reponse = $client->request($method, self::URLAPI.$uri, ['multipart' => $dataSend]);
    return $reponse;
  }
} 
