<?php
include_once '../Database/db_connect.php';
session_start();

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'doctor') {
    header("Location: ../login.php");
    exit();
}

// Get doctor email from the session
$doctor_email = $_SESSION['email'];
$fullname = $_SESSION['fullname'];

// Fetch doctor details
$sql = "SELECT doctor_name, doctor_email, doctor_phone FROM doctors WHERE doctor_email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $doctor_email);
$stmt->execute();
$result = $stmt->get_result();
$doctor = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Doctor Info</title>
    <link rel="stylesheet" href="../css/doc_dashboard.css">
    <style>
        .main {
            padding: 40px;
        }
        .info-box {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            max-width: 500px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .info-box h3 {
            margin-top: 0;
        }
        .info-box p {
            margin: 10px 0;
        }
    </style>
</head>
<body>
<div class="main">
    <h2>Account Information</h2>
    <div class="info-box">
        <h3><?= htmlspecialchars($doctor['doctor_name']) ?></h3>
        <p><strong>Email:</strong> <?= htmlspecialchars($doctor['doctor_email']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($doctor['doctor_phone']) ?></p>
    </div>
</div>
</body>
</html>