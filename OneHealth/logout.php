<?php
session_start();
include_once '../Database/db_connect.php';
// Unset all session variables
session_unset();
// Destroy the session
session_destroy();
// Redirect to the login page
header("Location: login.php");
exit();
