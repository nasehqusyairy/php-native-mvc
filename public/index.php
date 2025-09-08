<?php

use Libs\Route;

// autoloading
spl_autoload_register(function ($class) {
    // ubah namespace jadi path
    $path = __DIR__ . '/../' . str_replace('\\', '/', $class) . '.php';

    if (file_exists($path)) {
        require $path;
    }
});

// bootstrapping
require_once __DIR__ . '/../libs/helpers.php';
require_once __DIR__ . '/../routes/web.php';

session_start();

// Ambil request
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Hapus trailing slash kecuali untuk root
if ($requestUri !== '/' && substr($requestUri, -1) === '/') {
    $requestUri = rtrim($requestUri, '/');
}

$requestMethod = $_SERVER['REQUEST_METHOD'];

// Dispatch router
Route::dispatch($requestUri, $requestMethod);
