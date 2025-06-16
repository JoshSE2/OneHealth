<?php

// Include database connection
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

// to ensure only admin is logged in and can update user roles
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Get user information from session
$fullname = $_SESSION['fullname'] ?? '';
$email = $_SESSION['email'] ?? '';
// Fetch all users from the database
$stmt = $conn->prepare("SELECT id, email, role FROM users");
$stmt->execute();
$result = $stmt->get_result();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['role'])) {
    $id = $_POST['id'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->bind_param("si", $role, $id);

    //
    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error updating role: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
