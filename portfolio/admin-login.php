<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #0f054c, #1a0f91);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            animation: backgroundFade 10s infinite alternate;
        }

        @keyframes backgroundFade {
            0% { background: linear-gradient(to right, #0f054c, #1a0f91); }
            100% { background: linear-gradient(to right, #1a0f91, #0f054c); }
        }

        .login-container {
            background: white;
            color: #0f054c;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 400px;
            animation: slideIn 1s ease forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        @keyframes slideIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h3 {
            text-align: center;
            margin-bottom: 24px;
            font-weight: bold;
        }

        .form-control {
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #1a0f91;
            box-shadow: 0 0 10px #1a0f91aa;
        }

        .btn-primary {
            background-color: #1a0f91;
            border: none;
            transition: background 0.3s, box-shadow 0.3s;
        }

        .btn-primary:hover {
            background-color: #140b75;
            box-shadow: 0 0 15px #1a0f91aa;
        }

        .btn-danger {
            background: #e50914;
            border: none;
            margin-top: 10px;
            transition: background 0.3s, transform 0.3s;
        }

        .btn-danger:hover {
            background: #c40812;
            transform: scale(1.02);
            box-shadow: 0 0 12px #e50914aa;
        }

        .btn-danger a {
            color: white;
            text-decoration: none;
            display: block;
        }

        /* Responsive styles */
        @media (max-width: 576px) {
            .btn-danger a {
                font-size: 14px;
                padding: 8px 0;
            }
        }

        @media (min-width: 576px) {
            .btn-danger a {
                font-size: 16px;
                padding: 10px 0;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h3>Admin Login</h3>
        <form action="admin_login.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
            <button class="btn btn-danger w-100">
                <a href="index.php">Back</a>
            </button>
        </form>
    </div>
</body>
</html>
