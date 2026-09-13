<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth.html");
    exit();
}
?>
<?php
require_once 'php/db.php';
require_once 'php/journaldb.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_journal'])) {
        createJournalEntry(
            $pdo, 
            $user_id, 
            $_POST['entry_date'], 
            $_POST['daily_highlight'], 
            $_POST['area_growth'], 
            $_POST['gratitude_statement'], 
            $_POST['tags']
        );
    } elseif (isset($_POST['delete_journal'])) {
        deleteJournalEntry($pdo, $_POST['journal_id']);
    }
    header("Location: journal.php");
    exit();
}

$entries = getJournalEntries($pdo, $user_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DayTrace - Micro-Journaling & Reflection</title>
    <link rel="stylesheet" href="css/journal.css">
</head>
<body>

    <nav>
        <a href="index.php" class="logo">DayTrace</a>
        <ul class="nav-links">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="habits.php">Habits</a></li>
            <li><a href="sleep.php">Sleep & Recovery</a></li>
            <li><a href="journal.php" class="active">Micro-Journal</a></li>
            <li style="color: var(--text-main); font-weight: 600; padding: 0 15px; border-right: 1px solid var(--border-light);"><?= htmlspecialchars($_SESSION['username'] ?? 'User') ?></li>
            <li><a href="logout.php" class="btn-logout">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <div class="page-header">
            <h1>Micro-Journaling & Reflection</h1>
            <p>Author structured daily 3-line self-reflections and manage categorical tags.</p>
        </div>

        <div class="content-grid">
            <div class="card form-card">
                <h2 class="card-title">New Reflection</h2>
                <form method="POST" action="journal.php">
                    <div class="input-group">
                        <label for="entry-date">Entry Date</label>
                        <input type="date" id="entry-date" name="entry_date" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="input-group">
                        <label for="daily-highlight">1. Daily Highlight (What went well?)</label>
                        <textarea id="daily-highlight" name="daily_highlight" placeholder="e.g. Successfully finished the web technologies module." required></textarea>
                    </div>
                    <div class="input-group">
                        <label for="area-growth">2. Area for Growth (What can be improved?)</label>
                        <textarea id="area-growth" name="area_growth" placeholder="e.g. Need to manage time better between lectures." required></textarea>
                    </div>
                    <div class="input-group">
                        <label for="gratitude-statement">3. Gratitude Statement (What are you grateful for?)</label>
                        <textarea id="gratitude-statement" name="gratitude_statement" placeholder="e.g. Grateful for a productive study session with friends." required></textarea>
                    </div>
                    <div class="input-group">
                        <label for="entry-tags">Categorical Tags</label>
                        <input type="text" id="entry-tags" name="tags" placeholder="e.g. Productive, Academic (comma separated)">
                    </div>
                    <button type="submit" name="add_journal" class="btn-accent">Save Reflection Entry</button>
                </form>
            </div>

            <div class="card history-card">
                <h2 class="card-title">Reflection Logs History</h2>
                <div class="journal-feed">
                    
                    <?php if (empty($entries)): ?>
                        <p>No reflections logged yet. Start tracing your days!</p>
                    <?php else: ?>
                        <?php foreach ($entries as $entry): ?>
                            <div class="journal-item">
                                <div class="journal-header">
                                    <span class="journal-date"><?= htmlspecialchars($entry['entry_date']) ?></span>
                                    <div class="journal-tags">
                                        <?php foreach ($entry['tags'] as $tag): ?>
                                            <span class="tag-badge"><?= htmlspecialchars($tag) ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="journal-field">
                                    <strong>Daily Highlight</strong>
                                    <p><?= htmlspecialchars($entry['daily_highlight']) ?></p>
                                </div>
                                <div class="journal-field">
                                    <strong>Area for Growth</strong>
                                    <p><?= htmlspecialchars($entry['area_for_growth']) ?></p>
                                </div>
                                <div class="journal-field">
                                    <strong>Gratitude Statement</strong>
                                    <p><?= htmlspecialchars($entry['gratitude_statement']) ?></p>
                                </div>
                                <div class="journal-actions">
                                    <form method="POST" action="journal.php" style="display:inline;">
                                        <input type="hidden" name="journal_id" value="<?= $entry['journal_id'] ?>">
                                        <button type="submit" name="delete_journal" class="action-btn btn-delete">Delete</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

</body>
</html>