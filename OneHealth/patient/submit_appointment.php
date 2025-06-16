<?php
include_once '../Database/db_connect.php';

// Display errors for debugging (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// set session duration before logging out
$sessiion_lifetime = 3600; // 1 hour
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get patient details from session
    $email = $_SESSION['email'];
    $fullname = $_SESSION['fullname'];

    // Fetch phone number from database
    $phone = '';
    $stmt = $conn->prepare("SELECT phoneNumber FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($phone);
    $stmt->fetch();
    $stmt->close();

    // Get form inputs
    $date = $_POST['appointment_date'];
    $time = $_POST['appointment_time'];
    $message = htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8');
    $doctor_email = $_POST['doctor_email'];

    // Input validation
    if (empty($fullname) || empty($phone) || empty($date) || empty($time) || empty($message) || empty($doctor_email)) {
        echo "All fields are required.";
        exit;
    }

    if (!preg_match("/^[a-zA-Z ]*$/", $fullname)) {
        echo "Only letters and white space allowed in name.";
        exit;
    }

    if (!preg_match("/^\d+$/", $phone)) {
        echo "Invalid phone number format. Must contain only digits.";
        exit;
    }

    if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $date)) {
        echo "Invalid date format. Use YYYY-MM-DD.";
        exit;
    }

    if (!preg_match("/^\d{2}:\d{2}$/", $time)) {
        echo "Invalid time format. Use HH:MM.";
        exit;
    }

    if (!filter_var($doctor_email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid doctor email format.";
        exit;
    }

    // Insert appointment into database
    $insert = $conn->prepare("INSERT INTO booked_appointments
        (patient_name, phone, appointment_date, appointment_time, doctor_email, message, status, patient_email)
        VALUES (?, ?, ?, ?, ?, ?, 'pending', ?)");

    $insert->bind_param("sssssss", $fullname, $phone, $date, $time, $doctor_email, $message, $email);

    if ($insert->execute()) {
        // Redirect to success popup page
        header("Location: success_popup.php");
        exit();
    } else {
        echo "Error: " . $insert->error;
    }

    $stmt = $conn->prepare("SLECT * FROM booked_appointments WHERE appointment_date = ? AND appointment_time = ?");
    $stmt->bind_param("ss", $date, $time);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // Appointment time is already booked
        echo "This appointment time is already booked. Please choose another time.";
        exit();
    }

    $insert->close();
    $conn->close();
} else {
    echo "Invalid request method.";
    exit();
}