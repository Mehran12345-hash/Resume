<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV Scoring - Professional Resume Analysis</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        /* All the CSS from your original code goes here */
        /* ... (truncated for brevity, but include all CSS) ... */
    </style>
</head>
<body>
    <!-- Header -->
    <header id="header">
        <div class="container header-container">
            <a href="#" class="logo">
                <span>MK</span>
                <h1>CV Scoring</h1>
            </a>
            
            <button class="nav-toggle" id="navToggle">
                <i class="fas fa-bars"></i>
            </button>
            
            <nav>
                <ul id="navMenu">
                    <li><a href="#home" class="active">Home</a></li>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#testimonials">Testimonials</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="login.php" class="cta-button">Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="container hero-content">
            <div class="hero-text">
                <h1 class="hero-title">AI-Powered Resume Analysis & Optimization</h1>
                <p class="hero-subtitle">Get your CV scored, generate tailored resumes, and prepare for job interviews with our advanced AI technology. Improve your chances of landing your dream job.</p>
                <div class="hero-buttons">
                    <button class="cta-button" onclick="location.href='login.php'">Analyze Your CV Now</button>
                    <button class="outline-button" onclick="scrollToSection('features')">How It Works</button>
                </div>
            </div>
            <div class="hero-image">
                <div class="floating-icons">
                    <div class="floating-icon"><i class="fas fa-file-alt"></i></div>
                    <div class="floating-icon"><i class="fas fa-search"></i></div>
                    <div class="floating-icon"><i class="fas fa-chart-line"></i></div>
                    <div class="floating-icon"><i class="fas fa-check-circle"></i></div>
                </div>
                <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="CV Analysis Dashboard">
            </div>
        </div>
    </section>


    <!-- Upload Section -->
    <section class="upload-section" id="upload">
        <div class="container">
            <div class="section-title">
                <h2>Analyze Your Resume</h2>
                <p>Upload your resume for instant feedback and improvement suggestions</p>
            </div>
            
            <div class="upload-container" id="upload-container">
                <div class="upload-icon">
                    <i class="fas fa-cloud-upload-alt"></i>
                </div>
                
                <h2 class="upload-title">Analyze Your Resume</h2>
                <p class="upload-subtitle">Login to access our AI-powered resume analysis tools</p>
                
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
                
                <div class="file-input-wrapper">
                    <button class="browse-btn" onclick="location.href='login.php'">Login to Get Started</button>
                </div>
                
                <p class="terms">By using our service, you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></p>
            </div>
        </div>
    </section>


    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <div class="section-title">
                <h2>Our Services</h2>
                <p>Powerful tools to boost your career prospects</p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h3>CV Scoring (ATS)</h3>
                    <p>Get your resume analyzed by our AI to ensure it passes through Applicant Tracking Systems.</p>
                    <a href="#" class="feature-link">Learn More</a>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <h3>Job Matching Score</h3>
                    <p>See how well your resume matches specific job descriptions and get improvement suggestions.</p>
                    <a href="#" class="feature-link">Learn More</a>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-envelope-open-text"></i>
                    </div>
                    <h3>Cover Letter Generator</h3>
                    <p>Create personalized, professional cover letters tailored to specific job applications.</p>
                    <a href="#" class="feature-link">Learn More</a>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Salary Estimator</h3>
                    <p>Find out what salary range you should expect based on your experience and skills.</p>
                    <a href="#" class="feature-link">Learn More</a>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-magic"></i>
                    </div>
                    <h3>AI Resume Builder</h3>
                    <p>Create professional, ATS-friendly resumes from scratch with our AI-powered builder.</p>
                    <a href="#" class="feature-link">Learn More</a>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <h3>Interview Coach</h3>
                    <p>Practice and prepare for interviews with our AI-powered coaching system.</p>
                    <a href="#" class="feature-link">Notify Me</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials" id="testimonials">
        <div class="container">
            <div class="section-title">
                <h2>Success Stories</h2>
                <p>Hear from people who landed their dream jobs with our help</p>
            </div>
            
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <div class="testimonial-text">
                        "After using CV Scoring, I completely revamped my resume and got callbacks from 3 out of 5 companies I applied to. Landed my dream job at Google within a month!"
                    </div>
                    <div class="testimonial-author">
                        <img src="https://randomuser.me/api/portraits/women/65.jpg" alt="Sarah Johnson" class="testimonial-avatar">
                        <div class="testimonial-info">
                            <h4>Sarah Johnson</h4>
                            <p>Software Engineer at Google</p>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-text">
                        "The job matching feature helped me tailor my resume for each application. I went from no responses to multiple interview requests in just two weeks."
                    </div>
                    <div class="testimonial-author">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Michael Chen" class="testimonial-avatar">
                        <div class="testimonial-info">
                            <h4>Michael Chen</h4>
                            <p>Marketing Manager at Airbnb</p>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-text">
                        "The cover letter generator saved me hours of work. I was able to apply to twice as many jobs with personalized cover letters for each position."
                    </div>
                    <div class="testimonial-author">
                        <img src="https://randomuser.me/api/portraits/women/45.jpg" alt="Jessica Williams" class="testimonial-avatar">
                        <div class="testimonial-info">
                            <h4>Jessica Williams</h4>
                            <p>Product Designer at Shopify</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about" id="about">
        <div class="container">
            <div class="section-title">
                <h2>About Us</h2>
                <p>Learn more about our mission and team</p>
            </div>
            
            <div class="about-content">
                <div class="about-text">
                    <h2>Our Mission</h2>
                    <p>At CV Scoring, we believe that everyone deserves to find a job they love. Our mission is to empower job seekers with AI-powered tools that help them present their best selves to potential employers.</p>
                    <p>We combine cutting-edge artificial intelligence with expert HR knowledge to provide actionable insights that improve your chances of landing your dream job.</p>
                </div>
                <div class="about-image">
                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1171&q=80" alt="Our Team">
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-about">
                    <div class="footer-logo">
                        <a href="#" class="logo">
                            <span>MK</span>
                            <h1>CV Scoring</h1>
                        </a>
                    </div>
                    <p>Find the best talent faster with AI-driven resume screening and filtering.</p>
                </div>
                
                <div class="footer-services">
                    <h3 class="footer-heading">Services</h3>
                    <ul class="footer-links">
                        <li><a href="#">CV Scoring (ATS)</a></li>
                        <li><a href="#">Job Matching Score</a></li>
                        <li><a href="#">Cover Letter Generator</a></li>
                        <li><a href="#">Salary Estimator</a></li>
                        <li><a href="#">AI Resume Builder</a></li>
                    </ul>
                </div>
                
                <div class="footer-resources">
                    <h3 class="footer-heading">Resources</h3>
                    <ul class="footer-links">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                    </ul>
                </div>
                
                <div class="footer-contact">
                    <h3 class="footer-heading">Contact</h3>
                    <p><i class="fas fa-envelope"></i>mk2729517@gmail.com</p>
                    <p><i class="fas fa-phone"></i> +92 3328679560</p>
                    <p><i class="fas fa-map-marker-alt"></i> Software Engineering, CECOS University, Peshawar - Pakistan</p>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>© <span id="currentYear"></span> CVScoring. All rights reserved.</p>
                <div class="footer-legal">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                    <a href="#">Cookie Policy</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button class="back-to-top" id="backToTop">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script>
        // Set current year in footer
        document.getElementById('currentYear').textContent = new Date().getFullYear();
        
        // Header scroll effect
        const header = document.getElementById('header');
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
        
        // Mobile navigation toggle
        const navToggle = document.getElementById('navToggle');
        const navMenu = document.getElementById('navMenu');
        
        navToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
        });
        
        // Close mobile menu when clicking on a link
        const navLinks = document.querySelectorAll('nav a');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                navMenu.classList.remove('active');
            });
        });
        
        // Back to top button
        const backToTop = document.getElementById('backToTop');
        
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                backToTop.classList.add('visible');
            } else {
                backToTop.classList.remove('visible');
            }
        });
        
        backToTop.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });
        
        // Set active navigation link based on scroll position
        window.addEventListener('scroll', function() {
            const sections = document.querySelectorAll('section');
            const navLinks = document.querySelectorAll('nav a');
            
            let currentSection = '';
            
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                
                if (window.scrollY >= sectionTop - 100) {
                    currentSection = section.getAttribute('id');
                }
            });
            
            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === '#' + currentSection) {
                    link.classList.add('active');
                }
            });
        });
        
        // Helper function to scroll to section
        function scrollToSection(sectionId) {
            const targetSection = document.getElementById(sectionId);
            if (targetSection) {
                window.scrollTo({
                    top: targetSection.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        }
    </script>
</body>
</html>