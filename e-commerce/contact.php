<?php
include_once("header.php");
?>

    <!-- request -->
    <div id="contact" class="request">
         <div class="container" style="margin-bottom: 50px;">
            <div class="row">
               <div class="col-md-12">
                  <div class="titlepage">
                     <h2>Any Questions???</h2>
                     <span>If you have any question or query feel free to ask, our team will contact you shortly, Thank you for visiting our site!!</span>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-sm-12">
                  <div class="black_bg">
                     <div class="row">
                        <div class="col-md-7 ">
                           <form class="main_form" action="do_contact.php" method="post">
                              <div class="row">
                                 <div class="col-md-12 ">
                                    <input class="contactus" placeholder="Name" type="text" name="name">
                                 </div>
                                 <div class="col-md-12">
                                    <input class="contactus" placeholder="Phone number" type="text" name="number">
                                 </div>
                                 <div class="col-md-12">
                                    <input class="contactus" placeholder="Email" type="text" name="email">
                                 </div>
                                 <div class="col-md-12">
                                    <textarea class="textarea" placeholder="Message" name="message"></textarea>
                                 </div>
                                 <div class="col-sm-12">
                                    <button class="send_btn" type="submit">Send</button>
                                 </div>
                              </div>
                           </form>
                        </div>
                        <div class="col-md-5">
                           <div class="mane_img">
                              <figure><img src="images/mane_img.jpg" alt="#"/></figure>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- end request -->
<?php 
     include_once("footer.php");
     ?>