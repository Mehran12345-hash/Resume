<?php
session_start();
require_once 'config.php';
require_once 'db_functions.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    
    // Validate inputs
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Please fill in all fields";
        header("Location: login.php");
        exit();
    }
    
    // Get user by email
    $user = getUserByEmail($email);
    
    // Verify password and authenticate user
    if ($user && password_verify($password, $user['password'])) {
        // User authenticated successfully
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        
        // Get user resumes
        $resumes = getUserResumes($user['id']);
        
        // Get dashboard stats
        $stats = getDashboardStats($user['id']);
        
        // Add login notification
        addNotification($user['id'], "Login Successful", "You have successfully logged into your account.", "success");
        
        // Redirect to dashboard
        header("Location: dashboard.php");
        exit();
    } else {
        // Authentication failed
        $_SESSION['error'] = "Invalid email or password";
        header("Location: login.php");
        exit();
    }
} else {
    // If not a POST request, redirect to login
    header("Location: login.php");
    exit();
}
?>