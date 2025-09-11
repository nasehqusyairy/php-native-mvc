<?php

namespace App\Middlewares;

use App\Models\User;

class Authentication
{
    public function handle()
    {
        if (!isset($_SESSION['user'])) {
            redirect('/auth');
            return false; // Hentikan eksekusi middleware chain
        } else {
            if (!User::find($_SESSION['user'])) {
                unset($_SESSION['user']);
                redirect('/auth');
                return false; // Hentikan eksekusi middleware chain
            }
        }
        return true; // Lanjutkan ke middleware berikutnya atau ke controller
    }
}
