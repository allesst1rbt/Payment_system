<?php

namespace Src\Api\Middlewares;

use Exception;
use Src\Request;
use Src\Response;

class AllowedToTransferMiddleware
{
    public static function handle(Request $req, Response $res)
    {

        try {

            $user = $_SESSION['user'];

            if ($user['id'] == ($req->getJSON())->payee) {
                $res->status(400);
                $res->toJSON(['message' => "You can't transfer money to yourself! You should deposit it!"]);

                return false;

            }

            if ($user['type'] == 'shopKeeper') {
                $res->status(400);
                $res->toJSON(['message' => "ShopKeepers can't transfer money!"]);

                return false;

            }

        } catch (Exception $e) {

            $res->status(400);
            $res->toJSON(['message' => $e->getMessage()]);

            return false;
        }

        return true;
    }
}
