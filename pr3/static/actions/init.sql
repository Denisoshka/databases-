-- Таблица Teachers
CREATE TABLE IF NOT EXISTS teachers
(
    id             INT AUTO_INCREMENT PRIMARY KEY,
    full_name      VARCHAR(255) NOT NULL,
    position       VARCHAR(100) NOT NULL,
    main_workplace VARCHAR(255)
);

-- Таблица Phones для хранения одного или нескольких номеров для преподавателей
CREATE TABLE IF NOT EXISTS phones
(
    id           INT AUTO_INCREMENT PRIMARY KEY,
    teacher_id   INT         NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    FOREIGN KEY (teacher_id) REFERENCES teachers (id) ON DELETE CASCADE
);

-- Таблица Subjects
CREATE TABLE IF NOT EXISTS subjects
(
    id   INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
);

-- Таблица Groups
CREATE TABLE IF NOT EXISTS `groups`
(
    id            INT AUTO_INCREMENT PRIMARY KEY,
    group_number  VARCHAR(50) NOT NULL UNIQUE,
    student_count INT         NOT NULL CHECK ( student_count > 0 ),
    leader        VARCHAR(255)
);

-- Таблица Schedule
CREATE TABLE IF NOT EXISTS schedule
(
    id           INT AUTO_INCREMENT PRIMARY KEY,
    day_of_week  ENUM (
        'Monday', 'Tuesday', 'Wednesday', 'Thursday',
        'Friday', 'Saturday', 'Sunday'
        )            NOT NULL,
    class_number INT NOT NULL CHECK ( class_number BETWEEN 1 AND 9 ), -- Ограничение на количество пар
    group_id     INT NOT NULL,
    teacher_id   INT ,
    subject_id   INT NOT NULL,
    FOREIGN KEY (group_id) REFERENCES `groups` (id) ON DELETE CASCADE,
    FOREIGN KEY (teacher_id) REFERENCES teachers (id) ON DELETE SET NULL,
    FOREIGN KEY (subject_id) REFERENCES subjects (id) ON DELETE CASCADE
);
