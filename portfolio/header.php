<!DOCTYPE php>
<php>

<head>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!-- Site Metas -->
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <link rel="shortcut icon" href="images/favicon.png" type="">

  <title>MK</title>

  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

  <!-- fonts style -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">

  <!--owl slider stylesheet -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />

  <!-- font awesome style -->
  <link href="css/font-awesome.min.css" rel="stylesheet" />

  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet" />
  <!-- responsive style -->
  <link href="css/responsive.css" rel="stylesheet" />

</head>

<body>

  <div class="hero_area">

    <div class="hero_bg_box">
      <div class="bg_img_box">
        <img src="images/hero-bg.png" alt="">
      </div>
    </div>

    <!-- header section starts -->
    <header class="header_section">
      <div class="container-fluid">
        <nav class="navbar navbar-expand-lg custom_nav-container">
          <a class="navbar-brand" href="index.php">
            <span style="margin-left: 10px;" >MK</span>
          </a>

          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class=""></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" href="index.php">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="about.php">About</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="service.php">Services</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="team.php">Team</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="contact-form.php">Contact Us</a>
              </li>
            </ul>
          </div>
        </nav>
      </div>
    </header>
    <!-- end header section -->

    <style>
      /* Navbar base styling */
      .navbar {
        background-color: rgba(0, 5, 102, 0.8); /* semi-transparent dark */
        transition: top 0.3s;
        padding: 10px 0; /* Remove horizontal padding */
        z-index: 1000;
        position: fixed;
        width: 100%;
        top: 0;
        left: 0; /* Ensure it stretches from the left edge */
        right: 0; /* Ensure it stretches to the right edge */
      }

      /* Navbar brand style */
      .navbar-brand span {
        font-size: 28px;
        font-weight: 700;
        color: #ffffff;
        letter-spacing: 2px;
      }

      .navbar-brand span:hover {
        color:rgb(29, 255, 213);
      }

      /* Navbar links */
      .navbar-nav .nav-link {
        color:rgb(255, 255, 255) !important;
        font-size: 16px;
        font-weight: 500;
        padding: 10px 15px;
        transition: color 0.3s, background 0.3s;
      }

      .navbar-nav .nav-link:hover {
        color:rgb(29, 255, 213) !important;
        background-color: rgba(0, 5, 102, 0.8);
        border-radius: 5px;
      }

      /* Active page link styling */
      .navbar-nav .nav-item.active .nav-link {
        color:rgb(0, 237, 237) !important;
        font-weight: bold;
      }

      /* Navbar toggler (hamburger icon) */
      .navbar-toggler {
        border: none;
        outline: none;
      }

      .navbar-toggler-icon {
        background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='white' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
      }

      /* Adjust collapse container on small devices */
      @media (max-width: 991px) {
        .navbar-collapse {
          background-color: rgba(0, 5, 102, 0.8);
          padding: 15px;
        }

        .navbar-nav .nav-item {
          margin-bottom: 10px;
        }
      }
    </style>

    <script>
      // Scroll effect for navbar
      let lastScrollTop = 0;
      window.addEventListener("scroll", function () {
        let currentScroll = window.pageYOffset || document.documentElement.scrollTop;

        if (currentScroll > lastScrollTop) {
          document.querySelector('.navbar').style.top = "-60px";
        } else {
          document.querySelector('.navbar').style.top = "0";
        }

        lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
      });

      // Add active class to the current page
      const navLinks = document.querySelectorAll('.nav-link');
      navLinks.forEach(link => {
        if (window.location.href.includes(link.getAttribute('href'))) {
          link.closest('.nav-item').classList.add('active');
        }
      });

      // Mobile Hamburger Menu Toggle
      function toggleMenu() {
        const navLinks = document.querySelector('.navbar-collapse');
        navLinks.classList.toggle('show');
      }
    </script>

</body>
</html>
