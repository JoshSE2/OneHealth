<?php
// get_solution.php - Handles retrieving solutions for selected symptoms

// Connect to database
$conn = new mysqli("localhost", "root", "", "onehealth_db");

// Check connection
if ($conn->connect_error) {
    die(json_encode(['error' => "Connection failed: " . $conn->connect_error]));
}

// Get symptom IDs from request
$symptomIds = isset($_GET['symptom_ids']) ? $_GET['symptom_ids'] : '';

if (empty($symptomIds)) {
    echo json_encode(['error' => 'No symptoms selected']);
    exit;
}

// Split symptom IDs
$ids = explode(',', $symptomIds);

// Prepare query to get solutions for selected symptoms
$placeholders = str_repeat('?,', count($ids) - 1) . '?';
$sql = "SELECT symptom_name, solution FROM symptoms WHERE id IN ($placeholders)";

// Prepare statement
$stmt = $conn->prepare($sql);

// Bind parameters dynamically
$types = str_repeat('i', count($ids)); // All IDs are integers
$stmt->bind_param($types, ...$ids);

// Execute query
$stmt->execute();
$result = $stmt->get_result();

// Combine solutions, for when multiple symptoms are selected
$solutions = [];
$symptomNames = [];

while ($row = $result->fetch_assoc()) {
    $symptomNames[] = $row['symptom_name'];
    $solutions[] = "<strong>{$row['symptom_name']}:</strong> {$row['solution']}";
}

// Close database connection after fetching results
$stmt->close();
$conn->close();

// Return solution
echo json_encode([
    'symptoms' => implode(', ', $symptomNames),
    'solution' => implode('<br><br>', $solutions)
]);
?>