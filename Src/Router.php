<?php

namespace Src;

class Router
{
    public static function get($route, $callback, $options = [])
    {
        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') !== 0) {
            return;
        }

        self::on($route, $callback, $options);
    }

    public static function post($route, $callback, $options = [])
    {
        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) {
            return;
        }

        self::on($route, $callback, $options);
    }

    public static function put($route, $callback, $options = [])
    {
        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'PUT') !== 0) {
            return;
        }

        self::on($route, $callback, $options);
    }

    public static function on($regex, $cb, $options = [])
    {
        $uri = $_SERVER['REQUEST_URI'];
        $uri = (stripos($uri, '/') !== 0) ? '/'.$uri : $uri;
        $regex = str_replace('/', '\/', $regex);
        $is_match = preg_match('/^'.($regex).'$/', $uri, $matches, PREG_OFFSET_CAPTURE);

        if ($is_match) {
            array_shift($matches);
            $params = array_map(fn ($param) => $param[0], $matches);

            $req = new Request($params);
            $res = new Response;

            $middlewares = $options['middleware'] ?? [];

            foreach ($middlewares as $middleware) {

                if (is_callable($middleware)) {
                    $result = $middleware($req, $res);
                } elseif (class_exists($middleware) && method_exists($middleware, 'handle')) {
                    $result = call_user_func([$middleware, 'handle'], $req, $res);
                } else {
                    throw new \Exception("Invalid middleware: $middleware");
                }

                if ($result === false) {
                    return;
                }
            }
            try {
                $cb($req, $res);
            } catch (\Exception $e) {
                $res->status($e->getCode());
                $res->toJSON(['message' => $e->getMessage()]);
            }

        }
    }
}
