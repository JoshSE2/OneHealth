<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="./css/register.css">
    <script src="register_success.js"></script>
    
</head>
<body>
<header>
    <a href="index.html" class="logo"><span>O</span>ne<span>H</span>ealth</a>
    <a href="login.php" class="account-btn">Login</a>
</header>

<div class="register-container">
    <h2>REGISTER HERE</h2>
    <form action="register.php" method="POST">
        <div class="register-form">
            <label for="fullname">Full Name</label>
            <input type="text" id="fullname" name="fullname" required>
        </div>

        <div class="register-form">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="register-form">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="register-form">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>

        <div class="register-form">
  <label for="phoneNumber">Phone Number</label>
  <input type="tel" id="phoneNumber" name="phoneNumber" required pattern="[0-9]+" maxlength="11" placeholder="e.g. 0123456789">
</div>

        <div class="register-form">
            <button type="submit" name="register">Register</button>
        </div>

        <div class="register-form-a">
            <a href="login.php">Already have an account? Login</a>
        </div>
    </form>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = new mysqli('localhost', 'root', '', 'onehealth_db');

    if ($conn->connect_error) {
        die('Connection Failed: ' . $conn->connect_error);
    }

    // Sanitize and fetch inputs
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $phoneNumber = trim($_POST['phoneNumber']);
    $role = 'patient';

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "<p style='color: red; text-align: center;'>Passwords do not match.</p>";
        $conn->close();
        exit();
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<p style='color: red; text-align: center;'>Email already registered. Please use another.</p>";
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (fullname, email, password, phoneNumber, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $fullname, $email, $hashed_password, $phoneNumber, $role);

        if ($stmt->execute()) {
            // Display the html success message
            echo "<div class='success-message' id='success-message' style='display: none;'>";
            echo "<h2>Registration Successful!</h2>";
            echo "<p>Welcome to OneHealth, $fullname!</p>";
            // Redirect to login page after 3 seconds
            echo "<script>setTimeout(function() { window.location.href = 'login.php'; }, 3000);</script>";
        } else {
            echo "<p style='color: red; text-align: center;'>ERROR: " . $stmt->error . "</;>";
        }
    }

    $stmt->close();
    $conn->close();
}
?>
</body>
</html>