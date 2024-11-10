<?php
declare(strict_types=1);
require_once 'Database.php';
require_once 'dto/UserDTO.php';

class UserRepository {
    private static ?UserRepository $instance = null;
    private PDO $db;

    private function __construct() {
        $this->db = (new Database())->getConnection(); // Инициализация соединения с БД
    }

    // Статический метод для получения экземпляра
    public static function getInstance(): ?UserRepository
    {
        if (self::$instance === null) {
            self::$instance = new UserRepository();
        }
        return self::$instance;
    }

    public function getUsers(): array
    {
        $query = $this->db->query('SELECT * FROM users');
        $users = $query->fetchAll(PDO::FETCH_ASSOC);
        $userDTOs = [];

        foreach ($users as $user) {
            $userDTOs[] = new UserDTO((int)$user['id'], $user['name'], $user['email']);
        }

        return $userDTOs;
    }
}
