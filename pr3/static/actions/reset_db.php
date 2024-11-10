<?php
declare(strict_types=1);
require_once "../php/UserRepository.php"; // Подключение к БД

try {
  $db = new UserRepository();
  // Файл init.sql, который содержит SQL-запросы
  $sqlFile = 'init.sql'; // Убедитесь, что путь к init.sql правильный
  $db->resetDatabase($sqlFile);
  echo "База данных была сброшена и заполнена начальными данными.";
} catch (PDOException $e) {
  echo "Ошибка подключения к базе данных: " . $e->getMessage();
  exit;
} catch (Exception $e) {
  echo "Ошибка: " . $e->getMessage();
  exit;
}

