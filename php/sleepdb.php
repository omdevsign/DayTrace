<?php
require_once 'db.php';

function getSleepGoal($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT target_sleep_hours FROM sleep_goals WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['target_sleep_hours'] : 8.0;
}

function updateSleepGoal($pdo, $user_id, $target_hours) {
    $stmt = $pdo->prepare("INSERT INTO sleep_goals (user_id, target_sleep_hours) 
                           VALUES (?, ?) 
                           ON DUPLICATE KEY UPDATE target_sleep_hours = ?");
    return $stmt->execute([$user_id, $target_hours, $target_hours]);
}

function getSleepLogsByUser($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT * FROM sleep_logs WHERE user_id = ? ORDER BY log_date DESC");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addSleepLog($pdo, $user_id, $log_date, $hours_slept, $energy_rating) {
    $stmt = $pdo->prepare("INSERT INTO sleep_logs (user_id, log_date, hours_slept, energy_rating) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$user_id, $log_date, $hours_slept, $energy_rating]);
}

function deleteSleepLog($pdo, $sleep_log_id) {
    $stmt = $pdo->prepare("DELETE FROM sleep_logs WHERE sleep_log_id = ?");
    return $stmt->execute([$sleep_log_id]);
}
?>