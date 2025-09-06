<?php

use Libs\Route;


Route::get('/', 'HomeController@index');

Route::get('/:nam/:umur', 'HomeController@greet');
