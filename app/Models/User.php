<?php

namespace App\Models;

use Libs\DB;

class User
{

    public static function all()
    {
        $query = "SELECT * FROM users";
        return DB::execute($query);
    }
}
