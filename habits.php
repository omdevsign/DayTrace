<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth.html");
    exit();
}
?>
<?php
require_once 'php/db.php';
require_once 'php/habitdb.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_habit'])) {
        createHabit($pdo, $user_id, $_POST['habit_name'], $_POST['target_frequency']);
    } elseif (isset($_POST['complete_habit'])) {
        logHabitCompletion($pdo, $_POST['habit_id'], date('Y-m-d'));
    } elseif (isset($_POST['delete_habit'])) {
        deleteHabit($pdo, $_POST['habit_id']);
    }
    header("Location: habits.php");
    exit();
}

$habits = getHabitsByUser($pdo, $user_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DayTrace - Habit Formation</title>
    <link rel="stylesheet" href="css/habits.css">
</head>
<body>

    <nav>
        <a href="index.php" class="logo">DayTrace</a>
        <ul class="nav-links">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="habits.php" class="active">Habits</a></li>
            <li><a href="sleep.php">Sleep & Recovery</a></li>
            <li><a href="journal.php">Micro-Journal</a></li>
            <li style="color: var(--text-main); font-weight: 600; padding: 0 15px; border-right: 1px solid var(--border-light);"><?= htmlspecialchars($_SESSION['username'] ?? 'User') ?></li>
            <li><a href="logout.php" class="btn-logout">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <div class="page-header">
            <h1>Habit Formation & Routine Building</h1>
            <p>Define new recurring habits and log daily execution consistency.</p>
        </div>

        <div class="content-grid">
            <div class="card form-card">
                <h2 class="card-title">Define New Habit</h2>
                <form method="POST" action="habits.php">
                    <div class="input-group">
                        <label for="habit-name">Habit Name</label>
                        <input type="text" id="habit-name" name="habit_name" placeholder="e.g. Read 15 Pages" required>
                    </div>

                    <div class="input-group">
                        <label for="target-frequency">Target Frequency</label>
                        <select id="target-frequency" name="target_frequency" required>
                            <option value="Daily">Daily</option>
                            <option value="Weekly">Weekly</option>
                        </select>
                    </div>

                    <button type="submit" name="add_habit" class="btn-add">Add Habit Target</button>
                </form>
            </div>

            <div class="card list-card">
                <h2 class="card-title">Active Configured Habits</h2>
                <div class="habit-list">
                    
                    <?php if (empty($habits)): ?>
                        <p>No active habits found. Start by creating one!</p>
                    <?php else: ?>
                        <?php foreach ($habits as $habit): ?>
                            <?php $is_completed_today = isHabitLoggedToday($pdo, $habit['habit_id'], date('Y-m-d')); ?>
                            <div class="habit-item">
                                <div class="habit-details">
                                    <h4><?= htmlspecialchars($habit['habit_name']) ?> <span class="badge-freq"><?= htmlspecialchars($habit['target_frequency']) ?></span></h4>
                                    <p>Created: <?= date('Y-m-d', strtotime($habit['created_at'])) ?></p>
                                </div>
                                <div class="habit-actions">
                                    
                                    <?php if ($is_completed_today): ?>
                                        <button type="button" class="btn-complete" style="background-color: #64748b; cursor: default;" disabled>Completed ✓</button>
                                    <?php else: ?>
                                        <form method="POST" action="habits.php" class="inline-form">
                                            <input type="hidden" name="habit_id" value="<?= $habit['habit_id'] ?>">
                                            <button type="submit" name="complete_habit" class="btn-complete">Log Complete</button>
                                        </form>
                                    <?php endif; ?>
                                    
                                    <form method="POST" action="habits.php" class="inline-form delete-form">
                                        <input type="hidden" name="habit_id" value="<?= $habit['habit_id'] ?>">
                                        <button type="submit" name="delete_habit" class="btn-delete-habit">Delete</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

    <script src="js/habits.js"></script>
</body>
</html>