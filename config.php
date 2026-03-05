<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'hooria_residency');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Session start for admin panel
session_start();

// Admin credentials (Change these for production)
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', 'hr247@pass'); // Using a placeholder, user should change this
?>
