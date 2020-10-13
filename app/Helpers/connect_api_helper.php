<?php
if ( ! function_exists('connectApi')) {
    function connectApi(string $method, string $uri, array $arrayFieldPost,$debug = false)
    {
        $apiUrl = getenv('api.httpUrl');
        $apiCle = getenv('api.cle');
        $password = getenv('api.password');
        $time = time();        
        $token = base64_encode($time.':'.hash('sha256', $apiCle.':'.$password.':'.$time));
        $infoConnect = [
            'api_cle' => $apiCle,
            'token'   => $token,
        ];
        $data = array_merge($infoConnect, $arrayFieldPost);

        $client = new GuzzleHttp\Client(['verify' => false]);
        
        return $client->request($method, $apiUrl.$uri, ['form_params' => $data])->getBody()->getContents();
    }
}
