<?php
declare(strict_types=1);

// Подключаем репозиторий
require_once '../php/UserRepository.php';

// Создаем экземпляр репозитория
$userRepository = new UserRepository();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Получаем данные из формы
  $dayOfWeek = $_POST['day_of_week'];
  $classNumber = (int)$_POST['class_number'];
  $groupId = (int)$_POST['group_id'];
  $teacherId = (int)$_POST['teacher_id'];
  $subjectId = (int)$_POST['subject_id'];

  // Добавляем новое расписание
  $success = $userRepository->addSchedule($dayOfWeek, $classNumber, $groupId, $teacherId, $subjectId);

  // Проверка на успешность добавления
  if ($success) {
    // Перенаправление на страницу с расписанием после успешного добавления
    header("Location: ../schedule.php");
    exit;
  } else {
    echo "Ошибка при добавлении расписания!";
  }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Добавить преподавателя</title>
</head>
<body>
<a href="../index.html">Go to main page</a>
<a href="../schedule.php">go back</a>
<h1>Добавить новое занятие</h1>

<form method="POST" action="add_schedule.php">
  <label for="day_of_week">День недели:</label>
  <select name="day_of_week" id="day_of_week" required>
    <option value="Monday">Понедельник</option>
    <option value="Tuesday">Вторник</option>
    <option value="Wednesday">Среда</option>
    <option value="Thursday">Четверг</option>
    <option value="Friday">Пятница</option>
    <option value="Saturday">Суббота</option>
    <option value="Sunday">Воскресенье</option>
  </select><br>

  <label for="class_number">Номер занятия:</label>
  <input type="number" name="class_number" id="class_number" min="1" max="9" required><br>

  <label for="group_id">Группа:</label>
  <select name="group_id" id="group_id" required>
    <!-- Здесь будет список групп, которые можно выбрать -->
    <?php
    // Запрос для получения списка групп
    $groups = $userRepository->getAllGroups();
    foreach ($groups as $group) {
      echo "<option value=\"{$group->id}\">{$group->groupNumber}</option>";
    }
    ?>
  </select><br>

  <label for="teacher_id">Преподаватель:</label>
  <select name="teacher_id" id="teacher_id" required>
    <!-- Здесь будет список преподавателей -->
    <?php
    // Запрос для получения списка преподавателей
    $teachers = $userRepository->getAllTeachers();
    foreach ($teachers as $teacher) {
      echo "<option value=\"{$teacher->id}\">{$teacher->fullName}</option>";
    }
    ?>
  </select><br>

  <label for="subject_id">Предмет:</label>
  <select name="subject_id" id="subject_id" required>
    <!-- Здесь будет список предметов -->
    <?php
    // Запрос для получения списка предметов
    $subjects = $userRepository->getAllSubjects();
    foreach ($subjects as $subject) {
      echo "<option value=\"{$subject->id}\">{$subject->name}</option>";
    }
    ?>
  </select><br>

  <button type="submit">Добавить в расписание</button>
</form>
<br>
</body>
</html>