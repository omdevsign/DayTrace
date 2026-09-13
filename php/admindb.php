<?php
require_once __DIR__ . '/db.php';

function getSystemStats($pdo) {
    $stats = [];
    
    $stmtUsers = $pdo->query("SELECT COUNT(*) FROM users");
    $stats['total_users'] = $stmtUsers->fetchColumn();

    $stmtLogs = $pdo->query("
        SELECT 
            (SELECT COUNT(*) FROM habit_logs) + 
            (SELECT COUNT(*) FROM sleep_logs) + 
            (SELECT COUNT(*) FROM journal_entries) AS total_logs
    ");
    $stats['total_logs'] = $stmtLogs->fetchColumn();

    $stmtActive = $pdo->query("SELECT COUNT(*) FROM users WHERE status = 'Active'");
    $stats['active_users'] = $stmtActive->fetchColumn();

    return $stats;
}

function getAllUsers($pdo) {
    $stmt = $pdo->query("SELECT user_id, username, email, role, status, DATE(created_at) AS created_date FROM users ORDER BY user_id ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function updateUserStatus($pdo, $user_id, $status) {
    $stmt = $pdo->prepare("UPDATE users SET status = :status WHERE user_id = :user_id");
    return $stmt->execute([':status' => $status, ':user_id' => $user_id]);
}

function deleteUser($pdo, $user_id) {
    try {
        $pdo->beginTransaction();

        $pdo->prepare("DELETE FROM habit_logs WHERE habit_id IN (SELECT habit_id FROM habits WHERE user_id = ?)")->execute([$user_id]);
        $pdo->prepare("DELETE FROM habits WHERE user_id = ?")->execute([$user_id]);

        $pdo->prepare("DELETE FROM sleep_logs WHERE user_id = ?")->execute([$user_id]);
        $pdo->prepare("DELETE FROM sleep_goals WHERE user_id = ?")->execute([$user_id]);

        $pdo->prepare("DELETE FROM journal_tags WHERE journal_id IN (SELECT journal_id FROM journal_entries WHERE user_id = ?)")->execute([$user_id]);
        $pdo->prepare("DELETE FROM journal_entries WHERE user_id = ?")->execute([$user_id]);

        $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $user_id]);

        $pdo->commit();
        return true;
        
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("Failed to delete user: " . $e->getMessage());
        return false;
    }
}
?>