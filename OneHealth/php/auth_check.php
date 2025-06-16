<?php
session_start();
if (isset($_SESSION['Logged_in']) && $_SESSION['Logged_in'] === true) {
    // User is logged in, redirect to the home page
    header('Location: ../Pages/login.php');
    exit();
}