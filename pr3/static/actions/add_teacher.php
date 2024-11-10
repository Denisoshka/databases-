<?php
declare(strict_types=1);

// Подключаем репозиторий преподавателей
require_once '../php/UserRepository.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Получаем данные из формы
  $fullName = $_POST['full_name'];
  $position = $_POST['position'];
  $mainWorkplace = $_POST['main_workplace'];
  $phones = $_POST['phones']; // Список телефонов

  // Создаем экземпляр репозитория
  $teacherRepo = new UserRepository();

  // Добавляем преподавателя
  $teacherId = $teacherRepo->addTeacher($fullName, $position, $mainWorkplace);

  // Добавляем телефоны
  foreach ($phones as $phone) {
    $teacherRepo->addTeacherPhone((int)$teacherId, $phone);
  }

  // Перенаправление на страницу с преподавателями после добавления
  header("Location: teachers_list.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Добавить преподавателя</title>
</head>
<body>
<h1>Добавить нового преподавателя</h1>

<form method="POST" action="add_teacher.php">
  <label for="full_name">Ф.И.О. преподавателя:</label>
  <input type="text" id="full_name" name="full_name" required><br>

  <label for="position">Должность:</label>
  <input type="text" id="position" name="position" required><br>

  <label for="main_workplace">Основное место работы:</label>
  <input type="text" id="main_workplace" name="main_workplace"><br>

  <label for="phones">Телефоны:</label><br>
  <label>
    <input type="text" name="phones[]" placeholder="Телефон 1"><br>
    <input type="text" name="phones[]" placeholder="Телефон 2">
    <input type="text" name="phones[]" placeholder="Телефон 3"><br>
  </label><br>

  <button type="submit">Добавить преподавателя</button>
</form>

<br>
<a href="../teachers_list.php">Назад к списку преподавателей</a>
</body>
</html>
