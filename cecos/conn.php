<?php
// Database configuration
$host = 'localhost';
$dbname = 'cecos_lms';
$db_username = 'root'; // Replace with your database username
$db_password = ''; // Replace with your database password

// Create connection
$conn = new mysqli($host, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
