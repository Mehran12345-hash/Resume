<?php
// Database configuration
$host = 'localhost';
$dbname = 'cvscoring';
$username = 'root';
$password = '';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Create tables if they don't exist
    $tables_sql = [
        "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role ENUM('admin', 'user') DEFAULT 'user',
            avatar_url VARCHAR(255) DEFAULT NULL,
            phone VARCHAR(20) DEFAULT NULL,
            location VARCHAR(100) DEFAULT NULL,
            profession VARCHAR(100) DEFAULT NULL,
            experience_level ENUM('entry', 'mid', 'senior', 'executive') DEFAULT 'mid',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",
        
        "CREATE TABLE IF NOT EXISTS user_profiles (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            headline VARCHAR(255) DEFAULT NULL,
            summary TEXT DEFAULT NULL,
            skills TEXT DEFAULT NULL,
            education TEXT DEFAULT NULL,
            certifications TEXT DEFAULT NULL,
            languages TEXT DEFAULT NULL,
            website_url VARCHAR(255) DEFAULT NULL,
            linkedin_url VARCHAR(255) DEFAULT NULL,
            github_url VARCHAR(255) DEFAULT NULL,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )",
        
        "CREATE TABLE IF NOT EXISTS resumes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            filename VARCHAR(255) NOT NULL,
            original_name VARCHAR(255) NOT NULL,
            file_size INT NOT NULL,
            file_type VARCHAR(10) NOT NULL,
            score INT DEFAULT NULL,
            feedback TEXT DEFAULT NULL,
            analysis_data JSON DEFAULT NULL,
            is_public BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )",
        
        "CREATE TABLE IF NOT EXISTS job_applications (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            resume_id INT NOT NULL,
            company_name VARCHAR(100) NOT NULL,
            job_title VARCHAR(100) NOT NULL,
            job_description TEXT DEFAULT NULL,
            application_date DATE NOT NULL,
            status ENUM('applied', 'interview', 'rejected', 'offer', 'accepted') DEFAULT 'applied',
            notes TEXT DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (resume_id) REFERENCES resumes(id) ON DELETE CASCADE
        )",
        
        "CREATE TABLE IF NOT EXISTS cover_letters (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            job_application_id INT DEFAULT NULL,
            title VARCHAR(255) NOT NULL,
            content TEXT NOT NULL,
            generated_content TEXT DEFAULT NULL,
            score INT DEFAULT NULL,
            feedback TEXT DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (job_application_id) REFERENCES job_applications(id) ON DELETE SET NULL
        )",
        
        "CREATE TABLE IF NOT EXISTS interview_preparations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            job_application_id INT DEFAULT NULL,
            questions TEXT NOT NULL,
            answers TEXT DEFAULT NULL,
            feedback TEXT DEFAULT NULL,
            score INT DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (job_application_id) REFERENCES job_applications(id) ON DELETE SET NULL
        )",
        
        "CREATE TABLE IF NOT EXISTS job_matches (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            resume_id INT NOT NULL,
            job_title VARCHAR(100) NOT NULL,
            company VARCHAR(100) NOT NULL,
            job_description TEXT NOT NULL,
            match_percentage INT NOT NULL,
            skills_match TEXT DEFAULT NULL,
            missing_skills TEXT DEFAULT NULL,
            salary_estimate_low DECIMAL(10, 2) DEFAULT NULL,
            salary_estimate_high DECIMAL(10, 2) DEFAULT NULL,
            application_link VARCHAR(255) DEFAULT NULL,
            is_applied BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (resume_id) REFERENCES resumes(id) ON DELETE CASCADE
        )",
        
        "CREATE TABLE IF NOT EXISTS notifications (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            title VARCHAR(255) NOT NULL,
            message TEXT NOT NULL,
            type ENUM('info', 'success', 'warning', 'error') DEFAULT 'info',
            is_read BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )",
        
        "CREATE TABLE IF NOT EXISTS user_settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            email_notifications BOOLEAN DEFAULT TRUE,
            resume_analysis_notifications BOOLEAN DEFAULT TRUE,
            job_match_notifications BOOLEAN DEFAULT TRUE,
            dark_mode BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )"
    ];
    
    foreach ($tables_sql as $sql) {
        $pdo->exec($sql);
    }
    
    // Check if admin user exists, if not create one
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE role = 'admin'");
    $stmt->execute();
    $admin_count = $stmt->fetchColumn();
    
    if ($admin_count == 0) {
        $hashed_password = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, profession, experience_level) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute(['Admin User', 'admin@cvscoring.com', $hashed_password, 'admin', 'Administrator', 'senior']);
    }
    
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>