<?php

use Libs\Route;

require_once __DIR__ . '/../libs/Route.php';
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
