<?php

namespace Libs;

class Route
{
    protected static $routes = [];

    protected static function add($method, $uri, $action, $middleware = [])
    {
        self::$routes[] = [
            'method' => strtoupper($method),
            'uri' => $uri,
            'action' => $action,
            'middleware' => $middleware
        ];
    }

    public static function get($uri, $action, $middleware = [])
    {
        self::add('GET', $uri, $action, $middleware);
    }

    public static function post($uri, $action, $middleware = [])
    {
        self::add('POST', $uri, $action, $middleware);
    }

    public static function dispatch($requestUri, $requestMethod)
    {
        foreach (self::$routes as $route) {
            $pattern = "@^" . preg_replace('/:([a-zA-Z_]+)/', '([^/]+)', $route['uri']) . "$@";

            if ($route['method'] === strtoupper($requestMethod) && preg_match($pattern, $requestUri, $matches)) {

                array_shift($matches);

                // Jalankan middleware chain
                foreach ($route['middleware'] as $mw) {
                    if (is_callable($mw)) {
                        $result = $mw();
                        if ($result === false) {
                            http_response_code(403);
                            echo "Forbidden by middleware";
                            return;
                        }
                    } elseif (is_string($mw) && class_exists($mw)) {
                        $mwObj = new $mw;
                        if (method_exists($mwObj, 'handle')) {
                            $result = $mwObj->handle();
                            if ($result === false) {
                                http_response_code(403);
                                echo "Forbidden by middleware";
                                return;
                            }
                        }
                    }
                }

                // Jalankan action utama
                if (is_callable($route['action'])) {
                    return call_user_func_array($route['action'], $matches);
                }

                if (is_string($route['action']) && strpos($route['action'], '@') !== false) {
                    list($controller, $method) = explode('@', $route['action']);
                    require_once "../app/Controllers/$controller.php";
                    $obj = new $controller;
                    return call_user_func_array([$obj, $method], $matches);
                }
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }
}
