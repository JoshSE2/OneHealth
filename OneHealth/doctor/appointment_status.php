<?php
include_once '../Database/db_connect.php';

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

// Make sure the user is logged in and is a doctor
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'doctor') {
    header("Location: ../login.php");
    exit();
}


// Check if the required POST variables exist
if (!isset($_POST['id']) || !isset($_POST['action'])) {
    echo "Missing required parameters.";
    exit;
}

$id = $_POST['id'];
$action = $_POST['action'];

// Set the status based on the action
if ($action == 'approve') {
    $status = 'approved';
} elseif ($action == 'cancel') {
    $status = 'cancelled';
} else {
    echo "Invalid action.";
    exit;
}

// Prepare and execute the update query
$sql = "UPDATE booked_appointments SET status=? WHERE id=?"; // Update the status of the appointment
$stmt = $conn->prepare($sql); // Use prepared statements to prevent SQL injection
$stmt->bind_param("si", $status, $id);
$stmt->execute();

// Check if the update was successful
if ($stmt->affected_rows > 0) {
    $message = "Appointment status updated successfully.";
} else {
    $message = "Error updating appointment status or no changes made.";
}

$stmt->close();
$conn->close();

// Redirect doctor to the home page with a message
header("Location: index.php?message=" . urlencode($message));
exit();