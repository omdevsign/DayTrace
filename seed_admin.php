<?php
require_once 'php/db.php';

$adminUsername = 'admin';
$adminEmail = 'admin@daytrace.com';
$adminPassword = 'Admin123';

try {
    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = :email OR username = :username");
    $stmt->execute(['email' => $adminEmail, 'username' => $adminUsername]);

    if ($stmt->fetch()) {
        echo "Admin account already exists.";
        echo "<a href='auth.html'>Back to Login Page</a>";
    } else {
        $hashedPassword = password_hash($adminPassword, PASSWORD_DEFAULT);
        $insertStmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, role, status) VALUES (:username, :email, :password_hash, 'Admin', 'Active')");
        $insertStmt->execute([
            'username' => $adminUsername,
            'email' => $adminEmail,
            'password_hash' => $hashedPassword
        ]);

        echo "Admin account created successfully!";
        echo "<a href='auth.html'>Back to Login Page</a>";
    }
} catch (PDOException $e) {
    echo "Error creating admin account: " . $e->getMessage();
    echo "<a href='auth.html'>Back to Login Page</a>";
}
?>