<?php

include_once("conn.php");

$username = $_POST['email'];
$password = $_POST['password'];

$password = md5($password);

$sql = "select * from admin where email='$username' and password = '$password'";

$result = $conn->query($sql);

if($result->num_rows > 0){
    session_start();
    $_SESSION['login'] = true;
    header("location:admin/dashboard.php");
}else{
    header("location:login.php");
}

?>
