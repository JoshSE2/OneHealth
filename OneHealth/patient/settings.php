<?php
session_start();
include_once '../Database/db_connect.php';

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'patient') {
    header("Location: ../login.php");
    exit();
}

$email = $_SESSION['email'];
$query = "SELECT * FROM users WHERE email = ? AND role = 'patient'";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$fullname = $user['fullname'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient Settings</title>
    <link rel="stylesheet" href="../css/doc_dashboard.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/settings_modal.css">
    
    <style>
        /* Settings Container Styling */
.settings-container {
    max-width: 800px;
    margin: 40px auto;
    padding: 0 20px;
}

.settings-card {
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    padding: 40px;
}

/* Header Styling */
.settings-card h2 {
    color: #2c3e50;
    font-size: 24px;
    margin-bottom: 30px;
    font-weight: 600;
}

/* Button Container Styling */
.settings-card .btn-container {
    display: flex;
    flex-direction: column;
    gap: 30px;
}

/* Button Group Styling */
.btn-group {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.btn-group h3 {
    color: #2c3e50;
    font-size: 18px;
    margin-bottom: 8px;
    font-weight: 500;
}

.btn-group p {
    color: #7f8c8d;
    margin-bottom: 15px;
    font-size: 15px;
}

/* Button Styling */
.btn {
    display: inline-block;
    padding: 12px 24px;
    border-radius: 6px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
    border: none;
    font-size: 15px;
    width: fit-content;
}

.btn-view {
    background-color: #3498db;
    color: white;
}

.btn-view:hover {
    background-color: #2980b9;
}

.btn-edit {
    background-color: #2ecc71;
    color: white;
}

.btn-edit:hover {
    background-color: #27ae60;
}

.btn-delete {
    background-color: #e74c3c;
    color: white;
}

.btn-delete:hover {
    background-color: #c0392b;
}

/* Divider Styling */
.divider {
    height: 1px;
    background-color: #ecf0f1;
    margin: 25px 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .settings-container {
        padding: 0 15px;
    }
    
    .settings-card {
        padding: 25px;
    }
    
    .btn {
        width: 100%;
    }
}
    </style>
    
</head>
<body>

<div class="dashboard-cont">

    <!-- Sidebar menu -->
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
        <div class="menu-item">
            <div class="icon">📅</div>
            <a href="booking.php">Book Appointment</a>
        </div>
        <div class="menu-item">
            <div class="icon">🩺</div>
            <a href="symptoms.php">Check Symptoms</a>
        </div>
        <div class="menu-item">
            <div class="icon">📊</div>
            <a href="check_heartrate.php">Heart Rate Checker</a>
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

    <!-- Settings Content -->
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

<!-- View Modal -->
<div id="viewModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('viewModal')">&times;</span>
        <h3>View Patient Details</h3>
        <p><strong>Full Name:</strong> <?= htmlspecialchars($user['fullname']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Phone Number:</strong> <?= htmlspecialchars($user['phoneNumber']) ?></p>
        <p><strong>Role:</strong> <?= htmlspecialchars($user['role']) ?></p>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('editModal')">&times;</span>
        <h3>Edit Patient Details</h3>
        <form action="update_patient.php" method="POST">
            <label for="fullname">Full Name</label>
            <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname']) ?>" required>

            <label for="phoneNumber">Phone Number</label>
            <input type="text" name="phoneNumber" value="<?= htmlspecialchars($user['phoneNumber']) ?>" required>

            <label for="password">New Password (leave blank to keep current)</label>
            <input type="password" name="password">

            <button type="submit" class="btn btn-edit">Save Changes</button>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('deleteModal')">&times;</span>
        <h3>Confirm Account Deletion</h3>
        <p>Are you sure you want to delete your account? This action cannot be undone.</p>
        <form action="delete_patient.php" method="POST">
            <button type="submit" class="btn btn-delete">Yes, Delete My Account</button>
        </form>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).style.display = 'block';
    }

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }

    window.onclick = function(event) {
        ['viewModal', 'editModal', 'deleteModal'].forEach(function(id) {
            if (event.target === document.getElementById(id)) {
                document.getElementById(id).style.display = "none";
            }
        });
    };
</script>

</body>
</html>
