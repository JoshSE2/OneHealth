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

// Ensure user is logged in and is a patient
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'patient') {
    header("Location: ../login.php");
    exit();
}

// Get user data
$patient_email = $_SESSION['email'];
$fullname = $_SESSION['fullname'];

// Fetch phone number
$phoneNumber = '';
$sql = "SELECT phoneNumber FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $patient_email);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $phoneNumber = $row['phoneNumber'];
}
$stmt->close();

// Fetch doctors
$doctors = [];
$doc_query = "SELECT doctor_name, doctor_email FROM doctors";
$result = $conn->query($doc_query);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $doctors[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
    <link rel="stylesheet" href="../css/patient_dashboard.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/book_app.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
                <p><?= htmlspecialchars($patient_email) ?></p>
            </div>
        </div>

        <div class="menu-item">
            <div class="icon">🏠</div>
            <a href="index.php">Home</a>
        </div>
        <div class="menu-item active">
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
            <a href="../logout.php">Logout</a>
        </div>
    </div>

    <div class="app-container">
        <h2>Book Your Appointment</h2>
        <form action="submit_appointment.php" method="POST">
            <label for="patient_name">Full Name:</label>
            <input type="text" id="patient_name" name="patient_name" value="<?= htmlspecialchars($fullname) ?>"/>

            <label for="phone">Phone number:</label>
            <input type="tel" name="phone" value="<?= htmlspecialchars($phoneNumber) ?>" readonly>

            <label for="doctor_email">Select Doctor:</label>
            <select name="doctor_email" required>
                <option value=""> Select Doctor </option>
                <?php foreach ($doctors as $doc): ?>
                    <option value="<?= htmlspecialchars($doc['doctor_email']) ?>">
                        <?= htmlspecialchars($doc['doctor_name']) ?> (<?= htmlspecialchars($doc['doctor_email']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="date">Appointment Date:</label>
            <input type="date" id="date" name="appointment_date" required>

            <label for="time">Appointment Time:</label>
            <input type="text" id="timepicker" name="appointment_time" placeholder="Select Time" required>

            <label for="message">Describe Your Problem:</label>
            <textarea name="message" rows="4" cols="40" required></textarea>

            <button type="submit">Book Appointment</button>
        </form>
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

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr("#timepicker", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: false,
        minuteIncrement: 30,
        minTime: "07:00",
        maxTime: "19:00"
    });
</script>

</body>
</html>
