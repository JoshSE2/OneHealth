<?php
// Include database connection
include_once '../Database/db_connect.php';
session_start();

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

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT fullname, email, phoneNumber FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit User</title>
    <style>
        body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f0f2f5;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 600px;
    margin: 80px auto;
    background: #fff;
    border-radius: 15px;
    padding: 40px 30px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
    margin-bottom: 30px;
    color: #333;
}

form label {
    display: block;
    font-weight: 600;
    margin-bottom: 5px;
    color: #555;
}

form input[type="text"],
form input[type="email"] {
    width: 100%;
    padding: 12px 15px;
    margin-bottom: 20px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 15px;
    transition: border-color 0.3s;
}

form input[type="text"]:focus,
form input[type="email"]:focus {
    border-color: #3a80e9;
    outline: none;
}

button {
    background-color: #3a80e9;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    cursor: pointer;
    width: 100%;
    margin-top: 10px;
    transition: background 0.3s;
}

button:hover {
    background-color: #2f6edc;
}

a.back-link {
    display: block;
    text-align: center;
    margin-top: 25px;
    text-decoration: none;
    color: #3a80e9;
    font-weight: bold;
    transition: color 0.2s;
}

a.back-link:hover {
    color: #2f6edc;
}

    </style>
</head>
<body>
    <div class="container">
        <h2>Edit User</h2>
        <form method="POST" action="save_users.php">
            <input type="hidden" name="id" value="<?= $id ?>">
            <label>Full Name:</label>
            <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname']) ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

            <label>Phone Number:</label>
            <input type="text" name="phoneNumber" value="<?= htmlspecialchars($user['phoneNumber']) ?>" required>

            <button type="submit">Save Changes</button>
        </form>
        <a class="back-link" href="index.php">← Back to Admin Panel</a>
    </div>
</body>
</html>
