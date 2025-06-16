<?php
// Database connection configuration
$host = 'localhost';         // Database server
$username = 'root';          // Database username
$password = '';              // Database password (empty in this case)
$dbname = 'onehealth_db';    // Database name

// Create database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection and terminate if failed
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Error reporting configuration (for debugging)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Session configuration for security
$session_lifetime = 3600; // 1 hour session duration
session_set_cookie_params([
    'lifetime' => $session_lifetime,
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => true,     // Only send cookie over HTTPS
    'httponly' => true,   // Prevent JavaScript access to cookie
    'samesite' => 'Strict' // Prevent CSRF attacks
]);

// Set server-side session garbage collection lifetime
ini_set('session.gc_maxlifetime', $session_lifetime);
session_start(); // Start the session

// Check if user is logged in as patient
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'patient') {
    header("Location: ../login.php");
    exit();
}

// Set character encoding for database connection
$conn->set_charset("utf8");

// Get patient details from session
$patient_email = $_SESSION['email'];
$fullname = $_SESSION['fullname'];

// Fetch symptoms from database
$symptoms = [];
$query = "SELECT id, symptom_name FROM symptoms ORDER BY symptom_name";
$result = $conn->query($query);

// Process symptom query results
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $symptoms[] = $row; // Add each symptom to array
    }
} else {
    $error = "No symptoms found in the database.";
}

$conn->close(); // Close database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Symptoms Checker | OneHealth</title>
    <!-- CSS Stylesheets -->
    <link rel="stylesheet" href="../css/symptom.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/dashboard.css">
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
                    <h3><?= htmlspecialchars($fullname)?></h3>
                    <p><?= htmlspecialchars($patient_email)?></p>
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
            <div class="menu-item active">
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
                <a href="/logout.php">Logout</a>
            </div>
        </div>

        <!-- Main content area -->
        <div class="main-content">
            <h1>Symptoms Checker</h1>

            <!-- Tab navigation -->
            <div class="tabs">
                <div class="tab active" data-tab="checker">Symptoms Checker</div>
                <div class="tab" data-tab="history">Symptoms History</div>
            </div>

            <!-- Symptoms Checker Tab Content -->
            <div class="tab-content active" id="checker-tab">
                <h2>Select Your Symptoms</h2>
                <p>If your symptoms are not found below, contact us to speak to a doctor. Otherwise, click on the symptoms you're experiencing:</p>

                <!-- Symptoms selection container -->
                <div class="symptom-container">
                    <?php if (!empty($symptoms)): ?>
                        <!-- Display each symptom as selectable item -->
                        <?php foreach ($symptoms as $symptom): ?>
                            <div class="symptom-item" data-id="<?= htmlspecialchars($symptom['id']) ?>">
                                <?= htmlspecialchars($symptom['symptom_name']) ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Error message if no symptoms found -->
                        <p><?= isset($error) ? htmlspecialchars($error) : "Something went wrong." ?></p>
                    <?php endif; ?>
                </div>

                <!-- Action buttons -->
                <div class="action-buttons">
                    <button id="get-solution-btn" class="action-button" disabled>Get Solution</button>
                    <button id="clear-symptoms-btn" class="action-button">Clear Selection</button>
                </div>

                <!-- Solution display area (initially hidden) -->
                <div class="solution-container" id="solution-result">
                    <h3>Recommended Solution</h3>
                    <div id="solution-text"></div>
                    <div class="action-buttons">
                        <button id="save-history-btn" class="action-button">Save to History</button>
                    </div>
                </div>
            </div>

            <!-- Symptoms History Tab Content -->
            <div class="tab-content" id="history-tab">
                <h2>Your Symptoms History</h2>
                <div id="history-container"></div>
            </div>
        </div>
    </div>
    
    <!-- Overlay element (for modal effects) -->
    <div class="overlay"></div>
    
    <!-- Footer section -->
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

<!-- JavaScript for interactive functionality -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize variables
    let selectedSymptoms = [];
    const getSolutionBtn = document.getElementById('get-solution-btn');
    const saveBtn = document.getElementById('save-history-btn');
    const clearBtn = document.getElementById('clear-symptoms-btn');
    const solutionResult = document.getElementById('solution-result');
    const solutionText = document.getElementById('solution-text');
    const symptomContainer = document.querySelector('.symptom-container');

    // Handle symptom selection using event delegation
    symptomContainer.addEventListener('click', function (e) {
        const item = e.target.closest('.symptom-item');
        if (!item) return;

        const id = item.dataset.id;
        const name = item.textContent.trim();
        item.classList.toggle('selected');
    
        // Update selected symptoms array
        const isSelected = item.classList.contains('selected');
        if (isSelected) {
            selectedSymptoms.push({ id, name });
        } else {
            selectedSymptoms = selectedSymptoms.filter(s => s.id !== id);
        }

        // Update UI based on selection
        getSolutionBtn.disabled = selectedSymptoms.length === 0;
        solutionResult.style.display = 'none';
    });

    // Handle tab switching
    document.querySelectorAll('.tab').forEach(tab => {
        tab.addEventListener('click', function () {
            // Update active tab and content
            document.querySelectorAll('.tab, .tab-content').forEach(el => el.classList.remove('active'));
            this.classList.add('active');
            document.getElementById(this.dataset.tab + '-tab').classList.add('active');
            
            // Load history if history tab selected
            if (this.dataset.tab === 'history') loadSymptomHistory();
        });
    });

    // Get solution button click handler
    getSolutionBtn.addEventListener('click', function () {
        const ids = selectedSymptoms.map(s => s.id).join(',');
        
        // Fetch solution from server
        fetch('get_solution.php?symptom_ids=' + ids)
            .then(res => res.json())
            .then(data => {
                solutionText.innerHTML = data.solution || 'No solution found.';
                solutionResult.style.display = 'block';
            })
            .catch(err => {
                console.error(err);
                solutionText.innerHTML = 'An error occurred.';
                solutionResult.style.display = 'block';
            });
    });

    // Clear symptoms button click handler
    clearBtn.addEventListener('click', function () {
        selectedSymptoms = [];
        document.querySelectorAll('.symptom-item').forEach(i => i.classList.remove('selected'));
        getSolutionBtn.disabled = true;
        solutionResult.style.display = 'none';
        solutionText.innerHTML = '';
    });

    // Save to history button click handler
    saveBtn.addEventListener('click', function () {
        // Validate selection
        if (selectedSymptoms.length === 0 || !solutionText.innerHTML.trim()) {
            alert('Please select symptoms and get a solution first.');
            return;
        }

        // Prepare form data
        const formData = new FormData();
        formData.append('symptom_name', selectedSymptoms.map(s => s.name).join(', '));
        formData.append('solution', solutionText.innerHTML);

        // Send data to server
        fetch('symptom_history.php', {
            method: 'POST',
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                alert(data.success ? 'Saved!' : 'Error: ' + data.message);
                loadSymptomHistory();
            })
            .catch(err => {
                console.error(err);
                alert('Failed to save history.');
            });
    });

    // Function to load symptom history
    function loadSymptomHistory() {
        fetch('get_history.php')
            .then(res => res.json())
            .then(data => {
                const container = document.getElementById('history-container');
                
                // Handle empty history
                if (!data || data.length === 0) {
                    container.innerHTML = '<p>No history found.</p>';
                    return;
                }

                // Display history items
                container.innerHTML = data.map(item => `
                    <div class="history-item">
                        <div class="history-date">${item.created_at}</div>
                        <div class="history-symptoms"><strong>Symptoms:</strong> ${item.symptoms}</div>
                        <div class="history-solution"><strong>Solution:</strong> ${item.solution}</div>
                    </div>
                `).join('');
            })
            .catch(err => {
                console.error(err);
                document.getElementById('history-container').innerHTML =
                    '<p>Error loading history. Please try again later.</p>';
            });
    }
});
</script>
</body>
</html>