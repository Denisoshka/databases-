<?php
declare(strict_types=1);

// Подключаем репозиторий преподавателей
require_once 'php/UserRepository.php';

// Получаем список всех преподавателей
$teacherRepo = new UserRepository();
$teachers = $teacherRepo->getAllTeachers(); // Метод для получения всех преподавателей

?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Список преподавателей</title>
</head>
<body>
<nav>
  <a href="index.html">Go to main page</a>
</nav>
<h1>Список преподавателей</h1>

<table border="1">
  <thead>
  <tr>
    <th>ID</th>
    <th>Ф.И.О. преподавателя</th>
    <th>Должность</th>
    <th>Основное место работы</th>
    <th>Телефоны</th>
    <th>Действия</th>
  </tr>
  </thead>
  <tbody>
  <?php foreach ($teachers as $teacher): ?>
    <tr>
      <td><a href="teacher_detail.php?id=<?= urlencode((string)$teacher->id) ?>"><?= htmlspecialchars((string)$teacher->id) ?></td>

      <td><?= htmlspecialchars($teacher->fullName) ?></td>
      <td><?= htmlspecialchars($teacher->position) ?></td>
      <td><?= htmlspecialchars($teacher->mainWorkplace) ?></td>
      <td>
        <?php
        // Получаем и выводим все номера телефонов для преподавателя
        $phones = $teacherRepo->getTeacherPhones($teacher->id);
        foreach ($phones as $phone) {
          echo htmlspecialchars($phone) . "<br>";
        }
        ?>
      </td>
      <td>
        <!-- Ссылка на редактирование преподавателя по ID -->
        <a href="actions/edit_teacher.php?id=<?= $teacher->id
        ?>">Редактировать</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<br>
<!-- Ссылка на форму добавления нового преподавателя -->
<a href="actions/add_teacher.php">Добавить нового преподавателя</a>
</body>
</html>

