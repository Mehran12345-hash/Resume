<?php
include_once("conn.php");
include_once("header.php");



?>


<?php
if(isset($_GET['id']) && !empty($_GET['id']) ){
    
    $id = $_GET['id'];

    $sql = "select * from products where id = '$id'";
    $result = $conn->query($sql);

    $row = $result->fetch_array();
    
    ?>


<div id="contact" class="request">
         <div class="container" style="margin-bottom: 50px;">
      
            <div class="row">
               <div class="col-sm-12">
                  <div class="black_bg">
                     <div class="row">
                        <div class="col-md-7 ">
                           <form class="main_form" action="do_add_product.php" method="post">
                              <div class="row">
                                 <div class="col-md-12 ">
                                    <input class="contactus" value="<?php echo $row['name']; ?>" placeholder="Product Name" type="text" name="name">
                                    <input class="contactus" value="<?php echo $row['id']; ?>" placeholder="Product Name" type="hidden" name="id">
                                 </div>
                                 <div class="col-md-12">
                                    <input class="contactus" value="<?php echo $row['description']; ?>" placeholder="Product Description" type="text" name="description">
                                 </div>
                                 <div class="col-sm-12">
                                    <button class="send_btn" type="submit">Update Product</button>
                                 </div>
                              </div>
                           </form>
                        </div>
                        <div class="col-md-5">
                           <div class="mane_img">
                              <figure><img src="../images/top_img.png" alt="#"/></figure>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>



<?php }else{


?>
  <!-- request -->
  <div id="contact" class="request">
         <div class="container" style="margin-bottom: 50px;">
      
            <div class="row">
               <div class="col-sm-12">
                  <div class="black_bg">
                     <div class="row">
                        <div class="col-md-7 ">
                           <form class="main_form" action="do_add_product.php" method="post">
                              <div class="row">
                                 <div class="col-md-12 ">
                                    <input class="contactus" placeholder="Product Name" type="text" name="name">
                                 </div>
                                 <div class="col-md-12">
                                    <input class="contactus" placeholder="Product Description" type="text" name="description">
                                 </div>
                                 <div class="col-sm-12">
                                    <button class="send_btn" type="submit">Add Product</button>
                                 </div>
                              </div>
                           </form>
                        </div>
                        <div class="col-md-5">
                           <div class="mane_img">
                              <figure><img src="../images/top_img.png" alt="#"/></figure>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- end request -->


<?php } ?>
<?php
include_once("footer.php");
?>