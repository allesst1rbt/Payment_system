<?php 


namespace App\Services;


use GuzzleHttp\Client;


class AuthorizationService {

    const BASE_URL = 'https://66ad1f3cb18f3614e3b478f5.mockapi.io/';

    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => self::BASE_URL
        ]);
    }

    public function isServiceAvailable() {
        
        $uri = '/v1/auth';
        try {
            $response = $this->client->request('GET',$uri);
            $response = json_decode($response->getBody(), true);
            
            return $response['message'] == 'Autorizado';
        } catch (\Exception $e) {
            return false;
        }
    }

}