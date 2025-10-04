<?php

include_once("conn.php");

$username = $_POST['username'];
$password = $_POST['password'];


$sql = "select * from admin where username='$username' and password = '$password'";

$result = $conn->query($sql);

if($result->num_rows > 0){
    session_start();
    $_SESSION['login'] = true;
    header("location:dashboard.php");
}else{
    header("location:singin.php");
}

?>
