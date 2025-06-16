<?php
// Include database connection file
include_once '../Database/db_connect.php';

// Display errors for debugging (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configure session settings for security
$session_lifetime = 3600; // 1 hour
session_set_cookie_params([
    'lifetime' => $session_lifetime,
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => true,    // Only send over HTTPS
    'httponly' => true,  // Prevent JavaScript access
    'samesite' => 'Strict' // Prevent CSRF attacks
]);

// Set server-side session duration
ini_set('session.gc_maxlifetime', $session_lifetime);
session_start();

// Check if user is logged in and has doctor role
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'doctor') {
    header("Location: ../login.php");
    exit();
}

// Get doctor details from session
$doctor_email = $_SESSION['email'];
$fullname = $_SESSION['fullname'];

// Fetch doctor name from database
$sql = "SELECT doctor_name FROM doctors WHERE doctor_email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $doctor_email);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor</title>
    <!-- Include CSS files -->
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/doc_dashboard.css">
</head>
<body>
<!-- Header section with logo and logout button -->
<header>
    <a href="#" class="logo"><span>O</span>ne<span>H</span>ealth</a>
    <a href="../logout.php" class="account-btn">Logout</a>
</header>

<!-- Main dashboard container -->
<div class="dashboard-cont">
    <!-- Sidebar navigation -->
    <div class="sidebar">
        <!-- User profile section -->
        <div class="user-info">
            <div class="avatar"></div>
            <div class="user-details">
                <h3><?= htmlspecialchars($fullname) ?></h3>
                <p><?= htmlspecialchars($doctor_email) ?></p>
            </div>
        </div>
        <!-- Navigation menu items -->
        <div class="menu-item active">
            <div class="icon">🏠</div>
            <a href="index.php">Home</a>
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

    <!-- Main content area -->
    <div class="doc-content">
        <section id="doc-section">
            <div class="doc-overview">
                <h1>Doctor Overview</h1>
                <p>Welcome to your dashboard, <?= htmlspecialchars($fullname) ?>! Here you can manage your appointments and patient interactions.</p>

                <?php
                // Include database connection again (redundant but ensures connection)
                include_once '../Database/db_connect.php';

                // SQL queries to get appointment statistics
                $total_query = "SELECT COUNT(*) as total FROM booked_appointments WHERE doctor_email = ?";
                $approved_query = "SELECT COUNT(*) as approved FROM booked_appointments WHERE doctor_email = ? AND status = 'approved'";
                $pending_query = "SELECT COUNT(*) as pending FROM booked_appointments WHERE doctor_email = ? AND (status = 'pending' OR status IS NULL)";
                $cancelled_query = "SELECT COUNT(*) as cancelled FROM booked_appointments WHERE doctor_email = ? AND status = 'cancelled'";

                // Execute each query to get appointment counts
                $stmt = $conn->prepare($total_query);
                $stmt->bind_param("s", $doctor_email);
                $stmt->execute();
                $total_appointments = $stmt->get_result()->fetch_assoc()['total'];

                $stmt = $conn->prepare($approved_query);
                $stmt->bind_param("s", $doctor_email);
                $stmt->execute();
                $approved_appointments = $stmt->get_result()->fetch_assoc()['approved'];

                $stmt = $conn->prepare($pending_query);
                $stmt->bind_param("s", $doctor_email);
                $stmt->execute();
                $pending_appointments = $stmt->get_result()->fetch_assoc()['pending'];

                $stmt = $conn->prepare($cancelled_query);
                $stmt->bind_param("s", $doctor_email);
                $stmt->execute();
                $cancelled_appointments = $stmt->get_result()->fetch_assoc()['cancelled'];
                ?>

                <!-- Statistics cards showing appointment counts -->
                <div class="doc-cards">
                    <div class="stat-card">
                        <h2>Booked Appointments</h2>
                        <p><?= $total_appointments ?></p>
                    </div>
                    <div class="stat-card">
                        <h2>Approved Appointments</h2>
                        <p><?= $approved_appointments ?></p>
                    </div>
                    <div class="stat-card">
                        <h2>Pending Appointments</h2>
                        <p><?= $pending_appointments ?></p>
                    </div>
                    <div class="stat-card">
                        <h2>Cancelled</h2>
                        <p><?= $cancelled_appointments ?></p>
                    </div>
                </div>

                <!-- Pending Appointments section -->
                <h2 class="section-heading">Pending Appointments</h2>
                <div class="doc-appointments">
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Patient Name</th>
                                <th>Phone</th>
                                <th>Message</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        // Query to get pending appointments
                        $sql = "SELECT id, appointment_date, appointment_time, patient_name, phone, message
                                FROM booked_appointments
                                WHERE doctor_email = ? AND (status = 'pending' OR status IS NULL)
                                ORDER BY appointment_date, appointment_time";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("s", $doctor_email);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        // Display pending appointments with action buttons
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$row['appointment_date']}</td>
                                        <td>{$row['appointment_time']}</td>
                                        <td>" . htmlspecialchars($row['patient_name']) . "</td>
                                        <td>" . htmlspecialchars($row['phone']) . "</td>
                                        <td>" . nl2br(htmlspecialchars($row['message'])) . "</td>
                                        <td class='action-buttons'>
                                            <form action='appointment_status.php' method='POST'>
                                                <input type='hidden' name='id' value='{$row['id']}'>
                                                <button type='submit' name='action' value='approve' class='approve-btn'>Approve</button>
                                            </form>
                                            <form action='appointment_status.php' method='POST'>
                                                <input type='hidden' name='id' value='{$row['id']}'>
                                                <button type='submit' name='action' value='cancel' class='cancel-btn'>Cancel</button>
                                            </form>
                                        </td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No pending appointments.</td></tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>

                <!-- Upcoming Appointments section -->
                <h2 class="section-heading">All Upcoming Appointments</h2>
                <div class="doc-appointments">
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Patient Name</th>
                                <th>Phone</th>
                                <th>Message</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        // Query to get all upcoming (non-cancelled) appointments
                        $sql = "SELECT id, appointment_date, appointment_time, patient_name, phone, message, status
                                FROM booked_appointments
                                WHERE doctor_email = ? AND status != 'cancelled' AND appointment_date >= CURDATE()
                                ORDER BY appointment_date, appointment_time";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("s", $doctor_email);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        // Display upcoming appointments with status badges
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $status = ($row["status"] === 'approved') ? 'approved' : 'pending';
                                $status_class = ($status === 'approved') ? 'status-approved' : 'status-pending';

                                echo "<tr>
                                        <td>{$row['appointment_date']}</td>
                                        <td>{$row['appointment_time']}</td>
                                        <td>" . htmlspecialchars($row['patient_name']) . "</td>
                                        <td>" . htmlspecialchars($row['phone']) . "</td>
                                        <td>" . nl2br(htmlspecialchars($row['message'])) . "</td>
                                        <td><span class='status-badge {$status_class}'>" . ucfirst($status) . "</span></td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No upcoming appointments.</td></tr>";
                        }

                        // Close database connections
                        $stmt->close();
                        $conn->close();
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>
</body>
</html>