<?php

use App\Middlewares\Authentication;
use App\Middlewares\Guest;
use Libs\Route;


Route::group('/', function () {
    Route::get('/', 'HomeController@index');
}, [Authentication::class]);

Route::get('/users', 'UserController@index');
Route::group('users', function () {
    Route::get('/create', 'UserController@create');
    Route::post('/store', 'UserController@store');
    Route::get('/:id', 'UserController@edit');
    Route::get('/:id/delete', 'UserController@delete');
    Route::post('/:id/update', 'UserController@update');
}, [Authentication::class]);

Route::group('auth', function () {
    Route::get('/', 'AuthController@index');
    Route::post('/login', 'AuthController@login');
}, [Guest::class]);
Route::get('/auth/logout', 'AuthController@logout', [Authentication::class]);
