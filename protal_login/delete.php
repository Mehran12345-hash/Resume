<?php
$servername = "localhost";
$username = "root";
$password = "";
$db = "project_1212";

$conn = new mysqli ($servername, $username, $password, $db);

if($conn->connect_error){
    echo "Error";
}else{
    // echo "connected";  
}


$id = $_GET['id'];

$sql = "DELETE from project_1213 where id='$id'";

if($result = $conn->query($sql) === true){
    // echo "yes";
    header("location:list.php");
}else{
    echo "no";
}
?>