<?php
require_once 'config.php';

// User functions
function getUserByEmail($email) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch();
}

function getUserById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function createUser($name, $email, $password, $role = 'user') {
    global $pdo;
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$name, $email, $hashed_password, $role]);
}

function updateUserProfile($user_id, $data) {
    global $pdo;
    
    // Check if profile exists
    $stmt = $pdo->prepare("SELECT id FROM user_profiles WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $profile_exists = $stmt->fetch();
    
    if ($profile_exists) {
        // Update existing profile
        $stmt = $pdo->prepare("UPDATE user_profiles SET headline = ?, summary = ?, skills = ?, education = ?, certifications = ?, languages = ?, website_url = ?, linkedin_url = ?, github_url = ? WHERE user_id = ?");
        return $stmt->execute([
            $data['headline'], $data['summary'], $data['skills'], $data['education'], 
            $data['certifications'], $data['languages'], $data['website_url'], 
            $data['linkedin_url'], $data['github_url'], $user_id
        ]);
    } else {
        // Create new profile
        $stmt = $pdo->prepare("INSERT INTO user_profiles (user_id, headline, summary, skills, education, certifications, languages, website_url, linkedin_url, github_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $user_id, $data['headline'], $data['summary'], $data['skills'], $data['education'], 
            $data['certifications'], $data['languages'], $data['website_url'], 
            $data['linkedin_url'], $data['github_url']
        ]);
    }
}

// Resume functions
function saveResume($user_id, $filename, $original_name, $file_size, $file_type) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO resumes (user_id, filename, original_name, file_size, file_type) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$user_id, $filename, $original_name, $file_size, $file_type]);
}

function getUserResumes($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM resumes WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

function updateResumeAnalysis($resume_id, $score, $feedback, $analysis_data) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE resumes SET score = ?, feedback = ?, analysis_data = ? WHERE id = ?");
    return $stmt->execute([$score, $feedback, json_encode($analysis_data), $resume_id]);
}

// Job application functions
function createJobApplication($user_id, $resume_id, $company_name, $job_title, $job_description, $application_date, $status = 'applied') {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO job_applications (user_id, resume_id, company_name, job_title, job_description, application_date, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$user_id, $resume_id, $company_name, $job_title, $job_description, $application_date, $status]);
}

function getUserJobApplications($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT ja.*, r.original_name as resume_name FROM job_applications ja JOIN resumes r ON ja.resume_id = r.id WHERE ja.user_id = ? ORDER BY ja.created_at DESC");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

// Cover letter functions
function saveCoverLetter($user_id, $job_application_id, $title, $content, $generated_content = null) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO cover_letters (user_id, job_application_id, title, content, generated_content) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$user_id, $job_application_id, $title, $content, $generated_content]);
}

function getUserCoverLetters($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT cl.*, ja.company_name, ja.job_title FROM cover_letters cl LEFT JOIN job_applications ja ON cl.job_application_id = ja.id WHERE cl.user_id = ? ORDER BY cl.created_at DESC");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

// Job match functions
function saveJobMatch($user_id, $resume_id, $job_title, $company, $job_description, $match_percentage, $skills_match, $missing_skills, $salary_low, $salary_high, $application_link) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO job_matches (user_id, resume_id, job_title, company, job_description, match_percentage, skills_match, missing_skills, salary_estimate_low, salary_estimate_high, application_link) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$user_id, $resume_id, $job_title, $company, $job_description, $match_percentage, $skills_match, $missing_skills, $salary_low, $salary_high, $application_link]);
}

function getUserJobMatches($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT jm.*, r.original_name as resume_name FROM job_matches jm JOIN resumes r ON jm.resume_id = r.id WHERE jm.user_id = ? ORDER BY jm.match_percentage DESC, jm.created_at DESC");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

// Notification functions
function addNotification($user_id, $title, $message, $type = 'info') {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO notifications (user_id, title, message, type) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$user_id, $title, $message, $type]);
}

function getUserNotifications($user_id, $unread_only = false) {
    global $pdo;
    $sql = "SELECT * FROM notifications WHERE user_id = ?";
    if ($unread_only) {
        $sql .= " AND is_read = FALSE";
    }
    $sql .= " ORDER BY created_at DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

function markNotificationAsRead($notification_id) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE notifications SET is_read = TRUE WHERE id = ?");
    return $stmt->execute([$notification_id]);
}

// Dashboard statistics
function getDashboardStats($user_id) {
    global $pdo;
    
    $stats = [];
    
    // Total resumes
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM resumes WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $stats['total_resumes'] = $stmt->fetch()['count'];
    
    // Average resume score
    $stmt = $pdo->prepare("SELECT AVG(score) as avg_score FROM resumes WHERE user_id = ? AND score IS NOT NULL");
    $stmt->execute([$user_id]);
    $stats['avg_resume_score'] = round($stmt->fetch()['avg_score'] ?? 0);
    
    // Total job applications
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM job_applications WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $stats['total_applications'] = $stmt->fetch()['count'];
    
    // Applications by status
    $stmt = $pdo->prepare("SELECT status, COUNT(*) as count FROM job_applications WHERE user_id = ? GROUP BY status");
    $stmt->execute([$user_id]);
    $stats['applications_by_status'] = $stmt->fetchAll();
    
    // Recent job matches
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM job_matches WHERE user_id = ? AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
    $stmt->execute([$user_id]);
    $stats['recent_job_matches'] = $stmt->fetch()['count'];
    
    return $stats;
}
?>