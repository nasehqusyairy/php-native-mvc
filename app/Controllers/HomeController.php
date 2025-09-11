<?php

namespace App\Controllers;

use App\Models\User;

class HomeController
{
    public function index()
    {
        $user = User::find($_SESSION['user']);
        return view('home/index', ['title' => 'Home Page', 'name' => $user->name]);
    }
}
