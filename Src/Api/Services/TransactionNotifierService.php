<?php

namespace Src\Api\Services;

use GuzzleHttp\Client;

class TransactionNotifierService
{
    public const BASE_URL = 'https://66ad1f3cb18f3614e3b478f5.mockapi.io';
    
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => self::BASE_URL,
        ]);
    }

    public function sendNotification()
    {

        $uri = '/v1/send';
        try {
            

            $response = $this->client->request('GET', $uri);
            $response = json_decode($response->getBody(), true);
            
            return  $response;
        } catch (\Exception $exception) {
            throw new \Exception('Error, sending message!');
        }
    }
}
