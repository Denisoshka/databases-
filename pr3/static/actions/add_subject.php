<?php
// Подключаем класс UserRepository
require_once '../php/UserRepository.php';

$userRepository = new UserRepository();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];

  // Добавляем новый предмет
  if ($userRepository->addSubject($name)) {
    header('Location: subjects.php');
    exit;
  } else {
    echo "Ошибка при добавлении предмета.";
  }
}

