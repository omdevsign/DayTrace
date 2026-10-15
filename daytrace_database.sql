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



SELECT * FROM journal_entries;
UPDATE journal_entries SET created_at = '2026-09-17 15:30:00' WHERE journal_id = 14;
UPDATE journal_entries SET entry_date = '2026-09-20' WHERE journal_id = 17;
SELECT * FROM journal_tags;

SELECT * FROM sleep_goals;
SELECT * FROM sleep_logs;

SELECT * FROM habit_logs;
SELECT * FROM habits;

SELECT * FROM users;

INSERT INTO users VALUES (0, 'admin', 'admin@daytrace.com', 'admin123', 'Admin', 'Active', '2026-09-13 23:16:08');

DELETE FROM users WHERE user_id = 4;