-- db_setup.sql
/*CREATE DATABASE IF NOT EXISTS testdb;
USE testdb;*/

CREATE TABLE IF NOT EXISTS users (
                                     id INT AUTO_INCREMENT PRIMARY KEY,
                                     name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE
    );

INSERT INTO users (name, email) VALUES
                                    ('John Doe', 'john@example.com'),
                                    ('Jane Smith', 'jane@example.com'),
                                    ('Sam Black', 'sam@example.com');
