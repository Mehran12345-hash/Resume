<?php
include_once("conn.php");
include_once("header.php");
?>




 <!-- best -->
 <div id="" class="best">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="titlepage">
                     <h2>All Products </h2><a class="btn btn-primary" href="add-product.php">Add Product</a>
                     <!-- <span>Below are our best selling products, you can contact us to order one, or you can always visit our shop. You are always welcomed....</span> -->
                  </div>
               </div>
            </div>

            <div class="row">
                <div class="col-md-12">
<?php

$sql = "select * from products";
$result = $conn->query($sql);

?>


                <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sr. No</th>
                <th>Name</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                $i = 1;
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $i++ . "</td>";
                    echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["description"]) . "</td>";
                    echo "<td> <a class='btn btn-primary' href='add-product.php?id=". $row['id'] ."'>Edit</a>   <a class='btn btn-danger' href='delete-product.php?id=". $row['id'] ."'>Delete</a> </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No results found</td></tr>";
            }
            ?>
        </tbody>
    </table>


                </div>
            </div>
            
         </div>
      </div>
      <!-- end best -->





<?php
include_once("footer.php");
?>