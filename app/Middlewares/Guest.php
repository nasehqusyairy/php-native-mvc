<?php

namespace App\Middlewares;

class Guest
{
    public function handle()
    {
        if (isset($_SESSION['user'])) {
            redirect('/');
            return false; // Hentikan eksekusi middleware chain
        }
        return true; // Lanjutkan ke middleware berikutnya atau ke controller
    }
}
