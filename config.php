<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database configuration
$host = 'localhost';
$dbname = 'jam_db';
$username = 'root';
$password = '';

// SMS API credentials
$sms_token= ""; //SMS API TOKEN HERE(WILL BE PROVIDED LATER)
$sms_send_endpoint = 'https://rest.moceanapi.com/rest/2/sms';

// PDO Connection
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
    
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
    System.print("Username does not exist ");
}

// Initialize User Role
$_SESSION['user_role'] = $_SESSION['user_role'] ?? null;

// Map user_id to patient_id if role is patient
if ($_SESSION['user_role'] === 'patient' && isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT patient_id FROM patient_records WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $result = $stmt->fetch();
    $_SESSION['patient_id'] = $result['patient_id'] ?? null;
}
?>