CREATE DATABASE IF NOT EXISTS daytrace_db;

USE daytrace_db;

CREATE TABLE IF NOT EXISTS users (
        user_id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        role VARCHAR(20) DEFAULT 'User',
        status VARCHAR(20) DEFAULT 'Active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );


CREATE TABLE IF NOT EXISTS habits (
        habit_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        habit_name VARCHAR(100) NOT NULL,
        target_frequency VARCHAR(50),
        is_active BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
    );

CREATE TABLE IF NOT EXISTS habit_logs (
        log_id INT AUTO_INCREMENT PRIMARY KEY,
        habit_id INT NOT NULL,
        log_date DATE NOT NULL,
        is_completed BOOLEAN DEFAULT FALSE,
        FOREIGN KEY (habit_id) REFERENCES habits(habit_id) ON DELETE CASCADE
    );

CREATE TABLE IF NOT EXISTS sleep_goals (
        goal_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL UNIQUE,
        target_sleep_hours DECIMAL(3,1) DEFAULT 8.0,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
    );

CREATE TABLE IF NOT EXISTS sleep_logs (
        sleep_log_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        log_date DATE NOT NULL,
        hours_slept DECIMAL(3,1) NOT NULL,
        energy_rating INT NOT NULL CHECK (energy_rating BETWEEN 1 AND 5),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
    );
    
CREATE TABLE IF NOT EXISTS journal_entries (
        journal_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        entry_date DATE NOT NULL,
        daily_highlight TEXT,
        area_for_growth TEXT,
        gratitude_statement TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
    );

CREATE TABLE IF NOT EXISTS journal_tags (
        tag_id INT AUTO_INCREMENT PRIMARY KEY,
        journal_id INT NOT NULL,
        tag_name VARCHAR(50) NOT NULL,
        FOREIGN KEY (journal_id) REFERENCES journal_entries(journal_id) ON DELETE CASCADE
    );
    
SELECT * FROM journal_tags;
SELECT * FROM journal_entries;
SELECT * FROM sleep_goals;
SELECT * FROM sleep_logs;
SELECT * FROM habit_logs;
SELECT * FROM habits;
SELECT * FROM users;

-- INSERTION AND DISPLAY COMMANDS

INSERT INTO habits
(habit_id, user_id, habit_name, target_frequency, is_active, created_at)
VALUES
(1, 1, 'Study', 'Daily', 1, '2026-10-15 19:00:13'),
(2, 10, 'Read', 'Daily', 1, '2026-10-22 20:53:22'),
(3, 10, 'Study', 'Daily', 1, '2026-10-22 20:53:35'),
(4, 2, 'Practice coding', 'Weekly', 1, '2026-10-22 20:54:17'),
(5, 2, 'Read newspaper', 'Daily', 1, '2026-10-22 20:54:45'),
(6, 7, 'Study IOT', 'Daily', 1, '2026-10-22 20:55:35'),
(7, 7, 'Attend workshop', 'Weekly', 1, '2026-10-22 20:55:48'),
(8, 9, 'Read', 'Daily', 1, '2026-10-22 21:37:58'),
(9, 9, 'Exercise', 'Daily', 1, '2026-10-22 21:38:03'),
(10, 9, 'Cook', 'Weekly', 1, '2026-10-22 21:38:16');


INSERT INTO habit_logs
(log_id, habit_id, log_date, is_completed)
VALUES
(1, 1, '2026-10-15', 1),
(2, 6, '2026-10-22', 1),
(3, 7, '2026-10-22', 1),
(4, 3, '2026-10-22', 1),
(5, 1, '2026-10-22', 1),
(6, 8, '2026-10-22', 1),
(7, 2, '2026-10-20', 1),
(8, 4, '2026-10-21', 0),
(9, 5, '2026-10-21', 1),
(10, 9, '2026-10-22', 0);



INSERT INTO sleep_goals
(goal_id, user_id, target_sleep_hours, updated_at)
VALUES
(1, 1, 9.0, '2026-10-22 21:52:01'),
(2, 7, 8.0, '2026-10-22 21:11:43'),
(3, 2, 8.0, '2026-10-22 21:12:04'),
(4, 4, 8.0, '2026-10-22 21:13:15'),
(5, 9, 8.5, '2026-10-22 21:54:33'),
(7, 8, 8.0, '2026-10-22 21:52:35'),
(8, 5, 10.0, '2026-10-22 21:52:57'),
(10, 10, 7.0, '2026-10-22 21:53:50'),
(12, 3, 9.0, '2026-10-22 21:55:09'),
(13, 6, 10.0, '2026-10-22 21:55:45');

INSERT INTO sleep_logs
(sleep_log_id, user_id, log_date, hours_slept, energy_rating, created_at)
VALUES
(1, 1, '2026-10-15', 3.0, 3, '2026-10-15 19:01:06'),
(2, 1, '2026-10-19', 8.0, 4, '2026-10-22 21:04:44'),
(3, 1, '2026-10-20', 10.0, 5, '2026-10-22 21:05:10'),
(4, 1, '2026-10-21', 10.0, 3, '2026-10-22 21:05:27'),
(5, 1, '2026-10-22', 7.0, 2, '2026-10-22 21:05:38'),
(6, 2, '2026-10-21', 8.0, 4, '2026-10-22 21:06:26'),
(7, 2, '2026-10-22', 6.0, 4, '2026-10-22 21:06:35'),
(8, 4, '2026-10-22', 8.0, 3, '2026-10-22 21:06:59'),
(9, 7, '2026-10-21', 3.0, 1, '2026-10-22 21:07:28'),
(10, 7, '2026-10-22', 10.0, 5, '2026-10-22 21:07:35'),
(11, 9, '2026-10-19', 7.0, 4, '2026-10-22 21:42:46'),
(12, 9, '2026-10-20', 9.0, 5, '2026-10-22 21:42:55'),
(13, 9, '2026-10-21', 6.0, 4, '2026-10-22 21:43:03'),
(14, 9, '2026-10-22', 4.0, 1, '2026-10-22 21:43:13');


INSERT INTO journal_entries
(journal_id, user_id, entry_date, daily_highlight,
 area_for_growth, gratitude_statement, created_at)
VALUES
(2, 1, '2026-10-19', 'Study session','Proper time management', 'Productive', '2026-10-22 21:20:15'),
(3, 2, '2026-10-21', 'Finish DBMS project','Time management', 'Productive', '2026-10-22 21:21:18'),
(4, 5, '2026-10-14', 'Complete science lab exam','Scientific names', 'Good weather', '2026-10-22 21:22:43'),
(5, 9, '2026-10-16', 'Finalized research topic for biotechnology','Need to research more on DNA', 'Good morning routine',
 '2026-10-22 21:24:23'),
(6, 9, '2026-10-21', 'Completed phase one of project','Understand topic better', 'None', '2026-10-22 21:25:09'),
(7, 9, '2026-10-01', 'Good workout','Cardio', 'Good day', '2026-10-22 21:25:41'),
(8, 1, '2026-10-22', 'Cook dinner','Time', 'Good food', '2026-10-22 21:47:22'),
(9, 2, '2026-10-15', 'Submit assignment','Speed', 'None', '2026-10-22 21:48:12'),
(10, 2, '2026-10-17', 'Drive car for 30 mins','Patience', 'None', '2026-10-22 21:48:53'),
(11, 2, '2026-10-05', 'Complete M2 exam','Study better', 'None', '2026-10-22 21:49:33');


INSERT INTO journal_tags
(tag_id, journal_id, tag_name)
VALUES
(4, 4, 'Productive'),
(5, 5, 'Productive'),
(6, 6, 'Productive'),
(7, 7, 'Exercise'),
(8, 8, 'Cooking'),
(9, 9, 'Productive'),
(10, 10, 'Productive'),
(11, 11, 'Study');



INSERT INTO users
(user_id, username, email, password_hash, role, status, created_at)
VALUES
(1, 'Om Gohil', 'om@gmail.com', '$2y$10$x6rd48Mq9XnXWq5278VVGeuNERaZVxU.hdzF5wmgue0ueOkyWcPca', 'User', 'Active', '2026-10-15 18:59:50'),
(2, 'Brinda Das', 'brinda@gmail.com', '$2y$10$rqaf1PhjFFRlR9fG2WYKkeYAr5bIbhu9pyUjEpHJz3GYNIoi1Jzre', 'User', 'Active', '2026-10-22 20:36:27'),
(3, 'Tom', 'tom@gmail.com', '$2y$10$NpsOGd3Z7KxH9IO3XOtWRuy/oL7xTDmG7QmJjOFZoP6sCqGDXkLbq', 'User', 'Active', '2026-10-22 20:37:41'),
(4, 'Peter', 'peter@gmail.com', '$2y$10$fMhJGxLFEEZQmlcBbx4oU.0DHWMsCSbUiUT/25pHx6SWqDP58J4Yu', 'User', 'Active', '2026-10-22 20:38:49'),
(5, 'Gwen', 'gwen@gmail.com', '$2y$10$BH1y1jLrCy.Qv1XofQSLN.G7WYPgO9TRu1PIIfwtwQZy8Oc4f2rka', 'User', 'Active', '2026-10-22 20:39:52'),
(6, 'Jules', 'jules@gmail.com', ' $2y$10$tkKLPduGjzB2S078Xra.LO5nECkgqt/MU8HSkUFj2LNquRovdJhaq', 'User', 'Active', '2026-10-22 20:46:17'),
(7, 'Tony', 'tony@gmail.com', '$2y$10$Kqv3GZhB4AlWf3HxMA8st.qN43cGYSy7pTtDwl1scz2B2zHs1eYnG', 'User', 'Active', '2026-10-22 20:46:47'),
(8, 'Tim', 'tim@gmail.com', '$2y$10$1k8I6od9UQ4I4tBFIntU/.biA3Qqvzy0uw1kIfii8q.yxJDEh/xn6', 'User', 'Active', '2026-10-22 20:47:34'),
(9, 'Bruce', 'bruce@gmail.com', '$2y$10$Qv7sv7Q17tDSx9uXJk2IO.Jv0VE3K0G3qOW7fIwN1oZSIwv4dqvg6', 'User', 'Active', '2026-10-22 20:48:20'),
(10, 'Nat', 'nat@gmail.com', '$2y$10$m8aBL805Zy3sXj5MAdTq2OsZk2/XjbqFzSZEQss0frwb7yXAVH3Tq', 'User', 'Active', '2026-10-22 20:49:11');

-- MYSQL QUERIES COMMANDS
-- 1
SELECT user_id, username, email, role, status, created_at FROM users;

-- 2
SELECT username, email FROM users WHERE status = 'Active';

-- 3
SELECT username, email FROM users WHERE username LIKE 't%';

-- 4
SELECT * FROM habits WHERE is_active = TRUE;

-- 5
SELECT u.username, h.habit_name, h.target_frequency FROM users u INNER JOIN 
habits h ON u.user_id = h.user_id;

-- 6
SELECT h.habit_name, hl.log_date, hl.is_completed FROM habits h LEFT JOIN 
habit_logs hl ON h.habit_id = hl.habit_id;

-- 7
SELECT * FROM habit_logs WHERE is_completed = TRUE;

-- 8
SELECT h.habit_name, COUNT(hl.log_id) AS total_logs FROM habits h LEFT JOIN 
habit_logs hl ON h.habit_id = hl.habit_id GROUP BY h.habit_id, h.habit_name;

-- 9
SELECT user_id, log_date, hours_slept FROM sleep_logs WHERE hours_slept > 7 
ORDER BY hours_slept DESC;

-- 10
SELECT * FROM sleep_logs WHERE energy_rating BETWEEN 3 AND 5;

-- 11
SELECT AVG(hours_slept) AS average_sleep FROM sleep_logs;

-- 12
SELECT MIN(hours_slept) AS minimum_sleep, MAX(hours_slept) AS 
maximum_sleep FROM sleep_logs;

-- 13
SELECT u.username, sg.target_sleep_hours FROM users u LEFT JOIN 
sleep_goals sg ON u.user_id = sg.user_id;

-- 14
SELECT u.username, sl.log_date, sl.hours_slept, sl.energy_rating FROM 
users u INNER JOIN sleep_logs sl ON u.user_id = sl.user_id;

-- 15
SELECT user_id, entry_date, daily_highlight FROM journal_entries ORDER BY 
entry_date DESC;

-- 16
SELECT je.journal_id, je.entry_date, je.daily_highlight, jt.tag_name FROM 
journal_entries je LEFT JOIN journal_tags jt ON je.journal_id = jt.journal_id;

-- 17
SELECT user_id, COUNT(*) AS total_entries FROM journal_entries GROUP BY user_id;

-- 18
SELECT user_id, log_date, hours_slept FROM sleep_logs WHERE 
hours_slept > (SELECT AVG(hours_slept) FROM sleep_logs);

-- 19
UPDATE sleep_goals SET target_sleep_hours = 9 WHERE user_id = 1;
Select * from sleep_goals;

-- 20
DELETE FROM journal_tags WHERE tag_id = 3;
SELECT * FROM journal_tags;

-- 21
SELECT username, email, created_at FROM users ORDER BY created_at DESC;

-- 22
SELECT MAX(energy_rating) AS highest_energy FROM sleep_logs;

INSERT INTO users VALUES (0, 'admin', 'admin@daytrace.com', 'admin123', 'Admin', 'Active', '2026-09-13 23:16:08');
UPDATE users SET user_id = 100 WHERE user_id = 1;
delete from users where user_id=100;

UPDATE journal_entries SET created_at = '2026-09-17 15:30:00' WHERE journal_id = 14;
UPDATE journal_entries SET entry_date = '2026-09-20' WHERE journal_id = 17;