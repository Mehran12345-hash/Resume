<?php
// Database connection
$servername = "localhost";
$username = "root"; // Assuming you're using root user
$password = ""; // Assuming no password
$dbname = "love"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $hobbies = json_decode($_POST['hobbies'], true); // Decode the hobbies JSON string

    // Convert hobbies array to JSON string
    $hobbies_json = json_encode($hobbies); // Ensure hobbies are stored as JSON

    // Prepare and bind to insert into `users` table
    $stmt = $conn->prepare("INSERT INTO users (name, email, phone, hobbies) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $phone, $hobbies_json);

    // Execute the insert query for the `users` table
    if ($stmt->execute()) {
        // Redirect to list page
        header("Location: list.php");
        exit(); // Ensure no further code is executed after redirect
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the prepared statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
