<?php

namespace Libs;

use PDO;

class DB
{
    private $conn;
    private static $instance = null;

    public function __construct()
    {
        $this->conn = new PDO('mysql:host=localhost;dbname=for_learning', 'root', '');
    }

    public static function execute($query, $params = [])
    {

        if (self::$instance === null) {
            self::$instance = new DB();
        }

        $stmt = self::$instance->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
