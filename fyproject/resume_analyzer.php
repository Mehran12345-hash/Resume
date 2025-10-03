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
$resumes_stmt = $pdo->prepare("SELECT * FROM resumes WHERE user_id = ? ORDER BY score DESC LIMIT 5");
$resumes_stmt->execute([$user_id]);
$top_resumes = $resumes_stmt->fetchAll();

// Get waiting list resumes (lower scores)
$waiting_stmt = $pdo->prepare("SELECT * FROM resumes WHERE user_id = ? AND score < 70 ORDER BY created_at DESC");
$waiting_stmt->execute([$user_id]);
$waiting_resumes = $waiting_stmt->fetchAll();

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
    <title>Resume Analyzer - CV Scoring</title>
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
        }
        
        .bg-blue {
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary-color);
        }
        
        .bg-green {
            background: rgba(16, 185, 129, 0.1);
            color: var(--secondary-color);
        }
        
        .bg-red {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
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
                    <li><a href="resume_analyzer.php" class="active"><i class="fas fa-file-alt"></i> Resume Analyzer</a></li>
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
                    <h1>Resume Analyzer</h1>
                    <p>Intelligent applicant screening system with NLP</p>
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
                <!-- Top Resumes -->
                <div class="section">
                    <div class="section-header">
                        <div class="section-title">
                            <h2>Top Resumes</h2>
                            <p>Your highest-scoring resumes (showing top 5)</p>
                        </div>
                    </div>
                    
                    <?php if (count($top_resumes) > 0): ?>
                        <div class="resumes-grid">
                            <?php foreach ($top_resumes as $resume): ?>
                                <div class="resume-card">
                                    <div class="resume-header">
                                        <div class="resume-icon bg-green">
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
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-file-upload"></i>
                            </div>
                            <h3 class="empty-title">No Resumes Yet</h3>
                            <p class="empty-description">Upload your first resume to get started with our intelligent analysis system.</p>
                            <a href="dashboard.php" class="action-btn primary-btn">Upload Resume</a>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Waiting List -->
                <div class="section">
                    <div class="section-header">
                        <div class="section-title">
                            <h2>Waiting List</h2>
                            <p>Resumes that need improvement (score below 70%)</p>
                        </div>
                    </div>
                    
                    <?php if (count($waiting_resumes) > 0): ?>
                        <div class="resumes-grid">
                            <?php foreach ($waiting_resumes as $resume): ?>
                                <div class="resume-card">
                                    <div class="resume-header">
                                        <div class="resume-icon bg-red">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                        <div>
                                            <h3 class="resume-title"><?php echo htmlspecialchars($resume['original_name']); ?></h3>
                                            <p class="resume-date">Uploaded on <?php echo date('M j, Y', strtotime($resume['created_at'])); ?></p>
                                        </div>
                                    </div>
                                    
                                    <div class="resume-details">
                                        <div class="resume-score score-low">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <?php echo $resume['score']; ?>% Match
                                        </div>
                                        
                                        <p class="resume-feedback"><?php echo htmlspecialchars($resume['feedback']); ?></p>
                                    </div>
                                    
                                    <div class="resume-actions">
                                        <button class="action-btn primary-btn">Improve Now</button>
                                        <button class="action-btn secondary-btn">View Details</button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h3 class="empty-title">No Resumes Need Improvement</h3>
                            <p class="empty-description">All your resumes are scoring well! Keep up the good work.</p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- NLP Analysis -->
                <div class="section">
                    <div class="section-header">
                        <div class="section-title">
                            <h2>NLP Analysis</h2>
                            <p>Natural Language Processing insights from your resumes</p>
                        </div>
                    </div>
                    
                    <div class="resumes-grid">
                        <div class="resume-card">
                            <div class="resume-header">
                                <div class="resume-icon bg-blue">
                                    <i class="fas fa-brain"></i>
                                </div>
                                <div>
                                    <h3 class="resume-title">Keyword Optimization</h3>
                                    <p class="resume-date">Based on NLP analysis</p>
                                </div>
                            </div>
                            
                            <div class="resume-details">
                                <p class="resume-feedback">Your resumes contain strong industry keywords but could benefit from more action verbs and quantifiable achievements.</p>
                                
                                <div style="margin-top: 15px;">
                                    <h4 style="font-size: 14px; margin-bottom: 8px;">Recommended Keywords:</h4>
                                    <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                                        <span style="background: #e0f2fe; color: #0369a1; padding: 4px 10px; border-radius: 20px; font-size: 12px;">managed</span>
                                        <span style="background: #e0f2fe; color: #0369a1; padding: 4px 10px; border-radius: 20px; font-size: 12px;">increased</span>
                                        <span style="background: #e0f2fe; color: #0369a1; padding: 4px 10px; border-radius: 20px; font-size: 12px;">implemented</span>
                                        <span style="background: #e0f2fe; color: #0369a1; padding: 4px 10px; border-radius: 20px; font-size: 12px;">optimized</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="resume-card">
                            <div class="resume-header">
                                <div class="resume-icon bg-blue">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div>
                                    <h3 class="resume-title">Skills Analysis</h3>
                                    <p class="resume-date">Based on NLP analysis</p>
                                </div>
                            </div>
                            
                            <div class="resume-details">
                                <p class="resume-feedback">Your skills are well-aligned with industry requirements. Consider adding these trending skills:</p>
                                
                                <div style="margin-top: 15px;">
                                    <h4 style="font-size: 14px; margin-bottom: 8px;">Trending Skills:</h4>
                                    <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                                        <span style="background: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 20px; font-size: 12px;">Machine Learning</span>
                                        <span style="background: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 20px; font-size: 12px;">Cloud Computing</span>
                                        <span style="background: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 20px; font-size: 12px;">DevOps</span>
                                        <span style="background: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 20px; font-size: 12px;">AI Integration</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
    </script>
</body>
</html>