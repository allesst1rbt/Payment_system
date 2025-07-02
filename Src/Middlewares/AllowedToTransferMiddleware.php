<?php

namespace Src\Middlewares;

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
                throw new Exception("You can't transfer money to yourself! You should deposit it!", 400);
            }

            if ($user['type'] == 'shopKeeper') {
                throw new Exception("ShopKeepers can't transfer money!", 400);
            }

        } catch (Exception $e) {

            $res->status(400);
            $res->toJSON(['message' => $e->getMessage()]);
        }

        return true;
    }
}
