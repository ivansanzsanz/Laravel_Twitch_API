<?php

namespace App\Services;

use PDO;
use PDOException;

class DatabaseConnectionService
{
    private $host_name;
    private $database;
    private $port;
    private $user_name;
    private $password;
    private $dsn;
    protected $pdo;

    public function __construct()
    {
        $this->host_name = env('DB_HOST');
        $this->database = env('DB_DATABASE');
        $this->port = env('DB_PORT');
        $this->user_name = env('DB_USERNAME');
        $this->password = env('DB_PASSWORD');

        $this->dsn = "mysql:host=$this->host_name;" . "port=$this->port" . "dbname=$this->database";
        try {
            $this->pdo = new PDO($this->dsn, $this->user_name, $this->password);
            if (!$this->pdo) {
                die("Conexión fallida");
            }
        } catch (PDOException $exception) {
            die("Error de conexion: " . $exception->getMessage());
        }

        /*$conn = new mysqli($host_name, $user_name, $password, $database);

        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        return $conn;*/
    }
}
