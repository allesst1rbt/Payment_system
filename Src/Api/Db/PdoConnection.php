<?php

namespace Src\Api\Db;

use PDO;
use PDOException;

class PdoConnection
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            try {
                self::$instance = new PDO(
                    'mysql:host=localhost;port=3312;dbname=payment_system;charset=utf8',
                    'user',
                    'password'
                );
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                exit('Connection failed: '.$e->getMessage());
            }
        }

        return self::$instance;
    }
}
