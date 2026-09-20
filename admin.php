<?php
session_start();

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'Admin') {
    header("Location: auth.html");
    exit();
}

require_once 'php/db.php';
require_once 'php/admindb.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['toggle_status'])) {
        $new_status = $_POST['current_status'] === 'Active' ? 'Suspended' : 'Active';
        updateUserStatus($pdo, $_POST['user_id'], $new_status);
    } elseif (isset($_POST['delete_user'])) {
        deleteUser($pdo, $_POST['user_id']);
    }
    header("Location: admin.php");
    exit();
}

$stats = getSystemStats($pdo);
$users = getAllUsers($pdo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DayTrace - Admin Portal</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

    <nav>
        <a href="index.html" class="logo">DayTrace</a>
        <ul class="nav-links">
            <li style="color: var(--text-main); font-weight: 600; padding: 0 15px; border-right: 1px solid var(--border-light);"><?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></li>
            <li><a href="logout.php" class="btn-logout">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        
        <div class="page-header">
            <h1>System Administration</h1>
            <p>Monitor system usage and manage registered user accounts.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <span class="stat-label">Total Registered Users</span>
                <div class="stat-value"><?= htmlspecialchars($stats['total_users']) ?></div>
                <span class="stat-meta">Active Database Records</span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Daily Logs Submitted</span>
                <div class="stat-value"><?= htmlspecialchars($stats['total_logs']) ?></div>
                <span class="stat-meta">Habits, Sleep & Journal</span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Active Users</span>
                <div class="stat-value"><?= htmlspecialchars($stats['active_users']) ?></div>
                <span class="stat-meta">Accounts in Good Standing</span>
            </div>
        </div>

        <div class="card-table">
            <div class="table-header">
                <h2>User Management</h2>
                <input type="text" id="user-search" class="search-input" placeholder="Search by name or email...">
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User Details</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="user-table-body">
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--text-muted);">No users found in database.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td>#<?= htmlspecialchars($u['user_id']) ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($u['username']) ?></strong><br>
                                    <small><?= htmlspecialchars($u['email']) ?></small>
                                </td>
                                <td>
                                    <span class="badge <?= $u['role'] === 'Admin' ? 'badge-role' : 'badge-user' ?>">
                                        <?= htmlspecialchars($u['role']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge <?= $u['status'] === 'Active' ? 'badge-active' : 'badge-suspended' ?>">
                                        <?= htmlspecialchars($u['status']) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($u['created_date']) ?></td>
                                <td>
                                    <?php if ($u['user_id'] != $_SESSION['user_id']): ?>
                                        <form method="POST" action="admin.php" style="display:inline;">
                                            <input type="hidden" name="user_id" value="<?= $u['user_id'] ?>">
                                            <input type="hidden" name="current_status" value="<?= $u['status'] ?>">
                                            <button type="submit" name="toggle_status" class="btn-action <?= $u['status'] === 'Active' ? 'btn-suspend' : 'btn-activate' ?>">
                                                <?= $u['status'] === 'Active' ? 'Suspend' : 'Activate' ?>
                                            </button>
                                        </form>

                                        <form method="POST" action="admin.php" style="display:inline;" onsubmit="return confirm('Permanently delete this user and all associated logs?');">
                                            <input type="hidden" name="user_id" value="<?= $u['user_id'] ?>">
                                            <button type="submit" name="delete_user" class="btn-action btn-delete">Delete</button>
                                        </form>
                                    <?php else: ?>
                                        <small style="color: var(--text-muted);">Current User</small>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

    <script src="js/admin.js"></script>
</body>
</html>