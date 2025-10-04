<?php
include_once("header.php");
?>


    <!-- end header section -->
    <!-- slider section -->
    <section class="slider_section ">
      <div id="customCarousel1" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <div class="container ">
              <div class="row">
                <div class="col-md-6 ">
                  <div class="detail-box">
<?php 
include_once("connection.php");

// Corrected SQL query with FROM clause
$sql = "SELECT * FROM header_update";

// Execute the query
$result = $conn->query($sql);

// Check if there are any rows returned
if ($result->num_rows > 0) {
    // Loop through each row in the result set
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        ?>
        <h1>
            <?php echo $row['heading']; ?>
        </h1>
        <p>
            <?php echo $row['paragrap']; ?>
        </p>

                    
                    <div class="btn-box">
                      <a href="" class="btn1">
                        Read More
                      </a>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="img-box">
                  <img src="uploads/<?php echo $row['heading_image1']; ?>" alt="About Image">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="carousel-item ">
            <div class="container ">
              <div class="row">
                <div class="col-md-6 ">
                  <div class="detail-box">

        <h1>
            <?php echo $row['heading_2nd']; ?>
        </h1>
        <p>
            <?php echo $row['paragrap_2nd']; ?>
        </p>

                    <div class="btn-box">
                      <a href="" class="btn1">
                        Read More
                      </a>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="img-box">
                  <img src="uploads/<?php echo $row['heading_image2']; ?>" alt="About Image">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <div class="container ">
              <div class="row">
                <div class="col-md-6 ">
                  <div class="detail-box">
 
        <h1>
            <?php echo $row['heading_3rd']; ?>
        </h1>
        <p>
            <?php echo $row['paragrap_3rd']; ?>
        </p>


                    <div class="btn-box">
                      <a href="" class="btn1">
                        Read More
                      </a>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="img-box">
                  <img src="uploads/<?php echo $row['heading_image3']; ?>" alt="About Image">
                  </div>
                </div>
<?php 
    }
} else {
    echo "<p>No records found</p>";
}

// Close the database connection
// $conn->close();
?>
              </div>
            </div>
          </div>
        </div>
        <ol class="carousel-indicators">
          <li data-target="#customCarousel1" data-slide-to="0" class="active"></li>
          <li data-target="#customCarousel1" data-slide-to="1"></li>
          <li data-target="#customCarousel1" data-slide-to="2"></li>
        </ol>
      </div>

    </section>
    <!-- end slider section -->
  </div>

  <div class="container mt-4">
  <div class="container my-5">
  <h1 class="text-center fw-bold" id="Services" style="color: #003366; font-size: 50px; font-weight: bold;">Services</h1>
  <p class="text-center mt-3 fs-5" style="color:rgb(52, 94, 137); ">
    We offer professional services including <strong>Web Development</strong>, 
    <strong>SEO Optimization</strong>, and <strong>WordPress Development</strong> 
    to help your business grow online.
  </p>
</div>

    <div class="row g-4">  
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100 d-flex flex-column">
                <img src="images/seo.png" alt="Service Image" class="card-img-top">
                <div class="card-body text-center d-flex flex-column">
                    <h5 class="card-title">SEO Developer</h5>
                    <p class="card-text flex-grow-1">An SEO Developer (Search Engine Optimization Developer) improves website rankings on Google, Bing, and other search engines. They focus on keyword research, site speed, mobile-friendliness, and technical SEO to boost visibility and attract more visitors.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100 d-flex flex-column">
                <img src="images/graphic-desiner.png" alt="Service Image" class="card-img-top">
                <div class="card-body text-center d-flex flex-column">
                    <h5 class="card-title">Graphic Design</h5>
                    <p class="card-text flex-grow-1">Graphic design is the art of visually communicating ideas using typography, imagery, and layout. It involves creating logos, branding, marketing materials, and digital content. Graphic designers use tools like Adobe Photoshop and Illustrator to craft comp</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100 d-flex flex-column">
                <img src="images/custom-website.png" alt="Service Image" class="card-img-top">
                <div class="card-body text-center d-flex flex-column">
                    <h5 class="card-title">Custom Web Developer & wordpress Developer</h5>
                    <p class="card-text flex-grow-1">A custom web developer builds tailored websites to meet specific business needs. They use coding languages like HTML, CSS, JavaScript, and PHP to create unique, scalable, and high-performance web solutions. Their focus is on functionality, design, and use</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        min-height: 400px; /* Ensures all cards have the same height */
        transition: transform 0.3s ease-in-out; /* Smooth hover effect */
    }

    .card:hover {
        transform: translateY(-5px); /* Slight lift effect on hover */
    }

    .card-img-top {
        height: 200px; /* Fixed height for images */
        object-fit: cover; /* Ensures image fills space properly */
    }
</style>




    <!-- Single Learn More Button (Centered Below Cards) -->
    <div class="btn-container">
    <a href="service.php" class="btn btn-primary">Learn More</a>
</div>

<style>
/* Centering the button */
.btn-container {
    display: flex;
    justify-content: center;
    margin: 40px;
}

/* Button Styling */
.btn-container a {
    font-size: 15px;
    padding: 15px 40px;
    background-color: #00BBF0;
    color: white;
    text-decoration: none;
    font-weight: bold;
    transition: all 0.3s ease-in-out;
   text-align: center;
}

/* Hover Effect */
.btn-container a:hover {
  background-color:rgb(17, 155, 193);
  }

</style>


</style>


 
  <!-- end service section -->


  <!-- about section -->

  <section class="about_section layout_padding">
    <div class="container  ">
      <div class="heading_container heading_center">
        <h2>
          About <span>Us</span>
        </h2>
        <?php 
include_once("connection.php");

// Corrected SQL query with FROM clause
$sql = "SELECT * FROM about_update";

// Execute the query
$result = $conn->query($sql);

// Check if there are any rows returned
if ($result->num_rows > 0) {
    // Loop through each row in the result set
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        ?>
        <p>
        <?php echo $row['about_title']; ?>
        </p>
      </div>
      <div class="row">
        <div class="col-md-6 ">
          <div class="img-box">
          <img src="images/<?php echo $row['about_image']; ?>" alt="About Image">
          </div>
        </div>
        <div class="col-md-6">
          <div class="detail-box">
            <h3>
            <?php echo $row['about_heading']; ?>
            </h3>
            <p>
            <?php echo $row['about_paragrap']; ?>
            </p>
            <p>
            <?php echo $row['about_paragrap1']; ?>
    
          </p>
            <a href="">
              Read More
            </a>
          </div>
          <?php 
        }
} else {
    echo "<p>No records found</p>";
}

// Close the database connection
// $conn->close();
?>
        </div>
      </div>
    </div>
  </section>

  <!-- end about section -->

  <!-- why section -->

  <section class="why_section layout_padding">
    <div class="container">
      <div class="heading_container heading_center">
    
      </div>
    </div>
  </section>

  <!-- end why section -->

   
<?php 
include_once("connection.php");

// Corrected SQL query with FROM clause
$sql = "SELECT * FROM teams";

// Execute the query
$result = $conn->query($sql);

// Check if there are any rows returned
if ($result->num_rows > 0) {
    // Loop through each row in the result set
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        ?>
        <!-- team section -->
   <section class="team_section layout_padding">
    <div class="container-fluid">
      <div class="heading_container heading_center">
        <h2 class="">
          Our <span> Team</span>
        </h2>
      </div>
      <div class="team_container">
        <div class="row">
          <div class="col-lg-3 col-sm-6">
            <div class="box ">
              <div class="img-box">
              <img src="images/<?php echo $row['teamimage1']; ?>" alt="About Image">
              </div>
              <div class="detail-box">
                <h5>
                <?php echo $row['Team_Member_Name1']; ?>
                </h5>
                <p>
                <?php echo $row['title1']; ?>
                </p>
              </div>
              <div class="social_box">
               
              </div>
            </div>
          </div>

       <div class="col-lg-3 col-sm-6">
            <div class="box ">
              <div class="img-box">
              <img src="images/<?php echo $row['teamimage2']; ?>" alt="About Image">
              </div>
              <div class="detail-box">
                <h5>
                <?php echo $row['Team_Member_Name2']; ?><br>
                  </h5>
                <p>
                <?php echo $row['title2']; ?><br>
                </p>
              </div>
              <div class="social_box"> 
               
               </div>
            </div>
          </div>
          <div class="col-lg-3 col-sm-6">
            <div class="box ">
              <div class="img-box">
              <img src="images/<?php echo $row['teamimage3']; ?>" alt="About Image">
              </div>
              <div class="detail-box">
                <h5>
                <?php echo $row['Team_Member_Name3']; ?>
                </h5>
                <p>
                <?php echo $row['title3']; ?>
                </p>
              </div>
              <div class="social_box"> 
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-sm-6">
            <div class="box ">
              <div class="img-box">
              <img src="images/<?php echo $row['teamimage4']; ?>" alt="About Image">
              </div>
              <div class="detail-box">
                <h5>
                <?php echo $row['Team_Member_Name4']; ?><br>
                </h5>
                <p>              
                  <?php echo $row['title4']; ?>
                </p>
              </div>
              <div class="social_box"> 
                
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
   <!-- end team section  -->

  <?php 
        }
} else {
    echo "<p>No records found</p>";
}

// Close the database connection
// $conn->close();
?>
 

  <!-- client section -->

  <section class="client_section layout_padding">
    <div class="container">
      <div class="heading_container heading_center psudo_white_primary mb_45">
        <h2>
          What says our <span>Customers</span>
        </h2>
      </div>
      <div class="carousel-wrap ">
        <div class="owl-carousel client_owl-carousel">
          <div class="item">
            <div class="box">
              <div class="img-box">
                <img src="images/musa.png" alt="" class="box-img">
              </div>
              <div class="detail-box">
                <div class="client_id">
                  <div class="client_info">
                    <h6>
                    Musa Khan
                    </h6>
                    <p>
                    Social Media Marketing 
                    </p>
                  </div>
                  <i class="fa fa-quote-left" aria-hidden="true"></i>
                </div>
                <p>
                "Working with Mehran was amazing. He developed a responsive, modern website that was delivered on time. His expertise in web development and professionalism made a huge difference for our online presence."</p>
              </div>
            </div>
          </div>
          <div class="item">
            <div class="box">
              <div class="img-box">
                <img src="images/fai.png" alt="" class="box-img">
              </div>
              <div class="detail-box">
                <div class="client_id">
                  <div class="client_info">
                    <h6>
                      Faisal Khan
                    </h6>
                    <p>
                      Graphics Designer
                    </p>
                  </div>
                  <i class="fa fa-quote-left" aria-hidden="true"></i>
                </div>
                <p>
                "Mehran created a stunning website that reflects our brand perfectly. His attention to detail and clear communication throughout the process made everything run smoothly. Highly recommend his professional web development services."</p>
              </div>
            </div>
          </div>
          
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- end client section -->

<!-- Info Section -->
<section class="info_section layout_padding2">
  <div class="container">
    <div class="row">
      <!-- Contact Info -->
      <div class="col-md-6 col-lg-3 info_col">
        <div class="info_contact">
          <h4>Address</h4>
          <div class="contact_link_box">
            <a href="https://www.google.com/maps/place/Peshawar+Hayatabad,+Pakistan" target="_blank">
              <i class="fa fa-map-marker" aria-hidden="true"></i>
              <span>Peshawar Hayatabad, Pakistan</span>
            </a>
            <a href="tel:+923479557104">
              <i class="fa fa-phone" aria-hidden="true"></i>
              <span>Call: +92 347 9557104</span>
            </a>
            <a href="mailto:mkdeveloper123098@gmail.com?subject=Web%20Development%20Services%20Inquiry&body=Hi%20Mehran%2C%20I'm%20interested%20in%20your%20services.%20I%20would%20like%20to%20discuss%20a%20custom%20website.%20Can%20you%20please%20share%20more%20details%20about%20your%20web%20development%2C%20SEO%2C%20and%20WordPress%20services%3F">
              <i class="fa fa-envelope" aria-hidden="true"></i>
              <span>mkdeveloper123098@gmail.com</span>
            </a>

            <!-- WhatsApp Button -->
            <a href="https://wa.me/923479557104?text=Hi%20Mehran%2C%20I'm%20interested%20in%20your%20services.%20I%20would%20like%20to%20discuss%20a%20custom%20website.%20Can%20you%20please%20share%20more%20details%20about%20your%20web%20development%2C%20SEO%2C%20and%20WordPress%20services%3F" 
               target="_blank" 
               style="color: #25D366; font-weight: bold;">
              <i class="fa fa-whatsapp" aria-hidden="true"></i>
              <span>Contact on WhatsApp</span>
            </a>
          </div>
        </div>
        <div class="info_social">
          <a href="https://www.facebook.com/mkdeveloper.12" target="_blank">
            <i class="fa fa-facebook" aria-hidden="true"></i>
          </a>
          <a href="https://www.linkedin.com/posts/mehran-khan-khan-b549a0358_web-developer-activity-7310799338266337280-Gkgr?utm_source=share&utm_medium=member_android&rcm=ACoAAFkinHABVzoJcFiEr12HQBTbRk5nxs5Ojy8" target="_blank">
            <i class="fa fa-linkedin" aria-hidden="true"></i>
          </a>
          <a href="https://www.instagram.com/mk_mehrankhan_47?igsh=NTdhYnN4aHdmeTdt" target="_blank">
  <i class="fa fa-instagram" aria-hidden="true"></i>
</a>
        </div>
      </div>

      <!-- Info Description -->
      <div class="col-md-6 col-lg-3 info_col">
        <div class="info_detail">
          <h4>Info</h4>
          <p>
            I specialize in Custom Web Development, SEO Development, and WordPress Development. Let's build something amazing together.
          </p>
        </div>
      </div>

      <!-- Links -->
      <div class="col-md-6 col-lg-2 mx-auto info_col">
        <div class="info_link_box">
          <h4>Links</h4>
          <div class="info_links">
            <a class="active" href="index.php">Home</a>
            <a href="about.php">About</a>
            <a href="service.php">Services</a>
            <a href="contact-form.php">Contact Us</a>
            <a href="team.php">Team</a>
            <a href="admin-login.php">Login</a>
          </div>
        </div>
      </div>

      <!-- Contact Us Title (empty column, can be used for additional info later) -->
      <div class="col-md-6 col-lg-3 info_col">
      </div>
    </div>
  </div>
</section>
<!-- End Info Section -->

<!-- Footer Section -->
<section class="footer_section">
  <div class="container">
    <p>
      &copy; <span id="displayYear"></span> MKdeveloper.pk - All Rights Reserved.
      <br>Developed By Mehran Khan
    </p>
  </div>
</section>

<script>
  // Display the current year dynamically
  document.getElementById("displayYear").textContent = new Date().getFullYear();
</script>

  <!-- jQery -->
  <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
  <!-- popper js -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
  </script>
  <!-- bootstrap js -->
  <script type="text/javascript" src="js/bootstrap.js"></script>
  <!-- owl slider -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js">
  </script>
  <!-- custom js -->
  <script type="text/javascript" src="js/custom.js"></script>
  <!-- Google Map -->
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCh39n5U-4IoWpsVGUHWdqB6puEkhRLdmI&callback=myMap">
  </script>
  <!-- End Google Map -->

</body>

</php>