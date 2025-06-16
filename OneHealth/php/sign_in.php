<?php
session_start();

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'onehealth_db';

// Creating PDO connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Checking if form is submitted
    if (isset($_POST['login'])) {
        $email = trim($_POST['email']);
        $passwordInput = $_POST['password'];

        // Validate fields
        if (empty($email) || empty($passwordInput)) {
            header("Location: ../login.php?error=empty_fields");
            exit();
        }

        // Fetch users with matching email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);

        if ($stmt->rowCount() === 0) {
            header("Location: ./login.php?error=no_account");
            exit();
        }

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check and verify password
        if (password_verify($passwordInput, $user['password'])) {
            $_SESSION['Logged_in'] = true;
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            // Redirect users based on role
            if ($user['role'] === 'patient') {
                header('Location: ../patient/index.php');
            } elseif ($user['role'] === 'doctor') {
                header('Location: ../doctor/index.php'); //
            } else {
                header('Location: ../admin/index.php');
            }
            exit();
        } else {
            header("Location: ./login.php?error=invalid_password");
            exit();
        }
    } else {
        header("Location: ./login.php?error=unauthorized");
        exit();
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    header("Location: ./login.php?error=server_error");
    exit();
}
