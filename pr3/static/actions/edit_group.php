<?php
declare(strict_types=1);

// Подключаем классы UserRepository и GroupDTO
require_once '../php/UserRepository.php';

$userRepository = new UserRepository();
$id = $_GET['id'] ?? null;

if ($id) {
  $group = $userRepository->getGroupById((int)$id);

  if (!$group) {
    echo "Группа не найдена.";
    exit;
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $groupNumber = $_POST['group_number'];
  $studentCount = (int)$_POST['student_count'];
  $leader = $_POST['leader'];

  // Обновляем данные о группе
  if ($userRepository->updateGroup((int)$id, $groupNumber, $studentCount,
    $leader)) {
    header('Location: ../groups.php');
    exit;
  } else {
    echo "Ошибка при обновлении группы.";
  }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Редактировать группу</title>
</head>
<body>
<h1>Редактирование группы</h1>

<form action="edit_group.php?id=<?= $group->id ?>" method="POST">
  <label for="group_number">Номер группы:</label>
  <input type="text" name="group_number" id="group_number"
         value="<?= htmlspecialchars($group->groupNumber) ?>" required>
  <br>
  <label for="student_count">Кол-во студентов:</label>
  <input type="number" name="student_count" id="student_count"
         value="<?= $group->studentCount ?>" required>
  <br>
  <label for="leader">Староста:</label>
  <input type="text" name="leader" id="leader"
         value="<?= htmlspecialchars($group->leader) ?>" required>
  <br>
  <button type="submit">Сохранить изменения</button>
</form>
</body>
</html>
