<?php
include_once '../Database/db_connect.php';
session_start();

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'doctor') {
    header("Location: ../login.php");
    exit();
}

$email = $_SESSION['email'] ?? '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullname = trim($_POST['fullname']);
    $phoneNumber = trim($_POST['phoneNumber']);
    $password = trim($_POST['password']);

    if (empty($fullname) || empty($phoneNumber)) {
        echo "Please fill in all required fields.";
        exit;
    }

    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE doctors SET doctor_name = ?, doctor_phone = ?, doctor_password = ? WHERE doctor_email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $fullname, $phoneNumber, $hashedPassword, $email);
    } else {
        $sql = "UPDATE doctors SET doctor_name = ?, doctor_phone = ? WHERE doctor_email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $fullname, $phoneNumber, $email);
    }

    if ($stmt->execute()) {
        // Update session name if necessary
        $_SESSION['fullname'] = $fullname;
        header("Location: doctor_settings.php?success=1");
    } else {
        echo "Error updating account.";
    }
}