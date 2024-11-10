<?php
declare(strict_types=1);

// Подключаем класс UserRepository и SubjectDTO
require_once 'php/UserRepository.php';

$userRepository = new UserRepository();
$subjects = $userRepository->getAllSubjects();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Предметы</title>
</head>
<body>
<nav>
  <a href="index.html">Go to main page</a>
</nav>
<h1>Список предметов</h1>
<table border="1">
  <thead>
  <tr>
    <th>Название</th>
    <th>Действия</th>
  </tr>
  </thead>
  <tbody>
  <?php foreach ($subjects as $subject): ?>
    <tr>
      <td><?= htmlspecialchars($subject->name) ?></td>
      <td>
        <a href="actions/edit_subject.php?id=<?= $subject->id
        ?>">Редактировать</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<h2>Добавить новый предмет</h2>
<form action="actions/add_subject.php" method="POST">
  <label for="name">Название:</label>
  <input type="text" name="name" id="name" required>
  <button type="submit">Добавить</button>
</form>
</body>
</html>
