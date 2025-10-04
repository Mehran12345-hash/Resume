<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "portfolio"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = md5($conn->real_escape_string($_POST['password'])); // Hash the password with MD5

    // Query to check admin credentials
    $sql = "SELECT * FROM admin WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Login successful
        $_SESSION['admin_logged_in'] = true;
        header("Location:admin/dashboard.php"); // Redirect to the dashboard
        exit();
    } else {
        // Login failed
        echo "<script>alert('Invalid email or password.'); window.location.href='admin-login.php';</script>";
        exit();
    }
}

$conn->close();
?>
