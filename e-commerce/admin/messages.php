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
                     <h2>All Messages </h2>
                     <!-- <span>Below are our best selling products, you can contact us to order one, or you can always visit our shop. You are always welcomed....</span> -->
                  </div>
               </div>
            </div>

            <div class="row">
                <div class="col-md-12">
<?php

$sql = "select * from messages";
$result = $conn->query($sql);

?>


                <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sr. No</th>
                <th>Name</th>
                <th>Number</th>
                <th>Email</th>
                <th>Message</th>
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
                    echo "<td>" . htmlspecialchars($row["number"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["message"]) . "</td>";
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