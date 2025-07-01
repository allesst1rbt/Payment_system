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
                    'mysql:host=127.0.0.1;port=3312;dbname=mydb;charset=utf8',
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
