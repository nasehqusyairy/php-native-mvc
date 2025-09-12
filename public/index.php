<?php

use Libs\Route;

// autoloading
spl_autoload_register(function ($class) {
    // ubah namespace jadi path
    require_once __DIR__ . '/../' . str_replace('\\', '/', $class) . '.php';
});

// bootstrapping
require_once __DIR__ . '/../libs/helpers.php';
require_once __DIR__ . '/../routes/web.php';

// untuk keperluan session (flash message, old input, login, dsb)
session_start();

// Ambil request
$requestUri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Dispatch router
Route::dispatch($requestUri, $requestMethod);
