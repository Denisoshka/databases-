<?php
require_once 'php/UserRepository.php';
require_once 'php/dto/TeacherWorkloadDTO.php';

$userRepository = new UserRepository();
$teachers = $userRepository->getTeacherWorkload();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Нагрузка преподавателей</title>
</head>
<body>
<h1>Нагрузка преподавателей</h1>
<table border="1">
  <thead>
  <tr>
    <th>ID</th>
    <th>Ф.И.О. преподавателя</th>
    <th>Нагрузка (часы)</th>
    <th>Действия</th>
  </tr>
  </thead>
  <tbody>
  <?php foreach ($teachers as $teacher): ?>
    <tr>
      <td><?= htmlspecialchars($teacher->id) ?></td>
      <td><?= htmlspecialchars($teacher->fullName) ?></td>
      <td><?= htmlspecialchars($teacher->workload) ?></td>
      <td>
        <a href="teacher_detail.php?id=<?= $teacher->id ?>">Просмотреть</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
</body>
</html>
