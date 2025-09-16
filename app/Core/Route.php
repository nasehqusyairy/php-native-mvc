<?php

namespace App\Core;

class Route
{
    private static $routes = [];

    private static function add(string $method, string $uri, string $action,  $middleware = [])
    {
        self::$routes[] = [
            'method' => strtoupper($method), // pastikan uppercase
            'uri' => trim($uri, '/'), // hapus slash di awal/akhir
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
        // Simpan routes sementara
        $originalRoutes = self::$routes;

        // Kosongkan routes terlebih dahulu
        self::$routes = [];

        // Jalankan callback untuk menambahkan routes baru
        $callback();

        // Ambil routes yang baru ditambahkan
        $groupedRoutes = self::$routes;

        // Kembalikan routes asli
        self::$routes = $originalRoutes;

        // Tambahkan prefix dan middleware ke routes yang baru ditambahkan
        foreach ($groupedRoutes as $route) {
            // Gabungkan prefix dengan URI route
            $uri = trim($prefix, '/') . '/' . $route['uri'];

            // Hapus double slash jika ada
            $uri = trim($uri, '/');

            // Simpan route dengan prefix dan middleware yang digabung
            $route['uri'] = $uri;

            // Gabungkan middleware
            $route['middleware'] = array_merge($middleware, $route['middleware']);

            // Sekarang, $route berisi route baru yang sudah dimodifikasi

            // Simpan route yang telah dimodifikasi
            self::$routes[] = $route;
        }
    }


    public static function capture()
    {
        $requestUri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        foreach (self::$routes as $route) {

            // Buat pola regex untuk melakukan pencocokan string (antara uri dan route)
            $pattern = "@^" . preg_replace('/:([a-zA-Z]+)/', '([^/]+)', $route['uri']) . "$@";
            /** PENJELASAN
             * 
             * preg_replace('/:([a-zA-Z_]+)/', '([^/]+)', $route['uri']) menggantikan parameter
             * dalam route (misal: :id, :name) dengan pola regex '([^/]+)' yang menangkap
             * satu atau lebih karakter kecuali slash (/).
             * 
             * Misal:
             * Route: users/:id
             * Menjadi pola regex: users/([^/]+)
             * 
             * Kemudian, "@^" dan "$@" ditambahkan untuk memastikan pencocokan dari awal hingga akhir string.
             * Jadi, pola akhir menjadi: @^users/([^/]+)$@
             * 
             */

            // Cek apakah method dan URI cocok
            if ($route['method'] === strtoupper($requestMethod) && preg_match($pattern, $requestUri, $matches)) {
                array_shift($matches);

                /** PENJELASAN
                 * 
                 * preg_match($pattern, $requestUri, $matches) akan mencocokkan pola regex dengan request URI.
                 * Jika cocok, $matches akan berisi seluruh string yang cocok.
                 * 
                 * Misal: 
                 * URI: /users/42
                 * Route: /users/:id
                 * Maka, isi $matches: ['/users/42', '42']
                 * 
                 * array_shift($matches) akan menghapus elemen pertama (seluruh string yang cocok),
                 * sehingga $matches hanya berisi parameter yang ditangkap, misal: ['42']
                 *
                 */

                // jalankan middleware
                foreach ($route['middleware'] as $mw) {
                    $mwObj = new $mw;
                    if (!$mwObj->handle()) {
                        http_response_code(403);
                        echo "Forbidden by middleware";
                        return;
                    }
                }

                $action = explode('@', $route['action']);
                $controller = $action[0];
                $method = $action[1];

                $className = "App\\Controllers\\$controller";
                $obj = new $className();

                return $obj->$method(...$matches);
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }
}
