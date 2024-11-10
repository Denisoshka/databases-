<?php
declare(strict_types=1);

// Подключаем классы UserRepository и DayOfWeek
require_once 'php/UserRepository.php';

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
<nav>
  <a href="index.html">Go to main page</a>
</nav>
<h1>Расписание на неделю</h1>
<table border="1">
  <thead>
  <tr>
    <th>День недели</th>
    <th>Номер пары</th>
    <th>Группа</th>
    <th>id Преп.</th>
    <th>Ф.И.О.</th>
    <th>Предмет</th>
    <th>Действия</th>
  </tr>
  </thead>
  <tbody>
  <?php foreach ($schedule as $day): ?>
    <tr>
      <td><?= htmlspecialchars($day->dayOfWeek) ?></td>
      <td><?= $day->classNumber ?></td>
      <td><?= $day->group->groupNumber ?></td>
      <td><?= $day->teacher->id ?></td>
      <td><?= $day->teacher->fullName ?></td>
      <td><?= $day->subject->name ?></td>
      <td>
        <a href="actions/edit_schedule.php?id=<?= $day->id ?>">Редактировать</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<a href="actions/add_schedule.php?>">Добавить занятие</a>
</body>
</html>
