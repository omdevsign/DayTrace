<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$user_id = $_SESSION['user_id'];

$habitStmt = $pdo->prepare("
    SELECT 
        SUM(CASE WHEN hl.is_completed = 1 THEN 1 ELSE 0 END) AS completed,
        SUM(CASE WHEN hl.is_completed = 0 OR hl.log_id IS NULL THEN 1 ELSE 0 END) AS missed
    FROM habits h
    LEFT JOIN habit_logs hl ON h.habit_id = hl.habit_id
    WHERE h.user_id = :user_id AND h.is_active = TRUE
");
$habitStmt->execute([':user_id' => $user_id]);
$habitStats = $habitStmt->fetch(PDO::FETCH_ASSOC);

$completedHabits = (int)($habitStats['completed'] ?? 0);
$missedHabits = (int)($habitStats['missed'] ?? 0);
if ($completedHabits === 0 && $missedHabits === 0) {
    $completedHabits = 1;
}

$sleepStmt = $pdo->prepare("
    SELECT log_date, hours_slept, energy_rating 
    FROM sleep_logs 
    WHERE user_id = :user_id 
    ORDER BY log_date DESC 
    LIMIT 7
");
$sleepStmt->execute([':user_id' => $user_id]);
$sleepRows = array_reverse($sleepStmt->fetchAll(PDO::FETCH_ASSOC));

$sleepLabels = [];
$sleepHours = [];
$sleepEnergy = [];

foreach ($sleepRows as $row) {
    $sleepLabels[] = date('D', strtotime($row['log_date']));
    $sleepHours[] = (float)$row['hours_slept'];
    $sleepEnergy[] = (int)$row['energy_rating'] * 2;
}

$journalStmt = $pdo->prepare("
    SELECT COUNT(*) AS total_entries 
    FROM journal_entries 
    WHERE user_id = :user_id
");
$journalStmt->execute([':user_id' => $user_id]);
$journalTotal = (int)$journalStmt->fetchColumn();

echo json_encode([
    'habits' => [
        'completed' => $completedHabits,
        'missed' => $missedHabits
    ],
    'sleep' => [
        'labels' => !empty($sleepLabels) ? $sleepLabels : ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        'hours' => !empty($sleepHours) ? $sleepHours : [0, 0, 0, 0, 0, 0, 0],
        'energy' => !empty($sleepEnergy) ? $sleepEnergy : [0, 0, 0, 0, 0, 0, 0]
    ],
    'journal' => [
        'total' => $journalTotal
    ]
]);
?>