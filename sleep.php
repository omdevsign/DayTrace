<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth.html");
    exit();
}
?>
<?php
require_once 'php/db.php';
require_once 'php/sleepdb.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_goal'])) {
        updateSleepGoal($pdo, $user_id, $_POST['target_hours']);
    } elseif (isset($_POST['add_sleep_log'])) {
        addSleepLog($pdo, $user_id, $_POST['log_date'], $_POST['hours_slept'], $_POST['energy_rating']);
    } elseif (isset($_POST['delete_log'])) {
        deleteSleepLog($pdo, $_POST['sleep_log_id']);
    }
    header("Location: sleep.php");
    exit();
}

$target_goal = getSleepGoal($pdo, $user_id);
$sleep_logs = getSleepLogsByUser($pdo, $user_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DayTrace - Sleep & Recovery</title>
    <link rel="stylesheet" href="css/sleep.css">
</head>
<body>

    <nav>
        <a href="index.php" class="logo">DayTrace</a>
        <ul class="nav-links">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="habits.php">Habits</a></li>
            <li><a href="sleep.php" class="active">Sleep & Recovery</a></li>
            <li><a href="journal.php">Micro-Journal</a></li>
            <li style="color: var(--text-main); font-weight: 600; padding: 0 15px; border-right: 1px solid var(--border-light);"><?= htmlspecialchars($_SESSION['username'] ?? 'User') ?></li>
            <li><a href="logout.php" class="btn-logout">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <div class="page-header">
            <h1>Sleep & Recovery Tracker</h1>
            <p>Set target sleep goals and record daily sleep duration and energy metrics.</p>
        </div>

        <div class="content-grid">
            <div class="card goal-card">
                <h2 class="card-title">Target Goal</h2>
                <form method="POST" action="sleep.php">
                    <div class="input-group">
                        <label for="target-hours">Target Sleep Duration (Hours)</label>
                        <input type="number" id="target-hours" name="target_hours" step="0.5" min="4" max="12" value="<?= htmlspecialchars($target_goal) ?>" required>
                    </div>
                    <button type="submit" name="update_goal" class="btn-accent">Update Goal Baseline</button>
                </form>
            </div>

            <div class="card log-card">
                <h2 class="card-title">Log Sleep Entry</h2>
                <form method="POST" action="sleep.php">
                    <div class="input-group">
                        <label for="log-date">Log Date</label>
                        <input type="date" id="log-date" name="log_date" required>
                    </div>
                    <div class="input-group">
                        <label for="hours-slept">Hours Slept</label>
                        <input type="number" id="hours-slept" name="hours_slept" step="0.5" min="0" max="24" placeholder="e.g. 7.5" required>
                    </div>
                    <div class="input-group">
                        <label for="energy-rating">Qualitative Energy Level (1 to 5)</label>
                        <select id="energy-rating" name="energy_rating" required>
                            <option value="">Select Level</option>
                            <option value="1">1 - Very Low</option>
                            <option value="2">2 - Low</option>
                            <option value="3">3 - Moderate</option>
                            <option value="4">4 - High</option>
                            <option value="5">5 - Excellent</option>
                        </select>
                    </div>
                    <button type="submit" name="add_sleep_log" class="btn-accent">Record Sleep Entry</button>
                </form>
            </div>
        </div>

        <div class="card table-card">
            <h2 class="card-title">Sleep & Recovery Logs History</h2>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Hours Slept</th>
                        <th>Target Comparison</th>
                        <th>Energy Rating</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($sleep_logs)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center;">No sleep logs recorded yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($sleep_logs as $log): ?>
                            <tr>
                                <td><?= htmlspecialchars($log['log_date']) ?></td>
                                <td><?= htmlspecialchars(number_format($log['hours_slept'], 1)) ?> hrs</td>
                                <td>
                                    <?php if ($log['hours_slept'] >= $target_goal): ?>
                                        <span class="status-badge badge-good">Target Met</span>
                                    <?php else: ?>
                                        <span class="status-badge badge-low">Sleep Deficit</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($log['energy_rating']) ?> / 5</td>
                                <td>
                                    <form method="POST" action="sleep.php" style="display:inline;" onsubmit="return confirm('Delete this sleep log?');">
                                        <input type="hidden" name="sleep_log_id" value="<?= $log['sleep_log_id'] ?>">
                                        <button type="submit" name="delete_log" class="action-btn btn-delete">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="js/sleep.js"></script>
</body>
</html>