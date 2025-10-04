<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "portfolio"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = $_POST['name'];
$phone = $_POST['phone'];
$website_type = $_POST['website_type'];
$address = $_POST['address'];
$country = $_POST['country'];

$sql = "INSERT INTO contact_messages (name, phone, website_type, address, country) 
        VALUES ('$name', '$phone', '$website_type', '$address', '$country')";

if (mysqli_query($conn, $sql)) {
        header("Location:contact-form.php");
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>

