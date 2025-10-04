<?php
include_once("conn.php");


$name = $_POST['name'];
$password = $_POST['password'];


$sql = "select * from project_a where name='$name' and password='$password'";


$result = $conn->query($sql);

if($result->num_rows > 0){
    session_start();
    $_SESSION['login'] = true;
    header("location:khan.php");
}else{
    header("location:login_form.php");
}

?>