<?php

use Libs\Route;


Route::get('/', 'HomeController@index');

Route::get('/sapa/:nama/:umur', 'HomeController@greet');
Route::get('/users', 'UserController@index');
