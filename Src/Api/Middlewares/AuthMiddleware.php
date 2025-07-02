<?php

namespace Src\Api\Middlewares;
use Exception;
use Src\Jwt;
use Src\Request;
use Src\Response;

class AuthMiddleware
{
    public static function handle(Request $req, Response $res)
    {

        if (! preg_match("/^Bearer\s+(.*)$/", $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
            $res->status(400);
            $res->toJSON(['message' => 'incomplete authorization header']);

            return false;
        }

        try {
            $_SESSION['user'] = (new Jwt)->decode($matches[1]);

        } catch (Exception $e) {

            $res->status(400);
            $res->toJSON(['message' => $e->getMessage()]);

            return false;

        }

        return true;
    }
}
