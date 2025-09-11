<?php

namespace App\Models;

use Libs\Model;

class User extends Model
{
    protected $tableName = 'users';
    protected $fillable = ['name', 'email', 'password'];

    public static function findByEmail($email, $id = null)
    {
        $query = "email = :email";
        $params = ['email' => $email];
        if ($id) {
            $query .= " AND id != :id";
            $params['id'] = $id;
        }
        return static::where($query, $params)->first();
    }
}
