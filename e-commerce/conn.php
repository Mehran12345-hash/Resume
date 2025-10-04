<?php

$servername = "localhost";
$username = "root";
$password = "";
$db = "e_commerce";

$conn = new mysqli($servername, $username, $password, $db);

if($conn->connect_error){
    echo "connection error";
}else {
    // echo "Conntected successfully";
}

?>