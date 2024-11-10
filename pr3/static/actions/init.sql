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
    FOREIGN KEY (teacher_id) REFERENCES teachers (id) ON DELETE CASCADE ,
    FOREIGN KEY (subject_id) REFERENCES subjects (id) ON DELETE CASCADE
);
-- Inserting into the Teachers table
INSERT INTO teachers (full_name, position, main_workplace)
VALUES
    ('Ivanov Ivan Ivanovich', 'Professor', 'University 1'),
    ('Petrov Petr Petrovich', 'Associate Professor', 'University 2'),
    ('Sidorova Anna Sidorovna', 'Senior Lecturer', 'University 1'),
    ('Kuznetsova Maria Ivanovna', 'Lecturer', 'College 1'),
    ('Smirnov Andrei Andreevich', 'Professor', 'University 3'),
    ('Vasiliev Viktor Viktorovich', 'Assistant', 'College 2'),
    ('Nikolaeva Olga Sergeevna', 'Lecturer', 'University 2');

-- Inserting into the Phones table (multiple numbers per teacher)
INSERT INTO phones (teacher_id, phone_number)
VALUES
    (1, '+71234567890'), (1, '+79876543210'),
    (2, '+70001112233'), (2, '+71234567901'),
    (3, '+71234567890'), (4, '+79876543211'),
    (5, '+79871234567'), (6, '+70712345678'),
    (7, '+70123456789'), (7, '+79987654321');

-- Inserting into the Subjects table
INSERT INTO subjects (name)
VALUES
    ('Mathematics'),
    ('Physics'),
    ('Computer Science'),
    ('Chemistry'),
    ('Biology'),
    ('History'),
    ('Geography');

-- Inserting into the Groups table
INSERT INTO `groups` (group_number, student_count, leader)
VALUES
    ('101', 25, 'Andreev Alexey'),
    ('102', 30, 'Borisova Maria'),
    ('201', 28, 'Grigorieva Natalia'),
    ('202', 22, 'Dmitriev Oleg'),
    ('301', 26, 'Ivanov Pavel'),
    ('302', 23, 'Sokolov Sergey'),
    ('401', 20, 'Zhukova Elena');

-- Inserting into the Schedule table
INSERT INTO schedule (day_of_week, class_number, group_id, teacher_id, subject_id)
VALUES
    ('Monday', 1, 1, 1, 1),
    ('Monday', 2, 1, 2, 2),
    ('Monday', 3, 2, 3, 3),
    ('Tuesday', 1, 1, 4, 4),
    ('Wednesday', 2, 3, 5, 5),
    ('Thursday', 1, 2, 6, 6),
    ('Friday', 3, 3, 7, 7),
    ('Saturday', 2, 4, 1, 1),
    ('Sunday', 1, 5, 2, 2),
    ('Monday', 4, 6, 3, 3),
    ('Wednesday', 1, 7, 4, 4),
    ('Thursday', 2, 5, 5, 5),
    ('Friday', 4, 6, 6, 6),
    ('Saturday', 3, 7, 7, 7);
