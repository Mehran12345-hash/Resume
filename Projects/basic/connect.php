<?php
$servername = "localhost";
$username = "root";
$password ="";
$db = "";
  $conn = new mysqli($servername, $username, $password, $db);
if($conn){
    echo "connection_error";
}else{
    echo "connection susseccfully";
}
$name = $_POST['name'];
$email = $_POST['emial'];
$country = $_POST['country'];
$number = $_POST['number'];
$password = $_POST['password'];

$sql = "insert into table_name(name, email, country, number, password) values('$name', '$email', '$country', '$number', '$password')";

$result = $conn->query($sql);

if($result){
    echo "insertion susseccfully";

}else{
    echo "error";
    
}


?>