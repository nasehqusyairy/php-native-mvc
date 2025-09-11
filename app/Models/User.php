<?php

namespace App\Models;

use Libs\DB;
use Libs\Model;

class User extends Model
{
    protected $tableName = 'users';
    protected $fillable = ['name', 'email', 'password'];

    public static function findByEmail($email)
    {
        $instance = new static;
        $query = "SELECT * FROM {$instance->tableName} WHERE email = :email";
        $result = DB::execute($query, ['email' => $email]);
        return $result ? $result[0] : null;
    }
}
