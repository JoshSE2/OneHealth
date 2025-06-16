<?php
// Include database connection
include_once '../Database/db_connect.php';

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

ini_set('session.gc_maxlifetime', $sessiion_lifetime);
session_start();

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$admin_email = $_SESSION['email'];
$admin_name = $_SESSION['fullname'];

// Fetch all appointments
$sql = "SELECT a.*, u.fullname AS patient_name, d.doctor_name
        FROM booked_appointments a
        JOIN users u ON a.patient_email = u.email
        JOIN doctors d ON a.doctor_email = d.doctor_email
        ORDER BY a.appointment_date DESC, a.appointment_time DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Appointments - Admin</title>
    <link rel="stylesheet" href="../css/patient_dashboard.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <style>
        .app-container {
            padding: 20px;
            width: 100%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1.5rem;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: rgb(6, 86, 113);
            color: white;
            text-align: center;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .status {
    font-weight: bold;
    padding: 6px 12px;
    border-radius: 20px;
    display: inline-block;
    text-align: center;
    min-width: 90px;
}

.status.Approved {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.status.Cancelled {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.status.Pending {
    background-color: #fff3cd;
    color: #856404;
    border: 1px solid #ffeeba;
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
                <h3><?= htmlspecialchars($admin_name) ?></h3>
                <p><?= htmlspecialchars($admin_email) ?></p>
            </div>
        </div>

        <div class="menu-item">
            <div class="icon">🏠</div>
            <a href="index.php">Dashboard</a>
        </div>
        <div class="menu-item active">
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

    <div class="app-container">
        <h2>All Booked Appointments</h2>

        <table>
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Doctor</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Issue</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['patient_name']) ?></td>
                        <td><?= htmlspecialchars($row['patient_email']) ?></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                        <td><?= htmlspecialchars($row['appointment_date']) ?></td>
                        <td><?= htmlspecialchars($row['appointment_time']) ?></td>
                        <td><?= htmlspecialchars($row['message']) ?></td>
                        <td><span class="status <?= $row['status'] ?>"><?= $row['status'] ?></span></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="8">No appointments found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="overlay"></div>
<footer>
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3>OneHealth</h3>
                <p>Making healthcare accessible and convenient for everyone.</p>
            </div>
            <div class="footer-section">
                <h3>Our Services</h3>
                <ul>
                    <li>Check Your Symptoms</li>
                    <li>Check Heart Rate</li>
                    <li>Book Appointments</li>
                    <li>Consult with Doctors</li>
                    <li>Get Prescriptions</li>
                    <li>Health Tips</li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact</h3>
                <p>Email: support@onehealth.com</p>
                <p>Phone: (+44) 7726779086</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <script>document.write(new Date().getFullYear())</script> OneHealth. All rights reserved.</p>
        </div>
    </div>
</footer>

</body>
</html>
