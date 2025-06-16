<?php
include_once '../Database/db_connect.php';

// Error Reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Session Setup
$session_lifetime = 3600;
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

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'doctor') {
    header("Location: ../login.php");
    exit();
}

$email = $_SESSION['email'] ?? '';

// Fetch doctor details from the database
$sql = "SELECT doctor_name, doctor_email, doctor_phone FROM doctors WHERE doctor_email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$doctor = $result->fetch_assoc();

$fullname = $doctor['doctor_name'] ?? '';
$phoneNumber = $doctor['doctor_phone'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Doctor Settings</title>
    <link rel="stylesheet" href="../css/doc_dashboard.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/settings_modal.css">
</head>
<body>
<header>
    <a href="#" class="logo"><span>O</span>ne<span>H</span>ealth</a>
    <div class="date-time" id="datetime"></div>
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
        <div class="menu-item">
            <div class="icon">🏠</div>
            <a href="index.php">Home</a>
        </div>
        <div class="menu-item active">
            <div class="icon">⚙️</div>
            <a href="#">Settings</a>
        </div>
        <div class="menu-item logout">
            <div class="icon">🔒</div>
            <a href="../logout.php">Logout</a>
        </div>
    </div>
    <div class="main">
        <h2>Doctor Settings</h2>
        <div class="setting-card" onclick="openModal('editModal')">
            <div class="icon">🛠️</div>
            <div>
                <h3>Account Settings</h3>
                <p>Edit your Account Details & Change Password</p>
            </div>
        </div>
        <div class="setting-card" onclick="openModal('viewModal')">
            <div class="icon">👁️</div>
            <div>
                <h3>View Account Details</h3>
                <p>View Your Personal Information</p>
            </div>
        </div>
        <div class="setting-card delete" onclick="openModal('deleteModal')">
            <div class="icon">🗑️</div>
            <div>
                <h3>Delete Account</h3>
                <p>Permanently Delete your Account</p>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('editModal')">&times;</span>
    <h2>Edit Account</h2>
    <form id="editForm" method="POST" action="update_doctor.php">
        <label>Full Name</label>
        <input type="text" name="fullname" value="<?= htmlspecialchars($fullname) ?>" required>
        <label>Phone</label>
        <input type="text" name="phoneNumber" value="<?= htmlspecialchars($phoneNumber) ?>" required>
        <label>New Password (leave blank to keep current)</label>
        <input type="password" name="password">
        <button type="submit">Update</button>
    </form>
    <div id="editMsg"></div>
  </div>
</div>

<!-- View Modal -->
<div id="viewModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('viewModal')">&times;</span>
    <h2>Your Account</h2>
    <p><strong>Name:</strong> <?= htmlspecialchars($fullname) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
    <p><strong>Phone:</strong> <?= htmlspecialchars($phoneNumber) ?></p>
  </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('deleteModal')">&times;</span>
    <h2>Confirm Account Deletion</h2>
    <p>Are you sure you want to delete your account? This action is irreversible.</p>
    <button onclick="deleteAccount()" style="background:red; color:white;">Delete</button>
    <div id="deleteMsg"></div>
  </div>
</div>

<script src="../js/settings.js"></script>
</body>
</html>
