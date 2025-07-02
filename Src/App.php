<?php

namespace Src;

use Enqueue\SimpleClient\SimpleClient;

class App
{
    public static function run()
    {
        $client = new SimpleClient('fs://'.__DIR__.'/queue');
        $client->setupBroker();
        $client->bindTopic('account.created', function (\Interop\Queue\Message $message) {
            $data = json_decode($message->getBody(), true);
            echo 'New account created: '.$data['email'].PHP_EOL;
        });

        $client->consume();

        $client->sendEvent('account.created', [
            'id' => 1,
            'email' => 'test@example.com',
            'document' => '12345678901',
        ]);
        Logger::enableSystemLogs();
    }
}
