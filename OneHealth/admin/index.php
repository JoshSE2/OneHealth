<?php
include_once '../Database/db_connect.php';

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

// Check if user is logged in and is an admin
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
// Get user information from session
$fullname = $_SESSION['fullname'] ?? '';
$email = $_SESSION['email'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/header.css">
  <link rel="stylesheet" href="../css/dashboard.css">
  <link rel="stylesheet" href="../css/user.css">
  <style>
    table {
        width: 100%;
        border-collapse: collapse;
        background-color: #fff;
        margin-top: 20px;
    }
    th, td {
        padding: 12px 15px;
        border: 3px solid #ddd;
        text-align: center;
        font-size: 20px;
    }
    th {
        background-color: rgb(6, 86, 113);
        text-align: center;
        color: white;
        font-weight: bold;
        font-size: 24px;
    }
    .actions button {
    padding: 8px 16px;
    margin-right: 6px;
    cursor: pointer;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    transition: background-color 0.3s ease, transform 0.2s ease;
    color: black;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
}
.actions button:hover {
    background-color: #2f6edc;
    transform: translateY(-1px);
}

.actions button:active {
    transform: translateY(0);
}

    .actions form {
        display: inline;
    }
    .appointments-section h2{
      font-size: 30px;
    }
    .appointments-section {
        margin-top: 20px;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    }
  </style>
</head>
<body>

<header>
    <a href="#" class="logo"><span>O</span>ne<span>H</span>ealth</a>
    <a href="../logout.php" class="account-btn">Logout</a>
</header>

<div class="dashboard-cont">
    <div class="sidebar">
        <div class="user-info">
            <div class="avatar"></div>
            <div class="user-details">
                <h3><?= htmlspecialchars($fullname) ?></h3>
                <p><?= htmlspecialchars($email) ?></p>
            </div>
        </div>

        <div class="menu-item active">
            <div class="icon">🛠️</div>
            <a href="index.php">Admin Panel</a>
        </div>

        <div class="menu-item">
            <div class="icon">📅</div>
            <a href="appointments.php">All Appointments</a>
        </div>

        <div class="menu-item">
            <div class="icon">⚙️</div>
            <a href="settings.php">Settings</a>
        </div>

        <div class="menu-item logout">
            <div class="icon">🔒</div>
            <a href="../logout.php">Logout</a>
        </div>
    </div>

    <div class="main-content">
        <div class="dashboard-header">
            <h1>Welcome, <?= htmlspecialchars($fullname); ?></h1>
            <p>Manage all users registered on the OneHealth platform below.</p>
        </div>

        <div class="appointments-section">
            <h2>All Users</h2>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
                <?php
                $sql = "SELECT * FROM users";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()):
                ?>
                <tr>
                    <td><?= htmlspecialchars($row['fullname']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['phoneNumber']) ?></td>
                    <td>
                        <form method="POST" action="update_role.php">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <select name="role" onchange="this.form.submit()">
                                <option value="admin" <?= $row['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="doctor" <?= $row['role'] == 'doctor' ? 'selected' : '' ?>>Doctor</option>
                                <option value="patient" <?= $row['role'] == 'patient' ? 'selected' : '' ?>>Patient</option>
                            </select>
                        </form>
                    </td>
                    <td class="actions">
                        <form action="edit_users.php" method="GET">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <button type="submit">Edit</button>
                        </form>
                        <form action="delete_user.php" method="POST" onsubmit="return confirm('Are you sure?');">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <button type="submit" style="color:red;">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</div>
</body>
</html>
