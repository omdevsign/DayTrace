<?php
session_start();
require_once 'php/db.php';

$action = $_POST['action'] ?? '';

if ($action === 'register') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($email) || empty($password)) {
        header("Location: auth.html?error=" . urlencode("All fields are required."));
        exit();
    }

    try {
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = :email OR username = :username");
        $stmt->execute(['email' => $email, 'username' => $username]);
        
        if ($stmt->fetch()) {
            header("Location: auth.html?error=" . urlencode("Username or Email already exists."));
            exit();
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $insertStmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password_hash)");
        $insertStmt->execute([
            'username' => $username,
            'email' => $email,
            'password_hash' => $hashedPassword
        ]);

        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['role'] = 'User';

        header("Location: dashboard.php");
        exit();

    } catch (PDOException $e) {
        header("Location: auth.html?error=" . urlencode("Registration failed: " . $e->getMessage()));
        exit();
    }

} elseif ($action === 'login') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        header("Location: auth.html?error=" . urlencode("Email and password are required."));
        exit();
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            if ($user['status'] === 'Suspended') {
                header("Location: auth.html?error=" . urlencode("Your account has been suspended."));
                exit();
            }

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'Admin') {
                header("Location: admin.php");
            } else {
                header("Location: dashboard.php");
            }
            exit();
        } else {
            header("Location: auth.html?error=" . urlencode("Invalid email or password."));
            exit();
        }

    } catch (PDOException $e) {
        header("Location: auth.html?error=" . urlencode("Login failed: " . $e->getMessage()));
        exit();
    }

} else {
    header("Location: auth.html");
    exit();
}
?>