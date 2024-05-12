<?php

namespace App\Services;

//use PDO;
//use PDOException;

use mysqli;

class DatabaseConnectionService
{
    private $host_name;
    private $database;
    private $port;
    private $user_name;
    private $password;
    public $conn;

    //private $dsn;
    //protected $pdo;

    public function __construct()
    {
        $this->host_name = env('DB_HOST');
        $this->database = env('DB_DATABASE');
        $this->port = env('DB_PORT');
        $this->user_name = env('DB_USERNAME');
        $this->password = env('DB_PASSWORD');

        $conn = new mysqli($this->host_name, $this->user_name, $this->password, $this->database, $this->port);

        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        $this->conn = $conn;
    }
}
