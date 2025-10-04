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

// Retrieve all users
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<style>
    #qq{
        margin-left: 1265px;
    }
</style>
</head>
<body>
<div class="container mt-5">
    <h2>User List</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Hobbies</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['phone']) . "</td>";

                    // Decode and display hobbies
                    $hobbies = json_decode($row['hobbies'], true); // Decode JSON
                    if (is_array($hobbies) && !empty($hobbies)) {
                        echo "<td><ul>";
                        foreach ($hobbies as $hobby) {
                            echo "<li>" . htmlspecialchars($hobby) . "</li>";
                        }
                        echo "</ul></td>";
                    } else {
                        // Handle case where hobbies is null or not an array
                        echo "<td>No hobbies listed</td>";
                    }

                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No users found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<a id="qq" href="index.php" class="btn btn-info mb-2 text-white">
  <i class="fas fa-arrow-left"></i> Back
</a>
</body>
</html>

<?php
// Close connection
$conn->close();
?>
