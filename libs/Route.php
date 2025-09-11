<?php

namespace Libs;

class Route
{
    private static $routes = [];

    private static function add(string $method, string $uri, string $action,  $middleware = [])
    {
        self::$routes[] = [
            'method' => strtoupper($method),
            'uri' => ltrim($uri, '/'),
            'action' => $action,
            'middleware' => $middleware
        ];
    }

    public static function get(string $uri, string $action, array $middleware = [])
    {
        self::add('get', $uri, $action, $middleware);
    }

    public static function post(string $uri, string $action, array $middleware = [])
    {
        self::add('post', $uri, $action, $middleware);
    }

    public static function group(string $prefix, callable $callback, array $middleware = [])
    {
        $originalRoutes = self::$routes;
        self::$routes = [];

        $callback();

        $groupedRoutes = self::$routes;
        self::$routes = $originalRoutes;

        foreach ($groupedRoutes as $route) {
            $uri = trim($prefix, '/') . '/' . $route['uri'];
            $uri = trim($uri, '/'); // pastikan rapi
            $route['uri'] = $uri;
            $route['middleware'] = array_merge($middleware, $route['middleware']);
            self::$routes[] = $route;
        }
    }


    public static function dispatch(string $requestUri, string $requestMethod)
    {
        foreach (self::$routes as $route) {
            // Tambahkan slash di depan saat bikin regex pattern
            $pattern = "@^" . preg_replace('/:([a-zA-Z_]+)/', '([^/]+)', $route['uri']) . "$@";

            if ($route['method'] === strtoupper($requestMethod) && preg_match($pattern, $requestUri, $matches)) {
                array_shift($matches);

                // jalankan middleware
                foreach ($route['middleware'] as $mw) {
                    $mwObj = new $mw;
                    if (!$mwObj->handle()) {
                        http_response_code(403);
                        echo "Forbidden by middleware";
                        return;
                    }
                }

                list($controller, $method) = explode('@', $route['action']);
                $className = "App\\Controllers\\$controller";
                $obj = new $className();
                return call_user_func_array([$obj, $method], $matches);
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }
}
