<?php
declare(strict_types=1);

// Подключаем репозиторий преподавателей
require_once '../php/UserRepository.php';

// Получаем ID преподавателя из URL
$teacherId = $_GET['id']; // Пример: ?id=1

// Создаем экземпляр репозитория
$teacherRepo = new UserRepository();

// Получаем информацию о преподавателе
$teacher = $teacherRepo->getTeacherById((int)$teacherId);

if (!$teacher) {
  echo "Преподаватель не найден!";
  exit;
}

// Обработка формы редактирования
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Если была нажата кнопка "Удалить преподавателя"
  if (isset($_POST['delete']) && $_POST['delete'] === '1') {
    // Удаляем преподавателя
    if ($teacherRepo->deleteTeacher((int)$teacherId)) {
      // Перенаправляем на страницу с преподавателями после удаления
      header("Location: ../teachers_list.php");
      exit;
    } else {
      echo "Ошибка при удалении преподавателя!";
    }
  }

  // Если форма обновления была отправлена
  $fullName = $_POST['full_name'];
  $position = $_POST['position'];
  $mainWorkplace = $_POST['main_workplace'];
  $phones = $_POST['phones']; // Телефоны для обновления

  // Обновляем преподавателя
  $teacherRepo->updateTeacher((int)$teacherId, $fullName, $position, $mainWorkplace);

  // Удаляем старые телефоны и добавляем новые
  $teacherRepo->deleteTeacherPhones((int)$teacherId); // Удаляем старые телефоны
  foreach ($phones as $phone) {
    $teacherRepo->addTeacherPhone((int)$teacherId, $phone);
  }

  // Перенаправление на страницу с преподавателями после обновления
  header("Location: ../teachers_list.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Редактировать преподавателя</title>
</head>
<body>
<nav>
  <a href="../index.html">Go to main page</a>
  <a href="../teachers.php">go back</a>

</nav>
<h1>Редактировать преподавателя</h1>

<form method="POST" action="edit_teacher.php?id=<?= $teacher->id ?>">
  <label for="full_name">Ф.И.О. преподавателя:</label>
  <input type="text" id="full_name" name="full_name"
         value="<?= htmlspecialchars($teacher->fullName) ?>" required><br>

  <label for="position">Должность:</label>
  <input type="text" id="position" name="position"
         value="<?= htmlspecialchars($teacher->position) ?>" required><br>

  <label for="main_workplace">Основное место работы:</label>
  <input type="text" id="main_workplace" name="main_workplace"
         value="<?= htmlspecialchars($teacher->mainWorkplace) ?>"><br>

  <label for="phones">Телефоны:</label><br>
  <?php
  // Получаем телефоны для преподавателя
  $phones = $teacherRepo->getTeacherPhones($teacher->id);
  foreach ($phones as $index => $phone) {
    echo '<input type="text" name="phones[]" value="' . htmlspecialchars
      ($phone) . '" placeholder="Телефон ' . ($index + 1) . '"><br>';
  }
  ?>
  <button type="submit">Обновить преподавателя</button>
</form>
<!-- Кнопка для удаления преподавателя -->
<form method="POST" action="edit_teacher.php?id=<?= $teacher->id ?>"
      onsubmit="return confirm('Вы уверены, что хотите удалить этого преподавателя?');">
  <button type="submit" name="delete" value="1">Удалить преподавателя</button>
</form>
<br>
<a href="../teachers.php">Назад к списку преподавателей</a>
</body>
</html>
