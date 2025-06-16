<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "onehealth_db";
// to create a connection to the database
$conn = new mysqli($host, $username, $password, $dbname);
// to check connection and display error message
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}