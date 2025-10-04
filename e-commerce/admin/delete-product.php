<?php

include_once("conn.php");


$id = $_GET['id'];

$sql = "delete from add_item where id='$id'";
$result = $conn->query($sql);

if($result){
    header("location:products.php");
}

?>