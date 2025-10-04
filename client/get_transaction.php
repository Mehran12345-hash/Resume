<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "client_management_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'] ?? null;
$transaction_id = $_GET['transaction_id'] ?? null;

if ($id) {
    $sql = "SELECT * FROM transactions WHERE id = $id";
} elseif ($transaction_id) {
    $sql = "SELECT * FROM transactions WHERE transaction_id = '$transaction_id'";
} else {
    die("No ID or transaction ID provided");
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo json_encode([]);
}

$conn->close();
?>