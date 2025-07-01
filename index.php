<?php

require_once __DIR__.'/vendor/autoload.php';

use Src\Api\Controllers\UserController;
use Src\App;
use Src\Request;
use Src\Response;
use Src\Router;
use Dotenv\Dotenv;
use Src\Middlewares\AuthMiddleware;

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
},[
    'middleware' => [AuthMiddleware::class]
]);

App::run();
