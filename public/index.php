<?php

use App\Core\Route;

// autoloading
require_once __DIR__ . '/../vendor/autoload.php';

// bootstrapping
require_once __DIR__ . '/../libs/helpers.php';
require_once __DIR__ . '/../routes/web.php';

// untuk keperluan session (flash message, old input, login, dsb)
session_start();

// handle request
Route::capture();
