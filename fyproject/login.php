<?php
session_start();
require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        
        if (!empty($email) && !empty($password)) {
            // Check if user exists
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];
                
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Invalid email or password!";
            }
        } else {
            $error = "Please fill all fields!";
        }
    } elseif (isset($_POST['register'])) {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm_password']);
        
        if (!empty($name) && !empty($email) && !empty($password) && !empty($confirm_password)) {
            if ($password === $confirm_password) {
                // Check if email already exists
                $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->execute([$email]);
                
                if ($stmt->rowCount() === 0) {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $role = 'user'; // Default role
                    
                    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
                    if ($stmt->execute([$name, $email, $hashed_password, $role])) {
                        $success = "Registration successful! Please login.";
                    } else {
                        $error = "Registration failed. Please try again.";
                    }
                } else {
                    $error = "Email already exists!";
                }
            } else {
                $error = "Passwords do not match!";
            }
        } else {
            $error = "Please fill all fields!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CV Scoring</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .login-page {
            min-height: 100vh;
            background: linear-gradient(135deg, #1a202c 0%, #2d3748 50%, #000000 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            background: rgba(30, 41, 59, 0.95);
            border-radius: 16px;
            padding: 40px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
            position: relative;
        }
        
        .login-tabs {
            display: flex;
            margin-bottom: 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .login-tab {
            padding: 15px 20px;
            background: none;
            border: none;
            color: #a0aec0;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .login-tab.active {
            color: white;
        }
        
        .login-tab.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            border-radius: 3px 3px 0 0;
        }
        
        .login-form-container {
            position: relative;
        }
        
        .form-panel {
            display: none;
            animation: fadeIn 0.5s ease;
        }
        
        .form-panel.active {
            display: block;
        }
        
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert-error {
            background: rgba(239, 68, 68, 0.2);
            border-left: 4px solid #ef4444;
            color: #fca5a5;
        }
        
        .alert-success {
            background: rgba(16, 185, 129, 0.2);
            border-left: 4px solid #10b981;
            color: #6ee7b7;
        }
        
        .login-footer {
            text-align: center;
            margin-top: 25px;
            color: #a0aec0;
            font-size: 0.9rem;
        }
        
        .login-footer a {
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .login-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body class="login-page">
    <div class="login-container">
        <a href="index.html" class="logo" style="display: inline-flex; margin-bottom: 30px;">
            <span>MK</span>
            <h1>CV Scoring</h1>
        </a>
        
        <div class="login-tabs">
            <button class="login-tab active" id="login-tab">Login</button>
            <button class="login-tab" id="register-tab">Register</button>
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo $error; ?></span>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span><?php echo $success; ?></span>
            </div>
        <?php endif; ?>
        
        <div class="login-form-container">
            <!-- Login Form -->
            <form class="form-panel active" id="login-form" method="POST">
                <input type="hidden" name="login" value="1">
                
                <div class="form-group">
                    <label for="login-email">Email Address</label>
                    <input type="email" id="login-email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="login-password">Password</label>
                    <input type="password" id="login-password" name="password" required>
                </div>
                
                <div class="form-group" style="flex-direction: row; align-items: center; justify-content: space-between;">
                    <div>
                        <input type="checkbox" id="remember-me" name="remember_me">
                        <label for="remember-me" style="display: inline; margin-left: 8px;">Remember me</label>
                    </div>
                    <a href="#" style="color: var(--primary-color); text-decoration: none; font-size: 0.9rem;">Forgot password?</a>
                </div>
                
                <button type="submit" class="login-form-btn">Sign In</button>
            </form>
            
            <!-- Register Form -->
            <form class="form-panel" id="register-form" method="POST">
                <input type="hidden" name="register" value="1">
                
                <div class="form-group">
                    <label for="register-name">Full Name</label>
                    <input type="text" id="register-name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="register-email">Email Address</label>
                    <input type="email" id="register-email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="register-password">Password</label>
                    <input type="password" id="register-password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label for="register-confirm-password">Confirm Password</label>
                    <input type="password" id="register-confirm-password" name="confirm_password" required>
                </div>
                
                <div class="form-group">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms" style="display: inline; margin-left: 8px;">I agree to the <a href="#" style="color: var(--primary-color);">Terms of Service</a> and <a href="#" style="color: var(--primary-color);">Privacy Policy</a></label>
                </div>
                
                <button type="submit" class="login-form-btn">Create Account</button>
            </form>
        </div>
        
        <div class="login-divider">
            <span>Or continue with</span>
        </div>
        
        <div class="social-login">
            <button class="social-btn google-btn">
                <i class="fab fa-google"></i>
                Sign in with Google
            </button>
            
            <button class="social-btn linkedin-btn">
                <i class="fab fa-linkedin"></i>
                Sign in with LinkedIn
            </button>
        </div>
        
        <div class="login-footer">
            <a href="index.php"><i class="fas fa-arrow-left"></i> Back to Home</a>
        </div>
    </div>

    <script>
        // Tab switching functionality
        const loginTab = document.getElementById('login-tab');
        const registerTab = document.getElementById('register-tab');
        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');
        
        loginTab.addEventListener('click', () => {
            loginTab.classList.add('active');
            registerTab.classList.remove('active');
            loginForm.classList.add('active');
            registerForm.classList.remove('active');
        });
        
        registerTab.addEventListener('click', () => {
            registerTab.classList.add('active');
            loginTab.classList.remove('active');
            registerForm.classList.add('active');
            loginForm.classList.remove('active');
        });
    </script>
</body>
</html>