DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS `groups`;

CREATE TABLE faculties
(
    id           VARCHAR(10) UNIQUE PRIMARY KEY,
    faculty_name VARCHAR(100) NOT NULL -- Название факультета
);
INSERT INTO faculties (id, faculty_name)
VALUES ('09.03.01', 'FIT'),
       ('09.04.01', 'FIT'),
       ('04.03.01', 'FEN'),
       ('04.05.01', 'FEN');


CREATE TABLE `groups`
(
    group_id   VARCHAR(10) UNIQUE PRIMARY KEY,
    faculty_id VARCHAR(10) NOT NULL,                   -- ID факультета, к которому относится группа
    FOREIGN KEY (faculty_id) REFERENCES faculties (id) -- Внешний ключ на факультеты
);
INSERT INTO `groups` (group_id, faculty_id)
VALUES ('24404.1', '04.03.01'),
       ('24403.1', '04.05.01'),
       ('22201', '09.03.01'),
       ('22213', '09.04.01');


CREATE TABLE students
(
    id          INT AUTO_INCREMENT PRIMARY KEY,
    full_name   VARCHAR(100) NOT NULL,                    -- Полное имя студента
    birth_place VARCHAR(100) NOT NULL,                    -- Место рождения
    birth_date  DATE         NOT NULL,                    -- Дата рождения
    phone       VARCHAR(15)  NOT NULL UNIQUE,             -- Телефон
    group_id    VARCHAR(10)  NOT NULL,                    -- ID группы, к которой принадлежит студент
    FOREIGN KEY (group_id) REFERENCES `groups` (group_id) -- Внешний ключ на таблицу групп
);
INSERT INTO students (full_name, birth_place, birth_date, phone, group_id)
VALUES ('Ivan Ivanov', 'Moskva',
        '2000-01-15', '89161234567', '22201'),
       ('Aleksey Vishnevski', 'Novosibirsk',
        '2005-04-22', '89261234568', '22201'),
       ('Irina Valova', 'Novosibirsk',
        '2005-01-19', '89361234569', '22201'),
       ('Aleksey Alekseyev', 'Kazan',
        '2000-04-05', '89462234570', '22213'),
       ('Maria Smirnova', 'Novosibirsk',
        '1999-05-30', '89562234571', '22213'),
       ('Petr Ivanov', 'Moskva',
        '2000-01-15', '89162234567', '22213'),
       ('Ivan Petrov', 'Sankt-Peterburg',
        '2000-02-20', '89263234568', '24404.1'),
       ('Svetlana Smirnova', 'Ekaterinburg',
        '1999-03-01', '89363234569', '24404.1'),
       ('Artem Alekseev', 'Kazan',
        '2000-04-06', '89463234570', '24404.1'),
       ('Maria Morozova', 'Novosibirsk',
        '1999-05-29', '89564234571', '24403.1'),
       ('Ivan Ivanov', 'Moskva',
        '2000-01-15', '89164234567', '24403.1'),
       ('Petr Petrov', 'Sankt-Peterburg',
        '2000-02-20', '89264234568', '24403.1');

CREATE TABLE elders
(
    id         INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    group_id   VARCHAR(10),
    FOREIGN KEY (group_id) REFERENCES `groups` (group_id),
    FOREIGN KEY (student_id) REFERENCES students (id)
);

INSERT INTO elders (student_id, group_id)
VALUES (3, '22201'),
       (5, '22213'),
       (9, '24404.1'),
       (11, '24403.1');

CREATE TABLE electives
(
    id            VARCHAR(10) UNIQUE PRIMARY KEY,
    elective_name VARCHAR(100) NOT NULL, -- Название факультатива
    description   TEXT,                  -- Описание факультатива
    credit_hours  INT          NOT NULL  -- Количество кредитных часов
);

INSERT INTO electives
VALUES ('00.00.01', 'SAMBO', 'borba', 240),
       ('00.00.02', 'CHESS', 'chess', 180),
       ('00.00.03', 'SWIM', 'swim', 180);

CREATE TABLE enrollments
(
    id              INT AUTO_INCREMENT PRIMARY KEY,
    student_id      INT         NOT NULL,               -- ID студента
    elective_id     VARCHAR(10) NOT NULL,               -- ID факультатива
    enrollment_date DATE        NOT NULL,               -- Дата зачисления
    FOREIGN KEY (student_id) REFERENCES students (id),  -- Внешний ключ на студентов
    FOREIGN KEY (elective_id) REFERENCES electives (id) -- Внешний ключ на факультативы
);
INSERT INTO enrollments (student_id, elective_id, enrollment_date)
VALUES (1, '00.00.01', '2022-09-01'),
       (2, '00.00.02', '2022-09-01'),
       (3, '00.00.01', '2022-09-01'),
       (5, '00.00.02', '2022-09-01'),
       (7, '00.00.03', '2022-09-01'),
       (8, '00.00.01', '2022-09-01'),
       (9, '00.00.02', '2022-09-01'),
       (10, '00.00.03', '2022-09-01'),
       (11, '00.00.03', '2022-09-01');

CREATE TABLE grades
(
    id          INT AUTO_INCREMENT PRIMARY KEY,
    student_id  INT           NOT NULL,                                         -- ID студента
    elective_id VARCHAR(10)   NOT NULL,                                         -- ID факультатива
    grade       DECIMAL(3, 2) NOT NULL CHECK (grade >= 2.00 AND grade <= 5.00), -- Оценка за курс
    FOREIGN KEY (student_id) REFERENCES students (id),                          -- Внешний ключ на студентов
    FOREIGN KEY (elective_id) REFERENCES electives (id)                         -- Внешний ключ на факультативы
);

INSERT INTO grades (student_id, elective_id, grade)
VALUES (1, '00.00.01', 4.45),
       (2, '00.00.02', 4.43),
       (3, '00.00.01', 4.0),
       (5, '00.00.02', 3.9),
       (7, '00.00.03', 4.89),
       (8, '00.00.01', 4.67),
       (9, '00.00.02', 4.0),
       (10, '00.00.03', 3.4),
       (11, '00.00.03', 3.8);
