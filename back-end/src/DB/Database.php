<?php

namespace Driver\BackEnd\DB;

use Dotenv\Dotenv;

class Database
{
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    public function __construct()
    {
        // Carica le variabili d'ambiente
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../'); 
        $dotenv->load();

        // Assegna i valori delle variabili d'ambiente alle proprietà della classe
        $this->host = $_ENV['DB_HOST'];
        $this->db_name = $_ENV['DB_NAME'];
        $this->username = $_ENV['DB_USER'];
        $this->password = $_ENV['DB_PASSWORD'];
    }

    public function getConnection()
    {
        $this->conn = null;

        try {
            $this->conn = new \PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch (\PDOException $exception) {
            echo "Errore di connessione: " . $exception->getMessage();
        }

        return $this->conn;
    }
}

