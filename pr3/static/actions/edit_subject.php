<?php
declare(strict_types=1);

// Подключаем класс UserRepository
require_once '../php/UserRepository.php';

$userRepository = new UserRepository();
$id = $_GET['id'] ?? null;

if ($id) {
  $subject = $userRepository->getSubjectById((int)$id);

  if (!isset($subject)) {
    echo "Предмет не найден.";
    exit;
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];

  // Обновляем данные о предмете
  if ($userRepository->updateSubject((int)$id, $name)) {
    header('Location: ../subjects.php');
    exit;
  } else {
    echo "Ошибка при обновлении предмета.";
  }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Редактировать предмет</title>
</head>
<body>
<nav>
  <a href="../index.html">Go to main page</a>
  <a href="../subjects.php">go back</a>
</nav>
<h1>Редактирование предмета</h1>

<form action="edit_subject.php?id=<?= $subject->id ?>" method="POST">
  <label for="name">Название:</label>
  <input type="text" name="name" id="name"
         value="<?= htmlspecialchars($subject->name) ?>" required>
  <button type="submit">Сохранить изменения</button>
</form>
</body>
</html>