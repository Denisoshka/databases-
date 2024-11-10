<?php
declare(strict_types=1);
// Подключаем классы UserRepository и GroupDTO
require_once 'php/UserRepository.php';

$userRepository = new UserRepository();
$groups = $userRepository->getAllGroups();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Список групп</title>
</head>
<body>
<h1>Список групп</h1>
<table border="1">
  <thead>
  <tr>
    <th>Номер группы</th>
    <th>Кол-во студентов</th>
    <th>Староста</th>
    <th>Действия</th>
  </tr>
  </thead>
  <tbody>
  <?php foreach ($groups as $group): ?>
    <tr>
      <td><?= htmlspecialchars($group->groupNumber) ?></td>
      <td><?= $group->studentCount ?></td>
      <td><?= htmlspecialchars($group->leader) ?></td>
      <td>
        <a href="edit_group.php?id=<?= $group->id ?>">Редактировать</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<h2>Добавить новую группу</h2>
<form action="add_group.php" method="POST">
  <label for="group_number">Номер группы:</label>
  <input type="text" name="group_number" id="group_number" required>
  <br>
  <label for="student_count">Кол-во студентов:</label>
  <input type="number" name="student_count" id="student_count" required>
  <br>
  <label for="leader">Староста:</label>
  <input type="text" name="leader" id="leader" required>
  <br>
  <button type="submit">Добавить</button>
</form>
</body>
</html>
