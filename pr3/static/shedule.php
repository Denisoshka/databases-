<?php
// Подключаем классы UserRepository и DayOfWeek
require_once 'UserRepository.php';

$userRepository = new UserRepository();
$schedule = $userRepository->getWeekSchedule();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Расписание на неделю</title>
</head>
<body>
<h1>Расписание на неделю</h1>
<table border="1">
  <thead>
  <tr>
    <th>День недели</th>
    <th>Номер пары</th>
    <th>Группа</th>
    <th>Преподаватель</th>
    <th>Предмет</th>
    <th>Действия</th>
  </tr>
  </thead>
  <tbody>
  <?php foreach ($schedule as $day): ?>
    <tr>
      <td><?= htmlspecialchars($day->dayOfWeek) ?></td>
      <td><?= $day->classNumber ?></td>
      <td><?= $day->groupId ?></td>
      <td><?= $day->teacherId ?></td>
      <td><?= $day->subjectId ?></td>
      <td>
        <a href="edit_schedule.php?id=<?= $day->id ?>">Редактировать</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
</body>
</html>
