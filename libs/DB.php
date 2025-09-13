<?php

namespace Libs;

use PDO;

class DB
{
    private $conn;
    private static $instance = null;

    public function __construct()
    {
        $env = parse_ini_file(__DIR__ . '/../.env');
        $this->conn = new PDO('mysql:host=' . $env['DB_HOST'] . ';dbname=' . $env['DB_DATABASE'], $env['DB_USERNAME'], $env['DB_PASSWORD']);
    }

    public static function execute($query, $params = [])
    {

        if (self::$instance === null) {
            self::$instance = new static;
        }

        $stmt = self::$instance->conn->prepare($query);

        // sanitasi input (lakukan trim dan htmlspecialchars)
        foreach ($params as $key => $value) {
            if (is_string($value)) {
                $params[$key] = htmlspecialchars(trim($value));
            }
        }

        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
