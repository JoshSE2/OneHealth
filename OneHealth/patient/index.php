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

// Verify user is logged in and is a patient
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'patient') {
    header("Location: ../login.php");
    exit();
}

// Get patient session data
$patient_email = $_SESSION['email'];
$fullname = $_SESSION['fullname'];

// Fetch patient's appointments with doctor information
$stmt = $conn->prepare("SELECT ba.*, d.doctor_name
                        FROM booked_appointments ba
                        LEFT JOIN doctors d ON ba.doctor_email = d.doctor_email
                        WHERE ba.patient_email = ?
                        ORDER BY ba.appointment_date DESC");
$stmt->bind_param("s", $patient_email);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard</title>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/user.css">
</head>
<body>

<header>
    <a href="#" class="logo"><span>O</span>ne<span>H</span>ealth</a>
    <a href="./logout.php" class="account-btn">Logout</a>
</header>

<div class="dashboard-cont">
    <div class="sidebar">
        <div class="user-info">
            <div class="avatar"></div>
            <div class="user-details">
                <h3><?= htmlspecialchars($fullname) ?></h3>
                <p><?= htmlspecialchars($patient_email) ?></p>
            </div>
        </div>

        <div class="menu-item active">
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
        <div class="menu-item">
            <div class="icon">⚙️</div>
            <a href="settings.php">Settings</a>
        </div>
        <div class="menu-item logout">
            <div class="icon">🔒</div>
            <a href="./logout.php">Logout</a>
        </div>
    </div>

    <div class="main-content">
        <div class="dashboard-header">
            <h1>Welcome, <?= htmlspecialchars($fullname); ?></h1>
            <p>Your health is our priority. Below is your appointment history and updates.</p>
        </div>

        <div class="appointments-section">
            <h2>My Appointments</h2>
            <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Doctor</th>
                    <th>Phone</th>
                    <th>Message</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['appointment_date']); ?></td>
                            <td><?= htmlspecialchars($row['appointment_time']); ?></td>
                            <td><?= htmlspecialchars($row['doctor_name'] ?? 'Not specified'); ?></td>
                            <td><?= htmlspecialchars($row['phone']); ?></td>
                            <td><?= htmlspecialchars($row['message']); ?></td>
                            <td>
                                <span class="badge <?= strtolower($row['status']); ?>">
                                    <?= ucfirst($row['status']); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" style="text-align: center;">You have no booked appointments yet.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
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