<?php
include_once '../Database/db_connect.php';
session_start();

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'doctor') {
    echo "Unauthorized access";
    exit();
}

$email = $_SESSION['email'];
$stmt = $conn->prepare("DELETE FROM doctors WHERE doctor_email = ?");
if ($stmt->execute([$email])) {
    echo "Account successfully deleted.";
    session_destroy();
} else {
    echo "Failed to delete account.";
}
