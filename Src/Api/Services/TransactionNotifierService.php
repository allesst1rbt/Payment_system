<?php

namespace App\Services;

use GuzzleHttp\Client;

class TransactionNotifierService
{
    const BASE_URL = 'https://66ad1f3cb18f3614e3b478f5.mockapi.io/';

    public static function sendNotification()
    {

        $uri = '/v1/send';
        try {
            $client = new Client([
                'base_uri' => self::BASE_URL,
            ]);

            $response = $client->request($uri);

            return json_decode($response->getBody(), true);
        } catch (\Exception $exception) {
            throw new \Exception('Error, retry job!');
        }
    }
}
