<?php

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected $tableName = 'users';
    protected $fillable = ['name', 'email', 'password'];

    public static function findByEmail($email, $id = null)
    {
        $whereClause = "email = :email";
        $params = ['email' => $email];
        if ($id) {
            $whereClause .= " AND id != :id";
            $params['id'] = $id;
        }
        return static::where($whereClause, $params)->first();
    }
}
