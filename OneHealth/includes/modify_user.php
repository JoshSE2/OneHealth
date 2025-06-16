<?php
include_once '../Database/db_connect.php';

session_start();

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'patient') {
    header("Location: ../login.php");
    exit();
}

$email = $_SESSION['email'];
$fullname = $_SESSION['fullname'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_fullname = trim($_POST['fullname']);
    $new_password = trim($_POST['password']);

    if (!empty($new_fullname)) {
        $stmt = $conn->prepare("UPDATE users SET fullname = ? WHERE email = ?");
        $stmt->bind_param("ss", $new_fullname, $email);
        $stmt->execute();
        $_SESSION['fullname'] = $new_fullname;
    }

    if (!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $hashed_password, $email);
        $stmt->execute();
    }

    header("Location: view_user.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Account</title>
    <link rel="stylesheet" href="../css/doc_dashboard.css">
</head>
<body>
    <?php include 'patient_header.php'; ?>

    <div class="main">
        <h2>Edit Your Account</h2>
        <form method="POST">
            <label>Full Name</label>
            <input type="text" name="fullname" value="<?= htmlspecialchars($fullname) ?>" required>
            <label>New Password (Leave blank to keep current password)</label>
            <input type="password" name="password">
            <button type="submit">Update</button>
        </form>
    </div>

    <?php include '../footer.php'; ?>
</body>
</html>
