<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>DayTrace - Overview Dashboard</title>
<link rel="stylesheet" href="css/dashboard.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    <nav>
        <a href="dashboard.php" class="logo">DayTrace</a>
        <ul class="nav-links">
            <li><a href="dashboard.php" class="active">Dashboard</a></li>
            <li><a href="habits.php">Habits</a></li>
            <li><a href="sleep.php">Sleep & Recovery</a></li>
            <li><a href="journal.php">Micro-Journal</a></li>
            <li style="color: var(--text-main); font-weight: 600; padding: 0 15px; border-right: 1px solid var(--border-light);"><?= htmlspecialchars($_SESSION['username'] ?? 'User') ?></li>
            <li><a href="logout.php" class="btn-logout">Logout</a></li>
        </ul>
    </nav>

    <div class="dashboard-container">
        
        <div class="welcome-header">
            <h1>Welcome back, <?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></h1>
            <p>Here is your real-time personal wellness and consistency overview.</p>
        </div>

        <div class="overview-grid">
            <div class="chart-card">
                <h2 class="card-title">Habit Completion Overview</h2>
                <div class="canvas-wrapper pie-wrapper">
                    <canvas id="habitPieChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <h2 class="card-title">Sleep Duration & Energy Trends</h2>
                <div class="canvas-wrapper bar-wrapper">
                    <canvas id="sleepGraph"></canvas>
                </div>
            </div>
        </div>

        <div class="chart-card full-width-card">
            <h2 class="card-title">Micro-Journaling Summary</h2>
            <div class="tags-overview">
                <span class="status-badge badge-productive" id="journal-counter">Total Reflections Logged: 0</span>
            </div>
            <div class="canvas-wrapper line-wrapper">
                <canvas id="journalGraph"></canvas>
            </div>
        </div>

    </div>

    <script src="js/dashboard.js"></script>
</body>
</html>