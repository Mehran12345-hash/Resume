<?php
include_once("header.php");
?>



  <!-- service section -->
  <?php 
include_once("connection.php");

// Corrected SQL query with FROM clause
$sql = "SELECT * FROM services";
$result = $conn->query($sql);

// Check if there are any rows returned
if ($result->num_rows > 0) {
    echo '<div class="container mt-5"><div class="row g-4">'; // Open container and row

    $count = 0; // Counter to track number of cards in a row
    
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        // Start a new row after every 3 cards
        if ($count % 3 == 0 && $count != 0) {
            echo '</div><div class="row g-4">'; // Close the previous row and start a new one
        }
        ?>
        
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <img src="admin/uploads/..<?php echo $row['Services_Img']; ?>" alt="Service Image" class="card-img-top">
                <div class="card-body text-center">
                    <h5 class="card-title"><?php echo $row['title']; ?></h5>
                    <p class="card-text"><?php echo $row['title2']; ?></p>
                    <a href="service.php" class="btn btn-primary" style{ margin-top:400px;}>Learn More</a>
                </div>
            </div>
        </div>

        <?php
        $count++; // Increment counter
    }
    echo '</div></div>'; // Close last row and container
} else {
    echo "<p>No records found</p>";
}
?>
<style>
  .card {
    margin:10px;
    min-height: 560px; /* Adjusted for better flexibility */
  }
  .card img {
    height: 250px;
    object-fit: cover;
  }
</style>
  <!-- end service section -->
 


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

</php>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Popper.js (required for Bootstrap 4's dropdowns, tooltips, and popovers) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
