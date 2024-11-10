<?php
declare(strict_types=1);
require_once 'config/db_config.php';

class Database {
    private PDO $pdo;

    public function __construct() {
        try {
            $this->pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Could not connect to the database: " . $e->getMessage());
        }
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}
