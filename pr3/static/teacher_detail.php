<?php
require_once 'php/UserRepository.php';

$id = $_GET['id'] ?? null;
$userRepository = new UserRepository();

if ($id) {
  $teacher = $userRepository->getTeacherById((int)$id);
  if (!$teacher) {
    echo "Преподаватель не найден.";
    exit;
  }
} else {
  echo "ID преподавателя не указан.";
  exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Детали преподавателя</title>
</head>
<body>
<h1>Детали преподавателя</h1>
<p><strong>ID:</strong> <?= htmlspecialchars($teacher->id) ?></p>
<p><strong>Ф.И.О.:</strong> <?= htmlspecialchars($teacher->fullName) ?></p>
<p><strong>Должность:</strong> <?= htmlspecialchars($teacher->position) ?></p>
<p><strong>Основное место
    работы:</strong> <?= htmlspecialchars($teacher->mainWorkplace) ?></p>
<p><strong>Телефоны:</strong></p>
<ul>
  <?php foreach ($teacher->phones as $phone): ?>
    <li><?= htmlspecialchars($phone) ?></li>
  <?php endforeach; ?>
</ul>
<p>
  <a href="actions/edit_teacher.php?id=<?= $teacher->id
  ?>">Редактировать</a>
</p>
<a href="teachers.php">Назад к списку преподавателей</a>
</body>
</html>
