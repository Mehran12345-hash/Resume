<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user data
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Get user's resumes
$resumes_stmt = $pdo->prepare("SELECT * FROM resumes WHERE user_id = ? ORDER BY created_at DESC");
$resumes_stmt->execute([$user_id]);
$resumes = $resumes_stmt->fetchAll();

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['resume'])) {
    $file = $_FILES['resume'];
    
    // Check for errors
    if ($file['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $file['tmp_name'];
        $file_name = uniqid() . '_' . basename($file['name']);
        $file_size = $file['size'];
        $file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Validate file type
        $allowed_types = ['pdf', 'doc', 'docx', 'txt'];
        if (in_array($file_type, $allowed_types)) {
            // Validate file size (5MB max)
            if ($file_size <= 5 * 1024 * 1024) {
                // Move file to uploads directory
                $upload_dir = 'uploads/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                
                $file_path = $upload_dir . $file_name;
                
                if (move_uploaded_file($file_tmp, $file_path)) {
                    // Save to database
                    $stmt = $pdo->prepare("INSERT INTO resumes (user_id, filename, original_name, file_size) VALUES (?, ?, ?, ?)");
                    if ($stmt->execute([$user_id, $file_name, $file['name'], $file_size])) {
                        // Simulate analysis (in a real app, this would be done by AI)
                        $resume_id = $pdo->lastInsertId();
                        $score = rand(70, 95); // Random score for demo
                        
                        $feedback = "Your resume scored $score/100. ";
                        if ($score >= 90) {
                            $feedback .= "Excellent! Your resume is well-optimized for ATS systems.";
                        } elseif ($score >= 80) {
                            $feedback .= "Good job! Your resume is mostly optimized but could use some improvements.";
                        } else {
                            $feedback .= "Your resume needs optimization to better match ATS requirements.";
                        }
                        
                        $update_stmt = $pdo->prepare("UPDATE resumes SET score = ?, feedback = ? WHERE id = ?");
                        $update_stmt->execute([$score, $feedback, $resume_id]);
                        
                        header("Location: dashboard.php?upload=success");
                        exit();
                    } else {
                        $error = "Failed to save resume to database.";
                    }
                } else {
                    $error = "Failed to upload file.";
                }
            } else {
                $error = "File size exceeds 5MB limit.";
            }
        } else {
            $error = "Invalid file type. Only PDF, DOC, DOCX, and TXT files are allowed.";
        }
    } else {
        $error = "File upload error: " . $file['error'];
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CV Scoring</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3b82f6;
            --secondary-color: #10b981;
            --accent-color: #8b5cf6;
            --dark-color: #1f2937;
            --light-color: #f9fafb;
            --text-color: #1f2937;
            --card-bg: #ffffff;
            --card-border: #e5e7eb;
            --sidebar-bg: #1e40af;
            --sidebar-text: #ffffff;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #f3f4f6;
            color: var(--text-color);
            min-height: 100vh;
            line-height: 1.6;
        }
        
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            padding: 20px 0;
            transition: all 0.3s ease;
            position: fixed;
            height: 100vh;
            z-index: 1000;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
        }
        
        .sidebar-logo {
            display: flex;
            align-items: center;
            text-decoration: none;
            margin-bottom: 25px;
            gap: 10px;
        }
        
        .logo-icon {
            width: 40px;
            height: 40px;
            background: #ffffff;
            color: var(--primary-color);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 20px;
        }
        
        .logo-text {
            font-size: 22px;
            font-weight: 700;
            color: white;
        }
        
        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #ffffff;
        }
        
        .user-info h3 {
            font-size: 16px;
            margin-bottom: 5px;
        }
        
        .user-info p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 13px;
        }
        
        .sidebar-nav {
            list-style: none;
        }
        
        .sidebar-nav li {
            margin-bottom: 5px;
        }
        
        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px 20px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-left-color: #ffffff;
        }
        
        .sidebar-nav i {
            font-size: 18px;
            width: 24px;
            text-align: center;
        }
        
        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: auto;
            position: absolute;
            bottom: 0;
            width: 100%;
        }
        
        .logout-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 12px 20px;
            background: rgba(239, 68, 68, 0.2);
            color: #fecaca;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.3);
            color: white;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 30px;
        }
        
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--card-border);
        }
        
        .page-title h1 {
            font-size: 28px;
            margin-bottom: 5px;
            color: var(--dark-color);
        }
        
        .page-title p {
            color: #6b7280;
        }
        
        .header-actions {
            display: flex;
            gap: 15px;
        }
        
        .notification-btn {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: white;
            border: 1px solid var(--card-border);
            color: #6b7280;
            cursor: pointer;
            position: relative;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .notification-btn:hover {
            background: #f9fafb;
            color: var(--primary-color);
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--primary-color);
            color: white;
            font-size: 12px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: var(--dark-color);
            font-size: 24px;
            cursor: pointer;
        }
        
        /* Dashboard Content */
        .dashboard-content {
            padding: 20px 0;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 25px;
            border: 1px solid var(--card-border);
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .stat-title {
            font-size: 16px;
            color: #6b7280;
            font-weight: 600;
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        
        .bg-blue {
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary-color);
        }
        
        .bg-green {
            background: rgba(16, 185, 129, 0.1);
            color: var(--secondary-color);
        }
        
        .bg-purple {
            background: rgba(139, 92, 246, 0.1);
            color: var(--accent-color);
        }
        
        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 10px;
        }
        
        .stat-description {
            color: #6b7280;
            font-size: 14px;
        }
        
        .progress-ring {
            position: relative;
            width: 80px;
            height: 80px;
        }
        
        .ring-circle {
            transform: rotate(-90deg);
            transform-origin: 50% 50%;
        }
        
        .ring-bg {
            stroke: #e5e7eb;
            fill: none;
            stroke-width: 6;
        }
        
        .ring-progress {
            stroke: var(--primary-color);
            fill: none;
            stroke-width: 6;
            stroke-linecap: round;
            transition: stroke-dashoffset 0.5s ease;
        }
        
        .ring-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 18px;
            font-weight: 700;
            color: var(--dark-color);
        }
        
        .section {
            margin-bottom: 50px;
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .section-title {
            font-size: 22px;
            font-weight: 600;
            color: var(--dark-color);
        }
        
        .view-all {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
            font-size: 14px;
        }
        
        .view-all:hover {
            color: var(--accent-color);
        }
        
        .upload-box {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 30px;
            border: 2px dashed var(--card-border);
            text-align: center;
            transition: all 0.3s ease;
            margin-bottom: 30px;
        }
        
        .upload-box:hover {
            border-color: var(--primary-color);
        }
        
        .upload-icon {
            font-size: 48px;
            color: var(--primary-color);
            margin-bottom: 20px;
        }
        
        .upload-title {
            font-size: 20px;
            margin-bottom: 10px;
            color: var(--dark-color);
        }
        
        .upload-subtitle {
            color: #6b7280;
            margin-bottom: 20px;
        }
        
        .file-input-wrapper {
            margin-bottom: 20px;
        }
        
        .file-input {
            display: none;
        }
        
        .browse-btn {
            background: var(--primary-color);
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .browse-btn:hover {
            background: #2563eb;
            transform: translateY(-2px);
        }
        
        .file-types {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .file-type {
            background: #f9fafb;
            padding: 8px 15px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            border: 1px solid var(--card-border);
        }
        
        .file-type i {
            color: var(--primary-color);
        }
        
        .resumes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .resume-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 20px;
            border: 1px solid var(--card-border);
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .resume-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        
        .resume-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .resume-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            background: rgba(16, 185, 129, 0.1);
            color: var(--secondary-color);
        }
        
        .resume-title {
            font-size: 18px;
            color: var(--dark-color);
            font-weight: 600;
        }
        
        .resume-date {
            color: #6b7280;
            font-size: 14px;
        }
        
        .resume-details {
            margin-bottom: 20px;
        }
        
        .resume-score {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .score-high {
            background: rgba(16, 185, 129, 0.1);
            color: var(--secondary-color);
        }
        
        .score-medium {
            background: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }
        
        .score-low {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }
        
        .resume-feedback {
            color: #6b7280;
            font-size: 14px;
            line-height: 1.6;
        }
        
        .resume-actions {
            display: flex;
            gap: 10px;
        }
        
        .action-btn {
            padding: 8px 16px;
            border-radius: 6px;
            border: none;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
        }
        
        .primary-btn {
            background: var(--primary-color);
            color: white;
        }
        
        .primary-btn:hover {
            background: #2563eb;
        }
        
        .secondary-btn {
            background: #f9fafb;
            color: var(--dark-color);
            border: 1px solid var(--card-border);
        }
        
        .secondary-btn:hover {
            background: #f3f4f6;
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
            background: rgba(239, 68, 68, 0.1);
            border-left: 4px solid #ef4444;
            color: #dc2626;
        }
        
        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border-left: 4px solid var(--secondary-color);
            color: #065f46;
        }
        
        /* Responsive Styles */
        @media (max-width: 992px) {
            .sidebar {
                width: 230px;
            }
            
            .main-content {
                margin-left: 230px;
            }
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
            
            .mobile-menu-btn {
                display: block;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .resumes-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="dashboard.php" class="sidebar-logo">
                    <div class="logo-icon">MK</div>
                    <div class="logo-text">CV Scoring</div>
                </a>
                
                <div class="sidebar-user">
                    <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="User" class="user-avatar">
                    <div class="user-info">
                        <h3><?php echo htmlspecialchars($user['name']); ?></h3>
                        <p><?php echo htmlspecialchars($user['email']); ?></p>
                    </div>
                </div>
            </div>
            
            <nav>
                <ul class="sidebar-nav">
                    <li><a href="dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li><a href="resume_analyzer.php"><i class="fas fa-file-alt"></i> Resume Analyzer</a></li>
                    <li><a href="cover_letter.php"><i class="fas fa-envelope"></i> AI Cover Letter</a></li>
                    <li><a href="resume_builder.php"><i class="fas fa-magic"></i> AI Resume Builder</a></li>
                </ul>
            </nav>
            
            <div class="sidebar-footer">
                <button class="logout-btn" onclick="location.href='?logout=1'">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </button>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            <div class="dashboard-header">
                <div class="page-title">
                    <h1>Welcome back, <?php echo htmlspecialchars($user['name']); ?>!</h1>
                    <p>Here's your resume analysis dashboard</p>
                </div>
                
                <div class="header-actions">
                    <!-- <button class="notification-btn"> -->
                        <!-- <i class="fas fa-bell"></i> -->
                        <!-- <span class="notification-badge">3</span> -->
                    </button>
                    
                    <button class="mobile-menu-btn" id="mobile-menu-btn">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
            
            <div class="dashboard-content">
                <!-- Stats Overview -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-header">
                            <h3 class="stat-title">Resume Score</h3>
                            <div class="stat-icon bg-blue">
                                <i class="fas fa-file-alt"></i>
                            </div>
                        </div>
                        <div class="progress-ring">
                            <svg class="ring-circle" width="80" height="80" viewBox="0 0 100 100">
                                <circle class="ring-bg" cx="50" cy="50" r="40"></circle>
                                <circle class="ring-progress" cx="50" cy="50" r="40" stroke-dasharray="251.2" stroke-dashoffset="75.36"></circle>
                            </svg>
                            <div class="ring-text">100%</div>
                        </div>
                        <p class="stat-description">Your resume scored 93 out of 100</p>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <h3 class="stat-title">Resumes Analyzed</h3>
                            <div class="stat-icon bg-green">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                        </div>
                        <div class="stat-value"><?php echo count($resumes); ?></div>
                        <p class="stat-description">Total resumes you've analyzed</p>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <h3 class="stat-title">Average Score</h3>
                            <div class="stat-icon bg-purple">
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <div class="stat-value">
                            <?php
                            if (count($resumes) > 0) {
                                $total_score = 0;
                                $count = 0;
                                foreach ($resumes as $resume) {
                                    if ($resume['score']) {
                                        $total_score += $resume['score'];
                                        $count++;
                                    }
                                }
                                echo $count > 0 ? round($total_score / $count) : '0';
                            } else {
                                echo '0';
                            }
                            ?>%
                        </div>
                        <p class="stat-description">Average score across all resumes</p>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <h3 class="stat-title">Profile Strength</h3>
                            <div class="stat-icon bg-blue">
                                <i class="fas fa-user-check"></i>
                            </div>
                        </div>
                        <div class="progress-ring">
                            <svg class="ring-circle" width="80" height="80" viewBox="0 0 100 100">
                                <circle class="ring-bg" cx="50" cy="50" r="40"></circle>
                                <circle class="ring-progress" cx="50" cy="50" r="40" stroke-dasharray="251.2" stroke-dashoffset="100.48"></circle>
                            </svg>
                            <div class="ring-text">60%</div>
                        </div>
                        <p class="stat-description">Complete your profile for better matches</p>
                    </div>
                </div>
                
                <!-- Upload Section -->
                <div class="section">
                    <div class="section-header">
                        <div class="section-title">
                            <h2>Analyze New Resume</h2>
                            <p>Upload your resume for instant feedback</p>
                        </div>
                    </div>
                    
                    <div class="upload-box">
                        <div class="upload-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        
                        <h3 class="upload-title">Drag & Drop Your Resume</h3>
                        <p class="upload-subtitle">or browse files from your computer</p>
                        
                        <form method="POST" enctype="multipart/form-data">
                            <div class="file-input-wrapper">
                                <label for="resume-upload" class="browse-btn">Browse Files</label>
                                <input type="file" id="resume-upload" name="resume" class="file-input" accept=".pdf,.doc,.docx,.txt">
                            </div>
                            
                            <div class="file-types">
                                <div class="file-type">
                                    <i class="far fa-file-pdf"></i>
                                    <span>PDF</span>
                                </div>
                                <div class="file-type">
                                    <i class="far fa-file-word"></i>
                                    <span>DOCX</span>
                                </div>
                                <div class="file-type">
                                    <i class="far fa-file-alt"></i>
                                    <span>TXT</span>
                                </div>
                            </div>
                            
                            <button type="submit" class="browse-btn">Analyze Now</button>
                        </form>
                    </div>
                    
                    <?php if (isset($error)): ?>
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i>
                            <span><?php echo $error; ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_GET['upload']) && $_GET['upload'] === 'success'): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <span>Resume uploaded successfully! Analysis complete.</span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Recent Resumes -->
                <div class="section">
                    <div class="section-header">
                        <div class="section-title">
                            <h2>Recent Resumes</h2>
                            <p>Your recently analyzed resumes</p>
                        </div>
                        <a href="#" class="view-all">View All</a>
                    </div>
                    
                    <?php if (count($resumes) > 0): ?>
                        <div class="resumes-grid">
                            <?php foreach ($resumes as $resume): ?>
                                <div class="resume-card">
                                    <div class="resume-header">
                                        <div class="resume-icon">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                        <div>
                                            <h3 class="resume-title"><?php echo htmlspecialchars($resume['original_name']); ?></h3>
                                            <p class="resume-date">Uploaded on <?php echo date('M j, Y', strtotime($resume['created_at'])); ?></p>
                                        </div>
                                    </div>
                                    
                                    <div class="resume-details">
                                        <?php if ($resume['score']): ?>
                                            <?php
                                            $score_class = 'score-high';
                                            if ($resume['score'] < 70) {
                                                $score_class = 'score-low';
                                            } elseif ($resume['score'] < 85) {
                                                $score_class = 'score-medium';
                                            }
                                            ?>
                                            <div class="resume-score <?php echo $score_class; ?>">
                                                <i class="fas fa-star"></i>
                                                <?php echo $resume['score']; ?>% Match
                                            </div>
                                            
                                            <p class="resume-feedback"><?php echo htmlspecialchars($resume['feedback']); ?></p>
                                        <?php else: ?>
                                            <div class="resume-score score-medium">
                                                <i class="fas fa-clock"></i>
                                                Processing
                                            </div>
                                            
                                            <p class="resume-feedback">Your resume is being analyzed. Please check back later.</p>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="resume-actions">
                                        <button class="action-btn primary-btn">View Report</button>
                                        <button class="action-btn secondary-btn">Download</button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="upload-box">
                            <div class="upload-icon">
                                <i class="fas fa-file-upload"></i>
                            </div>
                            
                            <h3 class="upload-title">No Resumes Yet</h3>
                            <p class="upload-subtitle">Upload your first resume to get started</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Mobile sidebar toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const sidebar = document.getElementById('sidebar');
        
        mobileMenuBtn.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });
        
        // File upload preview
        const fileInput = document.getElementById('resume-upload');
        const browseBtn = document.querySelector('.browse-btn');
        
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                // Auto-submit the form when a file is selected
                this.closest('form').submit();
            }
        });
    </script>
</body>
</html>