<?php
include_once '../Database/db_connect.php';
session_start();

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'patient') {
    header("Location: ../login.php");
    exit();
}

$email = $_SESSION['email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname']);
    $phone = trim($_POST['phoneNumber']);
    $password = trim($_POST['password']);

    if (empty($fullname) || empty($phone)) {
        echo "Full name and phone number are required.";
        exit();
    }

    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET fullname = ?, phoneNumber = ?, password = ? WHERE email = ? AND role = 'patient'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $fullname, $phone, $hashedPassword, $email);
    } else {
        $sql = "UPDATE users SET fullname = ?, phoneNumber = ? WHERE email = ? AND role = 'patient'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $fullname, $phone, $email);
    }

    if ($stmt->execute()) {
        $_SESSION['fullname'] = $fullname;
        echo "<script>alert('Account updated successfully.'); window.location.href = 'settings.php';</script>";
    } else {
        echo "Update failed: " . $stmt->error;
    }

    $stmt->close();
}