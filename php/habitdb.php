<?php
require_once 'db.php';

function createHabit($pdo, $user_id, $habit_name, $target_frequency) {
    $stmt = $pdo->prepare("INSERT INTO habits (user_id, habit_name, target_frequency) VALUES (:user_id, :habit_name, :target_frequency)");
    $stmt->execute([
        ':user_id' => $user_id,
        ':habit_name' => $habit_name,
        ':target_frequency' => $target_frequency
    ]);
    return $pdo->lastInsertId();
}

function getHabitsByUser($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT * FROM habits WHERE user_id = :user_id AND is_active = TRUE");
    $stmt->execute([':user_id' => $user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function updateHabitStatus($pdo, $habit_id, $is_active) {
    $stmt = $pdo->prepare("UPDATE habits SET is_active = :is_active WHERE habit_id = :habit_id");
    $stmt->execute([
        ':is_active' => $is_active,
        ':habit_id' => $habit_id
    ]);
    return $stmt->rowCount();
}

function deleteHabit($pdo, $habit_id) {
    $stmt = $pdo->prepare("DELETE FROM habits WHERE habit_id = :habit_id");
    $stmt->execute([':habit_id' => $habit_id]);
    return $stmt->rowCount();
}

function isHabitLoggedToday($pdo, $habit_id, $log_date) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM habit_logs WHERE habit_id = :habit_id AND log_date = :log_date");
    $stmt->execute([
        ':habit_id' => $habit_id,
        ':log_date' => $log_date
    ]);
    return $stmt->fetchColumn() > 0;
}

function logHabitCompletion($pdo, $habit_id, $log_date) {
    if (isHabitLoggedToday($pdo, $habit_id, $log_date)) {
        return false; 
    }
    
    $stmt = $pdo->prepare("INSERT INTO habit_logs (habit_id, log_date, is_completed) VALUES (:habit_id, :log_date, TRUE)");
    $stmt->execute([
        ':habit_id' => $habit_id,
        ':log_date' => $log_date
    ]);
    return $pdo->lastInsertId();
}
?>