<?php
declare(strict_types=1);

// Подключаем классы UserRepository и DayOfWeek
require_once '../php/UserRepository.php';

$userRepository = new UserRepository();
$id = $_GET['id'] ?? null;

if ($id) {
  $daySchedule = $userRepository->getDayScheduleById($id);

  if (!$daySchedule) {
    echo "Расписание не найдено.";
    exit;
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $dayOfWeek = $_POST['day_of_week'];
  $classNumber = (int)$_POST['class_number'];
  $groupId = (int)$_POST['group_id'];
  $teacherId = (int)$_POST['teacher_id'];
  $subjectId = (int)$_POST['subject_id'];

  if ($userRepository->updateDaySchedule($id, $dayOfWeek, $classNumber, $groupId, $teacherId, $subjectId)) {
    header('Location: schedule.php');
    exit;
  } else {
    echo "Ошибка при обновлении расписания.";
  }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Редактирование расписания</title>
</head>
<body>
<h1>Редактирование расписания</h1>

<form action="edit_schedule.php?id=<?= $daySchedule->id ?>" method="POST">
  <label for="day_of_week">День недели:</label>
  <select name="day_of_week" id="day_of_week" required>
    <option
        value="Monday" <?= $daySchedule->dayOfWeek === 'Monday' ? 'selected' : '' ?>>
      Понедельник
    </option>
    <option
        value="Tuesday" <?= $daySchedule->dayOfWeek === 'Tuesday' ? 'selected' : '' ?>>
      Вторник
    </option>
    <option
        value="Wednesday" <?= $daySchedule->dayOfWeek === 'Wednesday' ? 'selected' : '' ?>>
      Среда
    </option>
    <option
        value="Thursday" <?= $daySchedule->dayOfWeek === 'Thursday' ? 'selected' : '' ?>>
      Четверг
    </option>
    <option
        value="Friday" <?= $daySchedule->dayOfWeek === 'Friday' ? 'selected' : '' ?>>
      Пятница
    </option>
    <option
        value="Saturday" <?= $daySchedule->dayOfWeek === 'Saturday' ? 'selected' : '' ?>>
      Суббота
    </option>
    <option
        value="Sunday" <?= $daySchedule->dayOfWeek === 'Sunday' ? 'selected' : '' ?>>
      Воскресенье
    </option>
  </select>
  <br>
  <label for="class_number">Номер пары:</label>
  <input type="number" name="class_number" id="class_number"
         value="<?= $daySchedule->classNumber ?>" required>
  <br>
  <label for="group_id">Группа:</label>
  <input type="number" name="group_id" id="group_id"
         value="<?= $daySchedule->groupId ?>" required>
  <br>
  <label for="teacher_id">Преподаватель:</label
  <input type="number" name="teacher_id" id="teacher_id"
         value="<?= $daySchedule->teacherId ?>" required>
  <br>
  <label for="subject_id">Предмет:</label>
  <input type="number" name="subject_id" id="subject_id"
         value="<?= $daySchedule->subjectId ?>" required>
  <br>
  <button type="submit">Сохранить изменения</button>
</form>
</body>
</html>