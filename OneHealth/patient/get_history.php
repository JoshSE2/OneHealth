<?php
include_once '../Database/db_connect.php';

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

ini_set('session.gc_maxlifetime', $sessiion_lifetime);
session_start();

// Check if user is logged in
if (!isset($_SESSION['fullname'])) {
    echo json_encode([]);
    exit;
}

$fullname = $_SESSION['fullname'];

// Get user's symptom history by fullname
$sql = "SELECT id, symptoms, solution, created_at FROM symptom_history WHERE fullname = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $fullname);
$stmt->execute();
$result = $stmt->get_result();

// Fetch all history items
$history = [];
while ($row = $result->fetch_assoc()) {
    $history[] = [
        'id' => $row['id'],
        'symptoms' => $row['symptoms'],
        'solution' => $row['solution'],
        'created_at' => date('M d, Y h:i A', strtotime($row['created_at']))
    ];
}

$stmt->close();
$conn->close();

echo json_encode($history);
