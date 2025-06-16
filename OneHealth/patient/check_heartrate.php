<?php
    include_once '../db_connect.php';

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

    // to make sure the user is logged in and is the only one accessing the page
    if (!isset ($_SESSION['email']) || $_SESSION['role'] !== 'patient') {
        header("Location: ../login.php");
        exit();
    }

    // check for patient email from the session
    $patient_email = $_SESSION['email'];
    $fullname = $_SESSION['fullname'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Basic meta tags for character set and responsive viewport -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Heart Rate</title>
    <!-- CSS stylesheets -->
    <link rel="stylesheet" href="../css/heartrate.css">       <!-- Main heart rate page styles -->
    <link rel="stylesheet" href="../css/footer.css">         <!-- Footer styles -->
    <link rel="stylesheet" href="../css/dashboard.css">      <!-- Dashboard layout styles -->
</head>
<body>
    <!-- Header section with logo, date/time display, and logout button -->
    <header>
        <a href="#" class="logo"><span>O</span>ne<span>H</span>ealth</a>
        <a href="../logout.php" class="account-btn">Logout</a>
    </header>

    <!-- Main dashboard container with sidebar and content -->
    <div class="dashboard-cont">
        <!-- Sidebar navigation -->
        <div class="sidebar">
            <!-- User profile section -->
            <div class="user-info">
                <div class="avatar"></div>  <!-- User avatar placeholder -->
                <div class="user-details">
                    <h3><?= htmlspecialchars($fullname)?></h3>       <!-- Display user's full name -->
                    <p><?= htmlspecialchars($patient_email)?></p>    <!-- Display user's email -->
                </div>
            </div>

            <!-- Navigation menu items -->
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
            <div class="menu-item active">  <!-- Currently active page -->
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

        <!-- Main content area for heart rate checker -->
        <div class="main-hr-content">
            <!-- Instructional steps section -->
            <section class="hr-steps">
                <h4 class="subheading">HOW THE HEART-RATE CHECKER WORKS</h4>
                <h1 class="heading1">Steps Needed To Check Your Heart Rate</h1>
                <div class="steps-container">
                    <!-- Step 1: Measure Heart Rate -->
                    <div class="steps">
                        <img src="../imgs/step1-icon.png" alt="Step 1" class="step-icon">
                        <h3>Measure Heart-Rate</h3>
                        <p>Use a heart rate monitor or manually check your pulse.</p>
                    </div>
                    <div class="arrow">→</div>  <!-- Visual separator -->

                    <!-- Step 2: Insert Heart Rate -->
                    <div class="steps">
                        <img src="../imgs/step2-icon.png" alt="Step 2" class="step-icon">
                        <h3>Insert Heart-Rate</h3>
                        <p>Enter the measured number of beats per minute (bpm) in the input field.</p>
                    </div>
                    <div class="arrow">→</div>  <!-- Visual separator -->

                    <!-- Step 3: Check Results -->
                    <div class="steps">
                        <img src="../imgs/step3-icon.png" alt="Step 3" class="step-icon">
                        <h3>Check Your Results</h3>
                        <p>Read the results carefully and take action based on the solution provided by our doctors.</p>
                    </div>
                </div>
            </section>

            <!-- Heart rate input and results section -->
            <div class="hr-container">
                <h2>Heart Rate Checker</h2>
                <!-- Input field for heart rate -->
                <input type="number" id="heartRate" placeholder="Enter your heart rate (bpm)">
                <!-- Button to trigger heart rate check -->
                <button onclick="checkHeartRate()">Check</button>
                
                <!-- Display area for entered rate -->
                <div class="entered-rate" id="enteredRate"></div>
                
                <!-- Visual meter to show heart rate level -->
                <div class="meter">
                    <div class="meter-fill" id="meterFill"></div>
                </div>
                
                <!-- Result display showing heart rate category -->
                <div class="result" id="result"></div>
                
                <!-- Medical advice display area -->
                <div class="advice" id="advice"></div>
            </div>
        </div>
    </div>
    
    <!-- Overlay element (could be used for modals) -->
    <div class="overlay"></div>
    
    <!-- Footer section -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <!-- Company information -->
                <div class="footer-section">
                    <h3>OneHealth</h3>
                    <p>Making healthcare accessible and convenient for everyone.</p>
                </div>
                <!-- Services list -->
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
                <!-- Contact information -->
                <div class="footer-section">
                    <h3>Contact</h3>
                    <p>Email: support@onehealth.com</p>
                    <p>Phone: (+44) 7726779086</p>
                </div>
            </div>
            <!-- Copyright notice with dynamic year -->
            <div class="footer-bottom">
                <p>&copy; <script>document.write(new Date().getFullYear())</script> OneHealth. All rights reserved.</p>
            </div>
        </div>
    </footer>

<!-- JavaScript for heart rate checking functionality -->
<script>
    function checkHeartRate() {
        // Get input value and convert to integer
        const heartRate = parseInt(document.getElementById('heartRate').value);
        
        // Get DOM elements that will display results
        const meterFill = document.getElementById('meterFill');
        const result = document.getElementById('result');
        const enteredRate = document.getElementById('enteredRate');
        const adviceDiv = document.getElementById('advice');

        // Validate input (must be number between 0-220)
        if (isNaN(heartRate) || heartRate < 0 || heartRate > 220) {
            result.textContent = 'Please enter a valid heart rate (0-220 bpm)';
            result.style.color = 'purple';
            meterFill.style.width = '0%';
            enteredRate.textContent = '';
            adviceDiv.textContent = '';
            return;  // Exit function if invalid input
        }

        // Calculate percentage for meter visualization (220 bpm is max)
        const filledPercentage = (heartRate / 220) * 100;
        meterFill.style.width = filledPercentage + "%";
        
        // Display the entered rate
        enteredRate.textContent = `You entered: ${heartRate} bpm`;

        let category;  // Will store heart rate category
        let color;     // Will store color for result display

        // Determine heart rate category and corresponding color
        if (heartRate < 40) {
            category = "extremely low";
            color = "red";
        } else if (heartRate < 60) {
            category = "very low";
            color = "red";
        } else if (heartRate < 70) {
            category = "low";
            color = "rgb(202, 115, 9)";  // Orange
        } else if (heartRate < 80) {
            category = "slightly low";
            color = "orange";
        } else if (heartRate < 100) {
            category = "normal";
            color = "green";
        } else if (heartRate < 120) {
            category = "slightly high";
            color = "blue";
        } else if (heartRate < 140) {
            category = "high";
            color = "violet";
        } else if (heartRate < 160) {
            category = "very high";
            color = "purple";
        } else if (heartRate >= 160) {
            category = "extremely high";
            color = "indigo";
        }

        // Display the category result with appropriate color
        result.textContent = `Your heart rate is ${category}!`;
        result.style.color = color;

        // Fetch medical advice from server based on category
        fetch(`hr_advice.php?category=${category}`)
            .then(response => response.text())  // Get text response
            .then(advice => {
                adviceDiv.textContent = advice;  // Display the advice
            })
            .catch(error => {
                console.error('Error fetching advice:', error);
                adviceDiv.textContent = 'Could not fetch advice, try again.';
            });
    }
</script>
</body>
</html>