<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    // Redirect to login page if not logged in
    header("Location:signin.php");
    exit();
}

// Continue with the page content for logged-in users
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CECOS University</title>
    <link rel="stylesheet" href="style.css">
    <!-- Include Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Header Section -->
    <header class="bg-dark text-white p-3 text-center">
        <h1>CECOS University</h1>
        <p>Committed to Excellence in Education</p>
    </header>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">CECOS</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav ml-auto">
    <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
    <li class="nav-item"><a class="nav-link" href="settings.php">Settings</a></li>
    <li class="nav-item"><a class="nav-link" href="user-login.php">User Login</a></li>
    <!-- Add more links as needed -->

            </ul>
        </div>
    </nav>

    <!-- Welcome Section -->
    <section id="home" class="container mt-5 text-center">
        <h2>Welcome to CECOS University</h2>
        <p>Your future starts here. Explore our programs and facilities.</p>
    </section>

    <!-- Programs Section -->
    <section id="programs" class="container mt-5">
        <h2>Our Programs</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Engineering</h5>
                        <p class="card-text">Explore our engineering programs and courses.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Business</h5>
                        <p class="card-text">Learn about our business management programs.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Computer Science</h5>
                        <p class="card-text">Discover our computer science and IT courses.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="container mt-5">
        <h2>Contact Us</h2>
        <p>Email: info@cecos.edu.pk</p>
        <p>Phone: +92 91 5860291-3</p>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white p-3 text-center mt-5">
        <p>&copy; 2024 CECOS University. All rights reserved.</p>
    </footer>

    <!-- Include Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
