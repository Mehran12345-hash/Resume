<?php
$servername = "localhost";
$username = "root";
$password = "";
$db = "project_1212";

$conn = new mysqli ($servername, $username, $password, $db);

if($conn->connect_error){
    echo "Error";
}else{
    echo "connected";  
}

$name = $_POST['name']; 
$email = $_POST['email'];
$phone = $_POST['phone'];
$city = $_POST['city'];
$password = $_POST['password'];

// echo $name . "<br>";
// echo $email . "<br>";
// echo $country . "<br>";
// echo $phone . "<br>";
// echo $password . "<br>";

$sql = "insert into project_1213 (name, email, phone, city, password) values('$name', '$email', '$phone' ,'$city', '$password')";

$result = $conn->query($sql);

if($result)
{ 
    echo "data insert successfully"; 
    header("location:list.php");

}else{
    echo "Error";
}



?>