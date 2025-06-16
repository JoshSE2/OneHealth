<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'Database/db_connect.php';

$errorMsg = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    try {
        // Create PDO connection
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $email = trim($_POST['email']);
        $passwordInput = $_POST['password'];

        if (empty($email) || empty($passwordInput)) {
            $errorMsg = "Please fill in all fields.";
        } else {
            // Fetch user by email
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute([':email' => $email]);

            if ($stmt->rowCount() === 0) {
                $errorMsg = "No account found with that email.";
            } else {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (password_verify($passwordInput, $user['password'])) {
                    $_SESSION['Logged_in'] = true;
                    $_SESSION['fullname'] = $user['fullname'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];

                    // Redirect by role
                    if ($user['role'] === 'patient') {
                        header('Location: patient/index.php');
                        exit();
                    } elseif ($user['role'] === 'doctor') {
                        header('Location: doctor/index.php');
                        exit();
                    } elseif ($user['role'] === 'admin') {
                        header('Location: admin/index.php');
                        exit();
                    }
                } else {
                    $errorMsg = "Incorrect password.";
                }
            }
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        $errorMsg = "Something went wrong. Please try again later.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./css/login.css">
</head>
<body>
<header>
    <a href="index.html" class="logo"><span>O</span>ne<span>H</span>ealth</a>
    <a href="register.php" class="account-btn">Register</a>
</header>

<div class="login-container">
    <h2>WELCOME BACK!</h2>

    <?php if (!empty($errorMsg)): ?>
        <div class="error-message" style="color: red; margin-bottom: 15px;">
            <?php echo htmlspecialchars($errorMsg); ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="login-form">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" placeholder="Enter your Email" required>
        </div>

        <div class="login-form">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your Password" required>
            <button class="hide-password" type="button">Show Password</button>
            <br /><br />
            <button type="submit" name="login">Sign In</button>
        </div>

        <div class="login-form-a">
            <a href="register.php">New User? Register Here</a>
        </div>
    </form>
</div>

<script>
    const passwordInput = document.getElementById('password');
    const showPasswordButton = document.querySelector('.hide-password');

    showPasswordButton.addEventListener('click', () => {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            showPasswordButton.textContent = 'Hide Password';
        } else {
            passwordInput.type = 'password';
            showPasswordButton.textContent = 'Show Password';
        }
    });
</script>
</body>
</html>
