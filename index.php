<?php

require_once __DIR__.'/vendor/autoload.php';

use Dotenv\Dotenv;
use Src\Api\Controllers\AccountController;
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

Router::post('/v1/user', function (Request $req, Response $res) {
    (new UserController)->create($req, $res);
});
Router::post('/v1/login', function (Request $req, Response $res) {
    (new UserController)->login($req, $res);
});
Router::get('/v1/user/me', function (Request $req, Response $res) {
    (new UserController)->me($req, $res);
}, [
    'middleware' => [AuthMiddleware::class],
]);
Router::post('/v1/transaction', function (Request $req, Response $res) {
    (new TransactionController)->create($req, $res);
}, [
    'middleware' => [AuthMiddleware::class, AllowedToTransferMiddleware::class],
]);
Router::post('/v1/reversal', function (Request $req, Response $res) {
    (new TransactionController)->reversal($req, $res);
}, [
    'middleware' => [AuthMiddleware::class],
]);
Router::get('/v1/transaction', function (Request $req, Response $res) {
    (new TransactionController)->show($req, $res);
}, [
    'middleware' => [AuthMiddleware::class],
]);

Router::put('/v1/account/withdraw', function (Request $req, Response $res) {
    (new AccountController)->withDraw($req, $res);
}, [
    'middleware' => [AuthMiddleware::class],
]);
Router::put('/v1/account/deposit', function (Request $req, Response $res) {
    (new AccountController)->deposit($req, $res);
}, [
    'middleware' => [AuthMiddleware::class],
]);

App::run();
