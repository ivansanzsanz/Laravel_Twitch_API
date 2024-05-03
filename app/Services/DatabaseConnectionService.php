<?php

namespace App\Services;

use mysqli;

class DatabaseConnectionService
{
    public function databaseConnection()
    {
        $host_name = env('DB_HOST');
        $database = env('DB_DATABASE');
        $user_name = env('DB_USERNAME');
        $password = env('DB_PASSWORD');

        $conn = new mysqli($host_name, $user_name, $password, $database);

        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        return $conn;
    }
}
