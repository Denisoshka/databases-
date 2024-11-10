<?php
require_once 'php/UserRepository.php';
require_once 'php/dto/TeacherStudentLoadDTO.php';

$userRepository = new UserRepository();
$teachers = $userRepository->getTeacherStudentCount();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Нагрузка преподавателей</title>
</head>
<body>
<nav>
  <a href="index.html">Go to main page</a>
</nav>
<h1>Нагрузка преподавателей</h1>
<table border="1">
  <thead>
  <tr>
    <th>Ф.И.О. преподавателя</th>
    <th>Количество студентов</th>
  </tr>
  </thead>
  <tbody>
  <?php foreach ($teachers as $teacher): ?>
    <tr>
      <td><?= htmlspecialchars($teacher->teacherName) ?></td>
      <td><?= htmlspecialchars($teacher->studentCount) ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<nav>
  <a href="index.html">Go to main page</a>
</nav>
</body>
</html>
