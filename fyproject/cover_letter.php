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

// Get user's cover letters
$cover_letters_stmt = $pdo->prepare("SELECT * FROM cover_letters WHERE user_id = ? ORDER BY created_at DESC");
$cover_letters_stmt->execute([$user_id]);
$cover_letters = $cover_letters_stmt->fetchAll();

// Handle cover letter generation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_letter'])) {
    $job_title = $_POST['job_title'];
    $company_name = $_POST['company_name'];
    $job_description = $_POST['job_description'];
    
    // Simulate AI generation
    $generated_content = "Dear Hiring Manager,\n\n";
    $generated_content .= "I am writing to express my interest in the $job_title position at $company_name. ";
    $generated_content .= "With my extensive experience and skills, I am confident that I would be a valuable asset to your team.\n\n";
    $generated_content .= "Based on the job description, I possess the following qualifications:\n";
    $generated_content .= "- Strong technical skills in relevant areas\n";
    $generated_content .= "- Proven track record of success in similar roles\n";
    $generated_content .= "- Excellent communication and teamwork abilities\n\n";
    $generated_content .= "I am excited about the opportunity to contribute to your organization and look forward to discussing how my skills and experience can benefit $company_name.\n\n";
    $generated_content .= "Sincerely,\n" . $user['name'];
    
    // Save to database
    $stmt = $pdo->prepare("INSERT INTO cover_letters (user_id, title, content, generated_content) VALUES (?, ?, ?, ?)");
    $title = "Cover Letter for $job_title at $company_name";
    $stmt->execute([$user_id, $title, $generated_content, $generated_content]);
    
    header("Location: cover_letter.php?generated=success");
    exit();
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Cover Letter Generator - CV Scoring</title>
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
        
        .form-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 25px;
            border: 1px solid var(--card-border);
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark-color);
        }
        
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid var(--card-border);
            background: white;
            color: var(--text-color);
            font-family: inherit;
        }
        
        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        .generate-btn {
            background: var(--primary-color);
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .generate-btn:hover {
            background: #2563eb;
            transform: translateY(-2px);
        }
        
        .letter-preview {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 30px;
            border: 1px solid var(--card-border);
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            position: relative;
            min-height: 500px;
        }
        
        .watermark {
            position: absolute;
            bottom: 20px;
            right: 20px;
            opacity: 0.1;
            font-size: 72px;
            font-weight: bold;
            color: var(--primary-color);
            pointer-events: none;
        }
        
        .letter-content {
            font-family: 'Georgia', serif;
            line-height: 1.8;
            color: var(--text-color);
        }
        
        .letter-header {
            text-align: right;
            margin-bottom: 40px;
        }
        
        .letter-body {
            margin-bottom: 40px;
        }
        
        .letter-footer {
            margin-top: 60px;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .action-btn {
            padding: 10px 20px;
            border-radius: 6px;
            border: none;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
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
        
        .letters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .letter-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 20px;
            border: 1px solid var(--card-border);
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .letter-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        
        .letter-card-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .letter-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary-color);
        }
        
        .letter-title {
            font-size: 18px;
            color: var(--dark-color);
            font-weight: 600;
        }
        
        .letter-date {
            color: #6b7280;
            font-size: 14px;
        }
        
        .letter-preview-text {
            color: #6b7280;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 20px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .empty-state {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 40px;
            text-align: center;
            border: 1px solid var(--card-border);
        }
        
        .empty-icon {
            font-size: 48px;
            color: #9ca3af;
            margin-bottom: 20px;
        }
        
        .empty-title {
            font-size: 20px;
            color: var(--dark-color);
            margin-bottom: 10px;
        }
        
        .empty-description {
            color: #6b7280;
            margin-bottom: 20px;
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
            
            .letters-grid {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
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
                    <div class="logo-icon">CV</div>
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
                    <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li><a href="resume_analyzer.php"><i class="fas fa-file-alt"></i> Resume Analyzer</a></li>
                    <li><a href="cover_letter.php" class="active"><i class="fas fa-envelope"></i> AI Cover Letter</a></li>
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
                    <h1>AI Cover Letter Generator</h1>
                    <p>Create professional cover letters with AI assistance</p>
                </div>
                
                <div class="header-actions">
                    <button class="notification-btn">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </button>
                    
                    <button class="mobile-menu-btn" id="mobile-menu-btn">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
            
            <div class="dashboard-content">
                <!-- Cover Letter Form -->
                <div class="section">
                    <div class="section-header">
                        <div class="section-title">
                            <h2>Generate New Cover Letter</h2>
                            <p>Provide details about the job you're applying for</p>
                        </div>
                    </div>
                    
                    <div class="form-card">
                        <form method="POST">
                            <div class="form-group">
                                <label for="job_title">Job Title</label>
                                <input type="text" id="job_title" name="job_title" placeholder="e.g., Software Engineer" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="company_name">Company Name</label>
                                <input type="text" id="company_name" name="company_name" placeholder="e.g., Google" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="job_description">Job Description</label>
                                <textarea id="job_description" name="job_description" placeholder="Paste the job description here..." required></textarea>
                            </div>
                            
                            <button type="submit" name="generate_letter" class="generate-btn">
                                <i class="fas fa-robot"></i> Generate Cover Letter
                            </button>
                        </form>
                    </div>
                    
                    <?php if (isset($_GET['generated']) && $_GET['generated'] === 'success'): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <span>Cover letter generated successfully!</span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Generated Letter Preview -->
                <div class="section">
                    <div class="section-header">
                        <div class="section-title">
                            <h2>Generated Cover Letter</h2>
                            <p>Your AI-generated cover letter</p>
                        </div>
                    </div>
                    
                    <div class="letter-preview">
                        <div class="watermark">MK</div>
                        
                        <div class="letter-content">
                            <div class="letter-header">
                                <p><?php echo htmlspecialchars($user['name']); ?></p>
                                <p><?php echo htmlspecialchars($user['email']); ?></p>
                                <p><?php echo htmlspecialchars($user['phone']); ?></p>
                                <p><?php echo htmlspecialchars($user['location']); ?></p>
                            </div>
                            
                            <div class="letter-body">
                                <p>Dear Hiring Manager,</p>
                                
                                <p>I am writing to express my interest in the Software Engineer position at Google. With my extensive experience and skills, I am confident that I would be a valuable asset to your team.</p>
                                
                                <p>Based on the job description, I possess the following qualifications:</p>
                                
                                <ul>
                                    <li>Strong technical skills in relevant areas</li>
                                    <li>Proven track record of success in similar roles</li>
                                    <li>Excellent communication and teamwork abilities</li>
                                </ul>
                                
                                <p>I am excited about the opportunity to contribute to your organization and look forward to discussing how my skills and experience can benefit Google.</p>
                            </div>
                            
                            <div class="letter-footer">
                                <p>Sincerely,</p>
                                <p><?php echo htmlspecialchars($user['name']); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="action-buttons">
                        <button class="action-btn primary-btn">
                            <i class="fas fa-download"></i> Download as PDF
                        </button>
                        <button class="action-btn secondary-btn">
                            <i class="fas fa-copy"></i> Copy to Clipboard
                        </button>
                        <button class="action-btn secondary-btn">
                            <i class="fas fa-edit"></i> Edit Content
                        </button>
                    </div>
                </div>
                
                <!-- Previous Cover Letters -->
                <div class="section">
                    <div class="section-header">
                        <div class="section-title">
                            <h2>Previous Cover Letters</h2>
                            <p>Your recently generated cover letters</p>
                        </div>
                        <a href="#" class="view-all">View All</a>
                    </div>
                    
                    <?php if (count($cover_letters) > 0): ?>
                        <div class="letters-grid">
                            <?php foreach ($cover_letters as $letter): ?>
                                <div class="letter-card">
                                    <div class="letter-card-header">
                                        <div class="letter-icon">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <div>
                                            <h3 class="letter-title"><?php echo htmlspecialchars($letter['title']); ?></h3>
                                            <p class="letter-date">Created on <?php echo date('M j, Y', strtotime($letter['created_at'])); ?></p>
                                        </div>
                                    </div>
                                    
                                    <p class="letter-preview-text"><?php echo substr($letter['content'], 0, 150) . '...'; ?></p>
                                    
                                    <div class="action-buttons">
                                        <button class="action-btn primary-btn">View</button>
                                        <button class="action-btn secondary-btn">Download</button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <h3 class="empty-title">No Cover Letters Yet</h3>
                            <p class="empty-description">Generate your first cover letter using our AI-powered tool.</p>
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
        
        // Simulate AI generation on page load for demo
        document.addEventListener('DOMContentLoaded', function() {
            // Pre-fill form with sample data for demo
            document.getElementById('job_title').value = "Software Engineer";
            document.getElementById('company_name').value = "Google";
            document.getElementById('job_description').value = "We are looking for a skilled Software Engineer with experience in JavaScript, React, and Node.js. The ideal candidate will have 5+ years of experience and a strong background in building scalable web applications.";
        });
    </script>
</body>
</html>