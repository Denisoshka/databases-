<?php
declare(strict_types=1);

define("DB_HOST", getenv('DB_HOST'));
define("DB_NAME", getenv('DB_NAME'));
define("DB_USER", getenv('DB_USER'));
define("DB_PASS", getenv('DB_PASS'));

require_once "dto/TeacherDTO.php";
require_once "dto/SubjectDTO.php";
require_once "dto/DayOfWeek.php";
require_once "dto/GroupDTO.php";
require_once "dto/GroupWorkloadDTO.php";
require_once "dto/TeacherStudentLoadDTO.php";
require_once "dto/TeacherWorkloadDTO.php";

class UserRepository
{

  private static ?UserRepository $instance = null;
  private PDO $db;

  public function __construct()
  {
    try {
      $this->db = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
        DB_USER,
        DB_PASS
      );
    } catch (PDOException $e) {
      die("Could not connect to the database: " . $e->getMessage());
    }
  }

  public function resetDatabase(string $initSqlPath): void
  {
    try {
      $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // Удаление базы данных, если она существует
      $sql = 'DROP DATABASE IF EXISTS ' . DB_NAME;
      $this->db->exec($sql);
      echo 'Old database ' . DB_NAME . ' deleted successfully.<br>';

      $sql = "CREATE DATABASE " . DB_NAME;
      $this->db->exec($sql);
      echo 'New database ' . DB_NAME . ' created successfully.<br>';

      $this->db->exec("USE " . DB_NAME);

      $sql = file_get_contents($initSqlPath);
      if ($sql === false) {
        throw new Exception("Error reading init.sql file");
      }

      $this->db->exec($sql);
      echo 'Database ' . DB_NAME . ' restored successfully from init.sql.<br>';
    } catch (PDOException|Exception $e) {
      die("Error: " . $e->getMessage());
    }
  }

  /**
   * @return  TeacherDTO[]
   * */
  // Метод для получения всех преподавателей
  public function getAllTeachers(): array
  {
    $stmt = $this->db->prepare("
            SELECT id, full_name, position, main_workplace
            FROM teachers
        ");
    $stmt->execute();

    // Получаем данные как ассоциативный массив
    $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $teacherList = [];
    foreach ($teachers as $teacher) {
      $f = new TeacherDTO();
      $f->id = (int)$teacher['id'];
      $f->fullName = $teacher['full_name'];
      $f->position = $teacher['position'];
      $f->mainWorkplace = $teacher['main_workplace'];
      $teacherList[] = $f;
    }

    return $teacherList; // Возвращаем массив объектов
  }

  public function addTeacher(string $fullName, string $position, string
  $mainWorkplace): int
  {
    $stmt = $this->db->prepare("
        INSERT INTO teachers (full_name, position, main_workplace)
        VALUES (:full_name, :position, :main_workplace)
    ");
    $stmt->bindParam(':full_name', $fullName, PDO::PARAM_STR);
    $stmt->bindParam(':position', $position, PDO::PARAM_STR);
    $stmt->bindParam(':main_workplace', $mainWorkplace, PDO::PARAM_STR);
    $stmt->execute();
    return (int) $this->db->lastInsertId();
// Возвращаем результат выполнения запроса
  }

  public function updateTeacher(int $id, string $fullName, string $position, string $mainWorkplace): bool
  {
    $stmt = $this->db->prepare("
        UPDATE teachers
        SET full_name = :full_name,
            position = :position,
            main_workplace = :main_workplace
        WHERE id = :id
    ");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':full_name', $fullName, PDO::PARAM_STR);
    $stmt->bindParam(':position', $position, PDO::PARAM_STR);
    $stmt->bindParam(':main_workplace', $mainWorkplace, PDO::PARAM_STR);

    return $stmt->execute(); // Возвращаем результат выполнения запроса
  }

  public function getTeacherById(int $id): ?teacherDTO
  {
    $stmt = $this->db->prepare("
        SELECT t.id, t.full_name, t.position, t.main_workplace, p.phone_number
        FROM teachers t
        LEFT JOIN phones p ON p.teacher_id = t.id
        WHERE t.id = :id
    ");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Получаем все строки данных о преподавателе и его телефонах
    $teacherData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($teacherData)) {
      return null; // Если данных нет, возвращаем null
    }

    // Создаем объект TeacherDTO
    $teacher = new TeacherDTO();
    $teacher->id = (int)$teacherData[0]['id'];
    $teacher->fullName = $teacherData[0]['full_name'];
    $teacher->position = $teacherData[0]['position'];
    $teacher->mainWorkplace = $teacherData[0]['main_workplace'];

    $teacher->phones = [];

    // Добавляем телефоны к преподавателю
    foreach ($teacherData as $row) {
      if (!empty($row['phone_number'])) {
        $teacher->phones[] = $row['phone_number'];
      }
    }

    return $teacher;
  }

  /**
   * @return string[]
   */
  public function getTeacherPhones(int $teacherId): array
  {
    // Подготовка SQL-запроса для получения всех номеров телефонов преподавателя
    $stmt = $this->db->prepare("
        SELECT phone_number 
        FROM phones 
        WHERE teacher_id = :teacher_id
    ");
    // Привязка параметра :teacher_id к значению переменной $teacherId
    $stmt->bindParam(':teacher_id', $teacherId, PDO::PARAM_INT);
    $stmt->execute();

    // Возвращаем массив строк (телефоны) с помощью fetchAll() с FETCH_COLUMN
    return $stmt->fetchAll(PDO::FETCH_COLUMN); // Возвращаем только значения в виде массива строк
  }

  public function addTeacherPhone(int $teacherId, string $phoneNumber): bool
  {
    $stmt = $this->db->prepare("
        INSERT INTO phones (teacher_id, phone_number)
        VALUES (:teacher_id, :phone_number)
    ");
    $stmt->bindParam(':teacher_id', $teacherId, PDO::PARAM_INT);
    $stmt->bindParam(':phone_number', $phoneNumber, PDO::PARAM_STR);

    return $stmt->execute(); // Возвращаем результат выполнения запроса
  }

  public function deleteTeacherPhones(int $teacherId): bool
  {
    $stmt = $this->db->prepare("
        DELETE FROM phones
        WHERE teacher_id = :teacher_id
    ");
    $stmt->bindParam(':teacher_id', $teacherId, PDO::PARAM_INT);

    return $stmt->execute(); // Возвращаем результат выполнения запроса
  }
  public function deleteTeacher(int $teacherId): bool
  {
    // Удаляем все телефоны преподавателя
    $stmt = $this->db->prepare("DELETE FROM phones WHERE teacher_id = :teacher_id");
    $stmt->bindParam(':teacher_id', $teacherId, PDO::PARAM_INT);
    $stmt->execute();

    // Удаляем преподавателя из таблицы teachers
    $stmt = $this->db->prepare("DELETE FROM teachers WHERE id = :teacher_id");
    $stmt->bindParam(':teacher_id', $teacherId, PDO::PARAM_INT);

    return $stmt->execute(); // Возвращаем результат выполнения запроса
  }
  /**
   * @return SubjectDTO[]
   */
  // Метод для получения всех предметов
  public function getAllSubjects(): array
  {
    $stmt = $this->db->prepare("SELECT id, name FROM subjects");
    $stmt->execute();

    $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Создаем массив объектов SubjectDTO
    $subjectDTOs = [];
    foreach ($subjects as $subject) {
      $dto = new SubjectDTO();
      $dto->id = (int)$subject['id'];
      $dto->name = $subject['name'];
      $subjectDTOs[] = $dto;
    }

    return $subjectDTOs;
  }

  // Метод для получения предмета по ID
  public function getSubjectById(int $id): ?SubjectDTO
  {
    $stmt = $this->db->prepare("SELECT id, name FROM subjects WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $subject = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($subject) {
      // Возвращаем объект SubjectDTO
      $dto = new SubjectDTO();
      $dto->id = (int)$subject['id'];
      $dto->name = $subject['name'];
      return $dto;
    }

    return null;
  }

  // Добавление нового предмета
  public function addSubject(string $name): bool
  {
    $stmt = $this->db->prepare("INSERT INTO subjects (name) VALUES (:name)");
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    return $stmt->execute();
  }

  // Обновление предмета
  public function updateSubject(int $id, string $name): bool
  {
    $stmt = $this->db->prepare("UPDATE subjects SET name = :name WHERE id = :id");
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    return $stmt->execute();
  }

  /**
   * @return GroupDTO[]
   */
  public function getAllGroups(): array
  {
    $stmt = $this->db->prepare("SELECT id, group_number, student_count, leader FROM `groups`");
    $stmt->execute();

    $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Создаем массив объектов GroupDTO
    $groupDTOs = [];
    foreach ($groups as $group) {
      $dto = new GroupDTO();
      $dto->id = (int)$group['id'];
      $dto->groupNumber = $group['group_number'];
      $dto->studentCount = (int)$group['student_count'];
      $dto->leader = $group['leader'];
      $groupDTOs[] = $dto;
    }

    return $groupDTOs;
  }

  // Метод для получения группы по ID
  public function getGroupById(int $id): ?GroupDTO
  {
    $stmt = $this->db->prepare("SELECT id, group_number, student_count, leader FROM `groups` WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $group = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($group) {
      // Возвращаем объект GroupDTO
      $dto = new GroupDTO();
      $dto->id = (int)$group['id'];
      $dto->groupNumber = $group['group_number'];
      $dto->studentCount = (int)$group['student_count'];
      $dto->leader = $group['leader'];
      return $dto;
    }

    return null;
  }

  // Метод для добавления новой группы
  public function addGroup(string $groupNumber, int $studentCount, string $leader): bool
  {
    $stmt = $this->db->prepare("INSERT INTO `groups` (group_number, student_count, leader) VALUES (:group_number, :student_count, :leader)");
    $stmt->bindParam(':group_number', $groupNumber, PDO::PARAM_STR);
    $stmt->bindParam(':student_count', $studentCount, PDO::PARAM_INT);
    $stmt->bindParam(':leader', $leader, PDO::PARAM_STR);
    return $stmt->execute();
  }

  // Метод для обновления информации о группе
  public function updateGroup(int $id, string $groupNumber, int $studentCount, string $leader): bool
  {
    $stmt = $this->db->prepare("UPDATE `groups` SET group_number = :group_number, student_count = :student_count, leader = :leader WHERE id = :id");
    $stmt->bindParam(':group_number', $groupNumber, PDO::PARAM_STR);
    $stmt->bindParam(':student_count', $studentCount, PDO::PARAM_INT);
    $stmt->bindParam(':leader', $leader, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    return $stmt->execute();
  }

  /**
   * @return DayOfWeek[]
   */
  public function getWeekSchedule(): array
  {
    $stmt = $this->db->prepare("
            SELECT 
                s.id AS schedule_id,
                s.day_of_week,
                s.class_number,
                g.id AS group_id, g.group_number, g.student_count, g.leader,
                t.id AS teacher_id, t.full_name, t.position, t.main_workplace,
                subj.id AS subject_id, subj.name AS subject_name,
                p.phone_number
            FROM 
                schedule s
            JOIN 
                `groups` g ON s.group_id = g.id
            JOIN 
                teachers t ON s.teacher_id = t.id
            LEFT JOIN 
                phones p ON t.id = p.teacher_id
            JOIN 
                subjects subj ON s.subject_id = subj.id
            ORDER BY 
                s.day_of_week, s.class_number
        ");
    $stmt->execute();

    $schedules = [];
    $results = $stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_ASSOC);

    foreach ($results as $schedule_id => $rows) {
      $firstRow = $rows[0];

      $schedule = new DayOfWeek();

      $schedule->id = (int)$schedule_id;
      $schedule->dayOfWeek = $firstRow['day_of_week'];
      $schedule->classNumber = (int)$firstRow['class_number'];
      $schedule->group = $this->getGroupById((int)$firstRow['group_id']);
      $schedule->teacher = $this->getTeacherById((int)$firstRow['teacher_id']);
      $schedule->subject = $this->getSubjectById((int)$firstRow['subject_id']);

      $schedules[] = $schedule;
    }

    return $schedules;
  }

  // Метод для получения расписания по ID
  public function getDayScheduleById(int $id): ?DayOfWeek
  {
    $stmt = $this->db->prepare("
        SELECT 
            s.id AS schedule_id,
            s.day_of_week,
            s.class_number,
            g.id AS group_id, g.group_number, g.student_count, g.leader,
            t.id AS teacher_id, t.full_name, t.position, t.main_workplace,
            subj.id AS subject_id, subj.name AS subject_name,
            p.phone_number
        FROM 
            schedule s
        JOIN 
            `groups` g ON s.group_id = g.id
        JOIN 
            teachers t ON s.teacher_id = t.id
        LEFT JOIN 
            phones p ON t.id = p.teacher_id
        JOIN 
            subjects subj ON s.subject_id = subj.id
        WHERE 
            s.id = :id
        ORDER BY 
            s.day_of_week, s.class_number
    ");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($rows)) {
      return null; // No schedule found for the given ID
    }

    $firstRow = $rows[0];

    $schedule = new DayOfWeek();
    $schedule->id = (int) $firstRow['schedule_id'];
    $schedule->dayOfWeek = $firstRow['day_of_week'];
    $schedule->classNumber = (int)$firstRow['class_number'];
    $schedule->group = $this->getGroupById((int)$firstRow['group_id']);
    $schedule->teacher = $this->getTeacherById((int)$firstRow['teacher_id']);
    $schedule->subject = $this->getSubjectById((int)$firstRow['subject_id']);

    return $schedule;
  }

  // Метод для обновления расписания
  public function updateDaySchedule(int $id, string $dayOfWeek, int $classNumber, int $groupId, int $teacherId, int $subjectId): bool
  {
    $stmt = $this->db->prepare("
            UPDATE schedule 
            SET day_of_week = :day_of_week, class_number = :class_number, group_id = :group_id, teacher_id = :teacher_id, subject_id = :subject_id 
            WHERE id = :id
        ");
    $stmt->bindParam(':day_of_week', $dayOfWeek, PDO::PARAM_STR);
    $stmt->bindParam(':class_number', $classNumber, PDO::PARAM_INT);
    $stmt->bindParam(':group_id', $groupId, PDO::PARAM_INT);
    $stmt->bindParam(':teacher_id', $teacherId, PDO::PARAM_INT);
    $stmt->bindParam(':subject_id', $subjectId, PDO::PARAM_INT);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    return $stmt->execute();
  }

  /**
   * @return  TeacherWorkloadDTO[]
   * */
  public function getTeacherWorkload(): array
  {
    $stmt = $this->db->prepare("
        SELECT 
            t.id,
            t.full_name,
            COUNT(s.id) AS workload
        FROM 
            teachers t
        LEFT JOIN 
            schedule s ON s.teacher_id = t.id
        GROUP BY 
            t.id,t.full_name 
        ORDER BY 
            t.full_name
    ");
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $workloadDTOs = [];

    foreach ($result as $row) {
      $f = new TeacherWorkloadDTO();
      $f->id = (int)$row['id'];
      $f->fullName = $row['full_name'];
      $f->workload = (int)$row['workload'];
      $workloadDTOs[] = $f;
    }

    return $workloadDTOs;
  }

  /**
   * @return GroupWorkloadDTO[]
   * */
  public function getGroupWorkload(): array
  {
    $stmt = $this->db->prepare("
        SELECT 
            g.group_number,
            s.day_of_week,
            COUNT(s.id) AS class_count
        FROM 
            `groups` g
        JOIN 
            schedule s ON s.group_id = g.id
        GROUP BY 
            g.id, s.day_of_week, g.group_number
        HAVING COUNT(s.id) = (
            SELECT MIN(class_count)
            FROM (
                SELECT COUNT(s.id) AS class_count
                FROM schedule s
                WHERE s.group_id = g.id
                GROUP BY s.day_of_week
            ) AS subquery
        )
        ORDER BY g.group_number
    ");
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $workloadDTOs = [];

    foreach ($results as $row) {
      $dto = new GroupWorkloadDTO();
      $dto->groupNumber = $row['group_number'];
      $dto->dayWithLowestWorkload = $row['day_of_week'];
      $dto->classCount = (int)$row['class_count'];
      $workloadDTOs[] = $dto;
    }

    return $workloadDTOs;
  }

  /**
   * @return TeacherStudentLoadDTO[]
   * */
  public function getTeacherStudentCount(): array
  {
    $stmt = $this->db->prepare("
        SELECT 
            t.full_name AS teacher_name,
            SUM(g.student_count) AS student_count  -- Используем SUM для подсчета студентов
        FROM 
            teachers t
        JOIN 
            schedule s ON s.teacher_id = t.id
        JOIN 
            `groups` g ON s.group_id = g.id
        GROUP BY 
            t.id, t.full_name
        ORDER BY 
            t.full_name
    ");
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $teacherLoadDTOs = [];

    foreach ($results as $row) {
      $f = new TeacherStudentLoadDTO();
      $f->teacherName = $row['teacher_name'];
      $f->studentCount = (int)$row['student_count'];
      $teacherLoadDTOs[] = $f;
    }

    return $teacherLoadDTOs;
  }

  public function addSchedule(string $dayOfWeek, int $classNumber, int $groupId, int $teacherId, int $subjectId): bool
  {
    // Подготовка SQL запроса для вставки нового расписания
    $stmt = $this->db->prepare("
        INSERT INTO schedule (day_of_week, class_number, group_id, teacher_id, subject_id)
        VALUES (:day_of_week, :class_number, :group_id, :teacher_id, :subject_id)
    ");

    // Привязка параметров
    $stmt->bindParam(':day_of_week', $dayOfWeek, PDO::PARAM_STR);
    $stmt->bindParam(':class_number', $classNumber, PDO::PARAM_INT);
    $stmt->bindParam(':group_id', $groupId, PDO::PARAM_INT);
    $stmt->bindParam(':teacher_id', $teacherId, PDO::PARAM_INT);
    $stmt->bindParam(':subject_id', $subjectId, PDO::PARAM_INT);

    // Выполнение запроса
    return $stmt->execute();
  }

}