<?php

require_once __DIR__.'/vendor/autoload.php';

use Dotenv\Dotenv;
use Src\Api\Controllers\TransactionController;
use Src\Api\Controllers\UserController;
use Src\App;
use Src\Middlewares\AllowedToTransferMiddleware;
use Src\Middlewares\AuthMiddleware;
use Src\Request;
use Src\Response;
use Src\Router;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

Router::get('/', function () {
    echo 'Hello World';
});

Router::get('/post/([0-9]*)', function (Request $req, Response $res) {
    $res->toJSON([
        'post' => ['id' => $req->params[0]],
        'status' => 'ok',
    ]);
});

Router::post('/v1/user', function (Request $req, Response $res) {
    (new UserController)->create($req, $res);
});
Router::post('/v1/login', function (Request $req, Response $res) {
    (new UserController)->login($req, $res);
});
Router::get('/v1/me', function (Request $req, Response $res) {
    (new UserController)->me($req, $res);
}, [
    'middleware' => [AuthMiddleware::class],
]);
Router::post('/v1/transaction', function (Request $req, Response $res) {
    (new TransactionController)->create($req, $res);
}, [
    'middleware' => [AuthMiddleware::class, AllowedToTransferMiddleware::class],
]);

App::run();
