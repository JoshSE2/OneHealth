<?php
// errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once '../Database/db_connect.php';
session_start();

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Check if the form is submitted
    // Get user information from session
    $id = $_POST['id'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phoneNumber'];

    $stmt = $conn->prepare("UPDATE users SET fullname = ?, email = ?, phoneNumber = ? WHERE id = ?");
    $stmt->bind_param("sssi", $fullname, $email, $phone, $id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error updating user: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
