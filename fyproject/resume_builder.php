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

// Get user profile
$profile_stmt = $pdo->prepare("SELECT * FROM user_profiles WHERE user_id = ?");
$profile_stmt->execute([$user_id]);
$profile = $profile_stmt->fetch();

// Initialize variables with default values
$user_name = htmlspecialchars($user['name'] ?? '');
$user_profession = htmlspecialchars($user['profession'] ?? '');
$user_email = htmlspecialchars($user['email'] ?? '');
$user_phone = htmlspecialchars($user['phone'] ?? '');
$user_location = htmlspecialchars($user['location'] ?? '');
$profile_summary = htmlspecialchars($profile['summary'] ?? '');
$profile_image = $profile['profile_image'] ?? '';

// Handle resume generation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['generate_resume'])) {
        $template = $_POST['template'];
        $color_scheme = $_POST['color_scheme'];
        $resume_data = $_POST['resume_data'];
        
        // Handle image upload
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $target_dir = "uploads/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            
            $file_extension = pathinfo($_FILES["profile_image"]["name"], PATHINFO_EXTENSION);
            $filename = "profile_" . $user_id . "_" . time() . "." . $file_extension;
            $target_file = $target_dir . $filename;
            
            // Check if image file is an actual image
            $check = getimagesize($_FILES["profile_image"]["tmp_name"]);
            if ($check !== false) {
                if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                    // Update profile image in database
                    if ($profile) {
                        $stmt = $pdo->prepare("UPDATE user_profiles SET profile_image = ? WHERE user_id = ?");
                        $stmt->execute([$filename, $user_id]);
                    } else {
                        $stmt = $pdo->prepare("INSERT INTO user_profiles (user_id, profile_image) VALUES (?, ?)");
                        $stmt->execute([$user_id, $filename]);
                    }
                    $profile_image = $filename;
                }
            }
        }
        
        // Process AI-generated content if provided
        if (!empty($resume_data)) {
            // Parse the AI-generated content to extract sections
            $sections = parseResumeContent($resume_data);
            
            // Save to database
            $stmt = $pdo->prepare("INSERT INTO resumes (user_id, filename, original_name, file_size, file_type, content) VALUES (?, ?, ?, ?, ?, ?)");
            $filename = "resume_" . time() . ".pdf";
            $original_name = "AI_Generated_Resume.pdf";
            $stmt->execute([$user_id, $filename, $original_name, 1024, 'pdf', json_encode($sections)]);
        }
        
        header("Location: resume_builder.php?generated=success");
        exit();
    }
    
    // Handle AI content generation
    if (isset($_POST['ai_generate'])) {
        $input_text = $_POST['resume_data'];
        
        // Call ChatGPT API (simulated here - you would need to implement actual API call)
        $ai_content = generateAIContent($input_text);
        
        // Return the AI content as JSON
        header('Content-Type: application/json');
        echo json_encode(['content' => $ai_content]);
        exit();
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Get skills from profile
$skills = $profile['skills'] ?? 'JavaScript, React, Node.js, Python, SQL, AWS';

// Function to parse resume content (simplified)
function parseResumeContent($content) {
    $sections = [
        'personal' => [],
        'education' => [],
        'experience' => [],
        'skills' => []
    ];
    
    // Simple parsing logic - in a real implementation, this would be more sophisticated
    $lines = explode("\n", $content);
    $current_section = '';
    
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) continue;
        
        // Check for section headers
        if (preg_match('/^(personal|education|experience|skills)/i', $line, $matches)) {
            $current_section = strtolower($matches[1]);
        } elseif (!empty($current_section)) {
            $sections[$current_section][] = $line;
        }
    }
    
    return $sections;
}

// Function to simulate AI content generation
function generateAIContent($input_text) {
    // In a real implementation, this would call the ChatGPT API
    // For now, we'll simulate with some predefined templates
    
    $responses = [
        "I'm a software engineer with 5 years of experience" => 
            "PROFESSIONAL SUMMARY:\nExperienced software engineer with 5 years in full-stack development. Expertise in JavaScript, React, Node.js, and cloud technologies. Strong problem-solving skills and experience in agile environments.\n\nEDUCATION:\nBachelor of Science in Computer Science - University of Technology (2016-2020)\nHigher Secondary Certificate - Science College (2014-2016)\nMatriculation - Public School (2012-2014)\n\nEXPERIENCE:\nSenior Developer - Tech Solutions Inc. (2020-Present)\n- Developed and maintained web applications\n- Led a team of 5 developers\n- Implemented CI/CD pipelines\n\nSoftware Engineer - Innovate Co. (2018-2020)\n- Built responsive user interfaces\n- Collaborated with product teams\n- Optimized application performance\n\nSKILLS:\nJavaScript, React, Node.js, Python, SQL, AWS, Docker, Git, Agile Methodologies",
        
        "I'm a marketing specialist with 3 years experience" =>
            "PROFESSIONAL SUMMARY:\nResults-driven marketing specialist with 3 years of experience in digital marketing campaigns, SEO, and social media management. Proven track record of increasing brand visibility and engagement.\n\nEDUCATION:\nBachelor of Business Administration - Business University (2017-2021)\nHigher Secondary Certificate - Commerce College (2015-2017)\nMatriculation - Central School (2013-2015)\n\nEXPERIENCE:\nMarketing Specialist - Digital Agency (2021-Present)\n- Managed social media campaigns\n- Increased organic traffic by 45%\n- Developed content strategies\n\nMarketing Assistant - Retail Company (2019-2021)\n- Created marketing materials\n- Analyzed campaign performance\n- Coordinated events\n\nSKILLS:\nDigital Marketing, SEO, Social Media, Content Creation, Google Analytics, Email Marketing, Campaign Management"
    ];
    
    // Find the best matching response
    foreach ($responses as $key => $value) {
        if (stripos($input_text, $key) !== false) {
            return $value;
        }
    }
    
    // Default response if no match found
    return "PROFESSIONAL SUMMARY:\nDedicated professional with strong background in their field. Excellent communication skills and ability to work in team environments.\n\nEDUCATION:\nBachelor's Degree - University (Year-Year)\nHigher Secondary - College (Year-Year)\nMatriculation - School (Year-Year)\n\nEXPERIENCE:\nPosition - Company (Year-Year)\n- Responsibility/Achievement 1\n- Responsibility/Achievement 2\n\nPosition - Company (Year-Year)\n- Responsibility/Achievement 1\n- Responsibility/Achievement 2\n\nSKILLS:\nSkill 1, Skill 2, Skill 3, Skill 4, Skill 5";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Resume Builder - CV Scoring</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- PDF generation library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
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
            --resume-bg: #1e40af;
            --resume-text: #ffffff;
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
            color: white;
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
        
        .builder-container {
            display: flex;
            gap: 30px;
        }
        
        .builder-options {
            flex: 1;
        }
        
        .resume-preview {
            flex: 1;
            background: var(--resume-bg);
            border-radius: 12px;
            padding: 30px;
            color: var(--resume-text);
            min-height: 600px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
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
            min-height: 100px;
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
        
        .template-selector {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .template-item {
            border: 2px solid var(--card-border);
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .template-item:hover, .template-item.active {
            border-color: var(--primary-color);
            transform: translateY(-5px);
        }
        
        .template-item img {
            width: 100%;
            height: 120px;
            object-fit: cover;
        }
        
        .template-name {
            padding: 10px;
            text-align: center;
            background: #f9fafb;
            font-weight: 500;
        }
        
        .color-selector {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .color-item {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .color-item:hover, .color-item.active {
            transform: scale(1.1);
            border-color: var(--dark-color);
        }
        
        .color-blue {
            background: #1e40af;
        }
        
        .color-green {
            background: #065f46;
        }
        
        .color-purple {
            background: #5b21b6;
        }
        
        .color-gray {
            background: #374151;
        }
        
        .resume-content {
            font-family: 'Georgia', serif;
        }
        
        .resume-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.3);
            position: relative;
        }
        
        .profile-image-container {
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
        }
        
        .profile-image {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgba(255, 255, 255, 0.3);
        }
        
        .resume-name {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .resume-title {
            font-size: 18px;
            opacity: 0.9;
            margin-bottom: 15px;
        }
        
        .resume-contact {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            font-size: 14px;
            opacity: 0.8;
        }
        
        .resume-section {
            margin-bottom: 20px;
        }
        
        .resume-section-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding-bottom: 5px;
        }
        
        .experience-item, .education-item {
            margin-bottom: 15px;
        }
        
        .experience-header, .education-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        
        .experience-company, .education-institution {
            font-weight: 600;
        }
        
        .experience-date, .education-date {
            opacity: 0.8;
            font-size: 14px;
        }
        
        .experience-position, .education-degree {
            font-style: italic;
            margin-bottom: 5px;
            opacity: 0.9;
        }
        
        .skills-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        
        .skill-item {
            background: rgba(255, 255, 255, 0.2);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 14px;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
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
        
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border-left: 4px solid var(--secondary-color);
            color: #065f46;
        }
        
        .ai-generator {
            background: #f0f9ff;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #bae6fd;
        }
        
        .ai-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .ai-icon {
            color: var(--primary-color);
            font-size: 24px;
        }
        
        .ai-title {
            font-weight: 600;
            color: var(--dark-color);
        }
        
        .ai-prompt {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .ai-input {
            flex: 1;
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid var(--card-border);
            font-family: inherit;
        }
        
        .ai-button {
            background: var(--primary-color);
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .ai-button:hover {
            background: #2563eb;
        }
        
        .ai-output {
            background: white;
            border-radius: 8px;
            padding: 15px;
            border: 1px solid var(--card-border);
            min-height: 100px;
            max-height: 200px;
            overflow-y: auto;
        }
        
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background: white;
            border-radius: 12px;
            padding: 25px;
            width: 90%;
            max-width: 500px;
            max-height: 80vh;
            overflow-y: auto;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .modal-title {
            font-size: 20px;
            font-weight: 600;
        }
        
        .close-modal {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #6b7280;
        }
        
        /* Responsive Styles */
        @media (max-width: 992px) {
            .sidebar {
                width: 230px;
            }
            
            .main-content {
                margin-left: 230px;
            }
            
            .builder-container {
                flex-direction: column;
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
            
            .template-selector {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .ai-prompt {
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
                        <h3><?php echo $user_name; ?></h3>
                        <p><?php echo $user_email; ?></p>
                    </div>
                </div>
            </div>
            
            <nav>
                <ul class="sidebar-nav">
                    <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li><a href="resume_analyzer.php"><i class="fas fa-file-alt"></i> Resume Analyzer</a></li>
                    <li><a href="cover_letter.php"><i class="fas fa-envelope"></i> AI Cover Letter</a></li>
                    <li><a href="resume_builder.php" class="active"><i class="fas fa-magic"></i> AI Resume Builder</a></li>                   
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
                    <h1>AI Resume Builder</h1>
                    <p>Create professional resumes with AI assistance</p>
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
                <!-- Success Messages -->
                <?php if (isset($_GET['generated']) && $_GET['generated'] === 'success'): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <span>Resume generated successfully!</span>
                    </div>
                <?php endif; ?>
                
                <!-- Resume Builder -->
                <div class="section">
                    <div class="section-header">
                        <div class="section-title">
                            <h2>Build Your Resume</h2>
                            <p>Create a professional resume with our AI-powered builder</p>
                        </div>
                    </div>
                    
                    <div class="builder-container">
                        <div class="builder-options">
                            <!-- AI Content Generator -->
                            <div class="ai-generator">
                                <div class="ai-header">
                                    <i class="fas fa-robot ai-icon"></i>
                                    <h3 class="ai-title">AI Content Generator</h3>
                                </div>
                                <p style="margin-bottom: 15px;">Tell me about yourself and I'll create a professional resume:</p>
                                <div class="ai-prompt">
                                    <input type="text" class="ai-input" id="ai-prompt-input" placeholder="e.g., I'm a software engineer with 5 years of experience...">
                                    <button class="ai-button" id="ai-generate-btn">
                                        <i class="fas fa-bolt"></i> Generate
                                    </button>
                                </div>
                                <div class="ai-output" id="ai-output">
                                    AI-generated content will appear here...
                                </div>
                            </div>
                            
                            <div class="form-card">
                                <h3 style="margin-bottom: 20px;">Resume Details</h3>
                                
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="resume_data">Resume Content</label>
                                        <textarea id="resume_data" name="resume_data" required placeholder="Paste or type your resume content here..."></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="profile_image">Profile Image</label>
                                        <input type="file" id="profile_image" name="profile_image" accept="image/*">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Choose Template</label>
                                        <div class="template-selector">
                                            <div class="template-item active">
                                                <img src="https://images.unsplash.com/photo-1586281380117-5a60ae2050cc?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" alt="Professional Template">
                                                <div class="template-name">Professional</div>
                                            </div>
                                            <div class="template-item">
                                                <img src="https://images.unsplash.com/photo-1589652717521-10c0d092dea9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" alt="Modern Template">
                                                <div class="template-name">Modern</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Color Scheme</label>
                                        <div class="color-selector">
                                            <div class="color-item color-blue active"></div>
                                            <div class="color-item color-green"></div>
                                            <div class="color-item color-purple"></div>
                                            <div class="color-item color-gray"></div>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" name="generate_resume" class="generate-btn">
                                        <i class="fas fa-robot"></i> Generate Resume
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Resume Preview -->
                        <div class="resume-preview" id="resume-preview">
                            <div class="resume-content">
                                <div class="resume-header">
                                    <div class="profile-image-container">
                                        <?php if (!empty($profile_image)): ?>
                                            <img src="uploads/<?php echo $profile_image; ?>" alt="Profile" class="profile-image">
                                        <?php else: ?>
                                            <div style="width: 100px; height: 100px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-user" style="font-size: 40px;"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <h1 class="resume-name" id="preview-name"><?php echo $user_name; ?></h1>
                                    <div class="resume-title" id="preview-title">Professional</div>
                                    <div class="resume-contact">
                                        <span id="preview-email"><?php echo $user_email; ?></span>
                                        <span id="preview-phone"><?php echo $user_phone; ?></span>
                                        <span id="preview-location"><?php echo $user_location; ?></span>
                                    </div>
                                </div>
                                
                                <div class="resume-section">
                                    <h2 class="resume-section-title">Professional Summary</h2>
                                    <p id="preview-summary">Your professional summary will appear here.</p>
                                </div>
                                
                                <div class="resume-section">
                                    <h2 class="resume-section-title">Education</h2>
                                    <div class="education-item">
                                        <div class="education-header">
                                            <span class="education-institution">University Name</span>
                                            <span class="education-date">Year - Year</span>
                                        </div>
                                        <div class="education-degree">Degree Name</div>
                                    </div>
                                    <div class="education-item">
                                        <div class="education-header">
                                            <span class="education-institution">College Name</span>
                                            <span class="education-date">Year - Year</span>
                                        </div>
                                        <div class="education-degree">F.Sc / A-Levels</div>
                                    </div>
                                    <div class="education-item">
                                        <div class="education-header">
                                            <span class="education-institution">School Name</span>
                                            <span class="education-date">Year - Year</span>
                                        </div>
                                        <div class="education-degree">Matriculation / O-Levels</div>
                                    </div>
                                </div>
                                
                                <div class="resume-section">
                                    <h2 class="resume-section-title">Experience</h2>
                                    <div class="experience-item">
                                        <div class="experience-header">
                                            <span class="experience-company">Company Name</span>
                                            <span class="experience-date">Year - Year</span>
                                        </div>
                                        <div class="experience-position">Position Title</div>
                                        <p>Responsibilities and achievements will appear here.</p>
                                    </div>
                                </div>
                                
                                <div class="resume-section">
                                    <h2 class="resume-section-title">Skills</h2>
                                    <div class="skills-list" id="preview-skills">
                                        <span class="skill-item">Skill 1</span>
                                        <span class="skill-item">Skill 2</span>
                                        <span class="skill-item">Skill 3</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="action-buttons">
                    <button class="action-btn primary-btn" id="download-pdf">
                        <i class="fas fa-download"></i> Download as PDF
                    </button>
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
        
        // Template selection
        document.querySelectorAll('.template-item').forEach(item => {
            item.addEventListener('click', function() {
                document.querySelectorAll('.template-item').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });
        
        // Color selection
        document.querySelectorAll('.color-item').forEach(item => {
            item.addEventListener('click', function() {
                document.querySelectorAll('.color-item').forEach(c => c.classList.remove('active'));
                this.classList.add('active');
                
                // Update resume preview color
                const colorClass = this.classList[1];
                let newColor;
                
                if (colorClass === 'color-blue') newColor = '#1e40af';
                else if (colorClass === 'color-green') newColor = '#065f46';
                else if (colorClass === 'color-purple') newColor = '#5b21b6';
                else if (colorClass === 'color-gray') newColor = '#374151';
                
                document.querySelector('.resume-preview').style.background = newColor;
            });
        });
        
        // AI Content Generation
        document.getElementById('ai-generate-btn').addEventListener('click', function() {
            const prompt = document.getElementById('ai-prompt-input').value;
            const outputElement = document.getElementById('ai-output');
            const button = this;
            
            if (!prompt.trim()) {
                outputElement.textContent = 'Please enter some information about yourself first.';
                return;
            }
            
            // Show loading state
            outputElement.innerHTML = '<div class="loading"></div> Generating content...';
            button.disabled = true;
            
            // Simulate API call to backend (which would call ChatGPT)
            fetch('resume_builder.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'ai_generate=1&resume_data=' + encodeURIComponent(prompt)
            })
            .then(response => response.json())
            .then(data => {
                outputElement.textContent = data.content;
                document.getElementById('resume_data').value = data.content;
                parseResumeContent(data.content);
                button.disabled = false;
            })
            .catch(error => {
                outputElement.textContent = 'Error generating content. Please try again.';
                console.error('Error:', error);
                button.disabled = false;
            });
        });
        
        // Parse resume content and update preview
        function parseResumeContent(content) {
            const lines = content.split('\n');
            let currentSection = '';
            let summary = '';
            let education = [];
            let experience = [];
            let skills = [];
            
            for (const line of lines) {
                if (line.startsWith('PROFESSIONAL SUMMARY:')) {
                    currentSection = 'summary';
                    summary = line.replace('PROFESSIONAL SUMMARY:', '').trim();
                } else if (line.startsWith('EDUCATION:')) {
                    currentSection = 'education';
                } else if (line.startsWith('EXPERIENCE:')) {
                    currentSection = 'experience';
                } else if (line.startsWith('SKILLS:')) {
                    currentSection = 'skills';
                    const skillsText = line.replace('SKILLS:', '').trim();
                    skills = skillsText.split(',').map(skill => skill.trim());
                } else if (line.trim() !== '') {
                    if (currentSection === 'summary') {
                        summary += ' ' + line.trim();
                    } else if (currentSection === 'education') {
                        education.push(line.trim());
                    } else if (currentSection === 'experience') {
                        experience.push(line.trim());
                    }
                }
            }
            
            // Update preview
            if (summary) {
                document.getElementById('preview-summary').textContent = summary;
            }
            
            // Update education
            if (education.length >= 3) {
                const educationItems = document.querySelectorAll('.education-item');
                for (let i = 0; i < Math.min(education.length, educationItems.length); i++) {
                    const parts = education[i].split('-');
                    if (parts.length >= 2) {
                        educationItems[i].querySelector('.education-institution').textContent = parts[0].trim();
                        educationItems[i].querySelector('.education-date').textContent = parts[1].trim();
                    }
                }
            }
            
            // Update experience
            if (experience.length > 0) {
                const experienceItems = document.querySelectorAll('.experience-item');
                const firstExp = experience[0];
                const parts = firstExp.split('-');
                if (parts.length >= 2) {
                    experienceItems[0].querySelector('.experience-company').textContent = parts[0].trim();
                    experienceItems[0].querySelector('.experience-date').textContent = parts[1].trim();
                }
            }
            
            // Update skills
            if (skills.length > 0) {
                const skillsContainer = document.getElementById('preview-skills');
                skillsContainer.innerHTML = '';
                skills.forEach(skill => {
                    if (skill) {
                        const skillElement = document.createElement('span');
                        skillElement.className = 'skill-item';
                        skillElement.textContent = skill;
                        skillsContainer.appendChild(skillElement);
                    }
                });
            }
        }
        
        // Update resume preview when content changes
        document.getElementById('resume_data').addEventListener('input', function(e) {
            parseResumeContent(e.target.value);
        });
        
        // PDF Download functionality
        document.getElementById('download-pdf').addEventListener('click', function() {
            const element = document.getElementById('resume-preview');
            const options = {
                margin: 10,
                filename: 'my-resume.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };
            
            html2pdf().set(options).from(element).save();
        });
        
        // AI Enhance functionality
        document.getElementById('ai-enhance').addEventListener('click', function() {
            const content = document.getElementById('resume_data').value;
            if (!content.trim()) {
                alert('Please generate or enter some resume content first.');
                return;
            }
            
            const button = this;
            const originalText = button.innerHTML;
            
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enhancing...';
            button.disabled = true;
            
            // Simulate AI enhancement
            setTimeout(function() {
                // Enhance the content
                let enhancedContent = content;
                
                // Add more professional phrases
                enhancedContent = enhancedContent.replace(/developed/i, 'Designed and developed');
                enhancedContent = enhancedContent.replace(/managed/i, 'Oversaw and managed');
                enhancedContent = enhancedContent.replace(/created/i, 'Spearheaded creation of');
                
                // Add metrics where possible
                enhancedContent = enhancedContent.replace(/web applications/i, 'web applications, improving performance by 25%');
                enhancedContent = enhancedContent.replace(/team of/i, 'cross-functional team of');
                
                document.getElementById('resume_data').value = enhancedContent;
                parseResumeContent(enhancedContent);
                
                button.innerHTML = originalText;
                button.disabled = false;
                
                alert('Resume enhanced with AI! Your content has been improved for better impact.');
            }, 2000);
        });
        
        // Handle image upload preview
        document.getElementById('profile_image').addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const profileImageContainer = document.querySelector('.profile-image-container');
                    profileImageContainer.innerHTML = `<img src="${e.target.result}" alt="Profile" class="profile-image">`;
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    </script>
</body>
</html>