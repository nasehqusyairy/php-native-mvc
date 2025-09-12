<?php

namespace App\Controllers;

use App\Models\User;

class AuthController
{
    public function index()
    {
        return view('auth/index', [], 'auth');
    }

    public function login()
    {
        // Proses login
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = User::findByEmail($email);

        if ($user && password_verify($password, $user->password)) {
            // Login berhasil
            $_SESSION['user'] = $user->id;
            return redirect('/');
        }

        return back(['danger' => 'Invalid email or password']);
    }

    public function logout()
    {
        session_destroy();
        return redirect('/auth');
    }
}
