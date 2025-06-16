<?php
include_once '../Database/db_connect.php';

// Display errors for debugging (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// set session duration before logging out
$session_lifetime = 3600; // 1 hour
session_set_cookie_params([
    'lifetime' => $session_lifetime,
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Strict'
]);

ini_set('session.gc_maxlifetime', $session_lifetime);
session_start();

// Check if user is logged in and is a patient
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'patient') {
    header("Location: ../login.php");
    exit();
}

// Get user information from session
$fullname = $_SESSION['fullname'] ?? '';

// Get data from request
$symptom_name = $_POST['symptom_name'] ?? '';
$solution = $_POST['solution'] ?? '';

if (empty($symptom_name) || empty($solution)) {
    echo json_encode(['success' => false, 'message' => 'Missing required information']);
    exit;
}

// Save to history
$sql = "INSERT INTO symptom_history (fullname, symptoms, solution) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $fullname, $symptom_name, $solution);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $stmt->error]);
}

// Close database connection
$stmt->close();
$conn->close();
?>
