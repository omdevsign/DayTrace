<?php
$host = 'localhost';
$port = 3306;
$username = 'root';
$password = 'root';
$dbname = 'daytrace_db';

try {
    $pdo = new PDO("mysql:host=$host;port=$port", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
    $pdo->exec("USE `$dbname`");

    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        user_id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        role VARCHAR(20) DEFAULT 'User',
        status VARCHAR(20) DEFAULT 'Active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS habits (
        habit_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        habit_name VARCHAR(100) NOT NULL,
        target_frequency VARCHAR(50),
        is_active BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS habit_logs (
        log_id INT AUTO_INCREMENT PRIMARY KEY,
        habit_id INT NOT NULL,
        log_date DATE NOT NULL,
        is_completed BOOLEAN DEFAULT FALSE,
        FOREIGN KEY (habit_id) REFERENCES habits(habit_id) ON DELETE CASCADE
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS sleep_goals (
        goal_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL UNIQUE,
        target_sleep_hours DECIMAL(3,1) DEFAULT 8.0,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS sleep_logs (
        sleep_log_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        log_date DATE NOT NULL,
        hours_slept DECIMAL(3,1) NOT NULL,
        energy_rating INT NOT NULL CHECK (energy_rating BETWEEN 1 AND 5),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS journal_entries (
        journal_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        entry_date DATE NOT NULL,
        daily_highlight TEXT,
        area_for_growth TEXT,
        gratitude_statement TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS journal_tags (
        tag_id INT AUTO_INCREMENT PRIMARY KEY,
        journal_id INT NOT NULL,
        tag_name VARCHAR(50) NOT NULL,
        FOREIGN KEY (journal_id) REFERENCES journal_entries(journal_id) ON DELETE CASCADE
    )");

} catch (PDOException $e) {
    die("Database Connection/Setup Failed: " . $e->getMessage());
}
?>