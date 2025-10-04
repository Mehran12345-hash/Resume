<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>
            <img src="Screenshot 2024-09-19 004822.png" alt="CECOS Logo">
            Learning Management System
        </h1>
        <div class="lock-icon">🔒</div>
        <h2>Student Login</h2>
        <h3>CECOS University Peshawar</h3>
        <form action="login.php" method="POST">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <div class="button-container">
                <button type="submit">Login</button>
                <a href="#" class="forgot-password">Forgot Password?</a>
            </div>
        </form>
        <?php if (isset($_GET['error'])): ?>
            <?php if ($_GET['error'] == 'invalid_password'): ?>
                <p style="color: red;">Invalid password. Please try again.</p>
            <?php elseif ($_GET['error'] == 'user_not_found'): ?>
                <p style="color: red;">Username not found. Please check your username.</p>
            <?php endif; ?>
        <?php endif; ?>
        <div class="copyright">All rights reserved @ CECOS.EDU.PK and CECOS University Peshawar</div>
    </div>
</body>
</html>
