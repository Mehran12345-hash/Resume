<?php
include_once("conn.php");


$name = $_POST['name'];
$number = $_POST['number'];
$email = $_POST['email'];
$message = $_POST['message'];



$sql = "insert into messages (name, email, number, message) values ('$name', '$email', '$number', '$message')";

$result = $conn->query($sql);

if($result){
    header("location:contact.php");
}
?>