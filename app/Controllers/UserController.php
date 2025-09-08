<?php

use App\Models\User;

class UserController
{

    public function index()
    {
        $users = User::all();
        view('users/index', [
            'title' => 'User List',
            'users' => $users
        ]);
    }
}
