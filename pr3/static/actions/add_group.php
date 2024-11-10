<?php
// Подключаем класс UserRepository
require_once '../php/UserRepository.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $groupNumber = $_POST['group_number'];
  $studentCount = (int)$_POST['student_count'];
  $leader = $_POST['leader'];

  $userRepository = new UserRepository();

  if ($userRepository->addGroup($groupNumber, $studentCount, $leader)) {
    header('Location: ../groups.php');
    exit;
  } else {
    echo "Ошибка при добавлении группы.";
  }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Добавить группу</title>
</head
