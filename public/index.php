<?php

use Libs\Route;

// autoloading
require_once __DIR__ . '/../vendor/autoload.php';

// bootstrapping
require_once __DIR__ . '/../libs/helpers.php';
require_once __DIR__ . '/../routes/web.php';

// load .env


// untuk keperluan session (flash message, old input, login, dsb)
session_start();

// Ambil request
$requestUri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Dispatch router
Route::dispatch($requestUri, $requestMethod);
