<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // If not logged in, redirect to the login page
    echo "<p>You must log in first</p>";
    exit();
}
?>