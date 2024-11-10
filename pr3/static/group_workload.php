<?php
require_once 'php/UserRepository.php';
require_once 'php/dto/GroupWorkloadDTO.php';

$userRepository = new UserRepository();
$groups = $userRepository->getGroupWorkload();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Нагрузка по группам</title>
</head>
<body>
<nav>
  <a href="index.html">Go to main page</a>
</nav>
<h1>Нагрузка по группам</h1>
<table border="1">
  <thead>
  <tr>
    <th>Номер группы</th>
    <th>День с наименьшей нагрузкой</th>
    <th>Количество пар</th>
  </tr>
  </thead>
  <tbody>
  <?php foreach ($groups as $group): ?>
    <tr>
      <td><?= htmlspecialchars($group->groupNumber) ?></td>
      <td><?= htmlspecialchars($group->dayWithLowestWorkload) ?></td>
      <td><?= htmlspecialchars($group->classCount) ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
</body>
</html>
