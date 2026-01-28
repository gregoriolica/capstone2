<?php
session_start();
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../url.php';
if (!isset($_SESSION['pending_user'])) {
    die("Session expired or missing. Please restart registration.");
}

$user = $_SESSION['pending_user'];

// 🔍 Check for duplicates
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR contact = ?");
$stmt->execute([$user['email'], $user['contact']]);
if ($stmt->fetch()) {
    unset($_SESSION['pending_user']);
    die("Email or contact number already registered.");
}

// ✅ Insert new user
$stmt = $pdo->prepare("
    INSERT INTO users (first_name, last_name, email, contact, role, password)
    VALUES (?, ?, ?, ?, ?, ?)
");
$stmt->execute([
    $user['first_name'],
    $user['last_name'],
    $user['email'],
    $user['contact'],
    $user['role'],
    $user['password']
]);

// 🧹 Clean up session
unset($_SESSION['pending_user']);

// 🎉 Redirect or confirm

header("Location: " . URL_LOGIN);
// Or: header("Location: ../login.php");
?>