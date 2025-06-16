<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "onehealth_db";

// connecting to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// to check the connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

// Get the 'category' parameter from the URL query string
$category = $_GET['category'];

// Check if the category parameter is empty
if (empty($category)) {
    // Return a JSON error response if no category was provided
    echo json_encode(['error' => 'No category provided']);
    exit; // Stop script execution
}

// Prepare SQL query to get advice for the specified heart rate category
$sql = "SELECT advice FROM heart_rate WHERE category = ?";
$stmt = $conn->prepare($sql); // Prepare the statement to prevent SQL injection

// Bind the category parameter to the prepared statement
$stmt->bind_param("s", $category); // "s" indicates the parameter is a string

// Execute the prepared statement
$stmt->execute();

// Bind the result variable to store the advice
$stmt->bind_result($advice);

// Fetch the result (gets the first matching row)
$stmt->fetch();

// Output the advice directly (not as JSON)
echo $advice;

// Clean up by closing the statement and database connection
$stmt->close();
$conn->close();