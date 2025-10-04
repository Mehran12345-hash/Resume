<?php
$servername = "localhost";
$username = "root";
$password = "";
$db = "project";

$conn = new mysqli ($servername, $username, $password, $db);

if($conn->connect_error){
    echo "Error";
}else{
    // echo "connected";
   
}




?>