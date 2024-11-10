<?php
declare(strict_types=1);

// Подключаем репозиторий
require_once 'php/UserRepository.php';

// Создаем экземпляр репозитория
$teacherRepo = new UserRepository();

// Получаем список преподавателей с их нагрузкой
$teachersWorkload = $teacherRepo->getTeacherWorkload();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Нагрузка преподавателей за неделю</title>
</head>
<body>
<nav>
  <a href="index.html">Go to main page</a>
</nav>
<h1>Нагрузка преподавателей за неделю</h1>

<table border="1">
  <thead>
  <tr>
    <th>Ф.И.О. преподавателя</th>
    <th>Нагрузка (в часах)</th>
  </tr>
  </thead>
  <tbody>
  <?php
  if (empty($teachersWorkload)) {
    echo '<tr><td colspan="2">Нет данных</td></tr>';
  } else {
    foreach ($teachersWorkload as $teacher) {
      echo '<tr>';
      echo '<td>' . htmlspecialchars($teacher->fullName) .
        '</td>';
      echo '<td>' . htmlspecialchars((string)$teacher->workload) . ' часов</td>';
      echo '</tr>';
    }
  }
  ?>
  </tbody>
</table>

</body>
</html>
