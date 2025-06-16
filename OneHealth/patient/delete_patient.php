<?php
include_once '../Database/db_connect.php';
session_start();

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'patient') {
    echo "Unauthorized access.";
    exit();
}

$email = $_SESSION['email'];

$sql = "DELETE FROM users WHERE email = ? AND role = 'patient'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);

if ($stmt->execute()) {
    session_destroy();
    echo "Account deleted successfully.";
} else {
    echo "Failed to delete account: " . $stmt->error;
}

$stmt->close();
