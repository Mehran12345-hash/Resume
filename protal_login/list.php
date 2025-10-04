<?php
session_start();
if(!$_SESSION['login']){
    header("location:khan.php");
}

$servername = "localhost";
$username = "root";
$password = "";
$db = "project_1212";

$conn = new mysqli ($servername, $username, $password, $db);

if($conn->connect_error){
    echo "Error";
}else{
    // echo "connected";  
}

$sql = "SELECT * FROM project_1213";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<style>
    .button{
        float: right;
    }
</style>
</head>
<body>
             <div class="x">
            <a href="khan.php" class="button btn btn-primary">ADD Student</a>
            <h2>Data Base</h2>
            </div>
        
<hr> 

   
      <table border="2" class="table table-hovered table-bordered table-stripe">
        <thead>
            <tr>
                <th>ID</th>
                <th>NAME</th>
                <th>EMAIL</th>
                <th>PHONE</th>
                <th>city</th>
                <th>PASSWORD</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
<?php
while($row = $result->fetch_array(MYSQLI_NUM)){ ?>

    <tr>
        <td><?php echo $row[0]; ?></td>
        <td><?php echo $row[1]; ?></td>
        <td><?php echo $row[2]; ?></td>
        <td><?php echo $row[3]; ?></td>
        <td><?php echo $row[4]; ?></td>
        <td><?php echo $row[5]; ?></td>
        <td><a class="btn btn-danger" href="delete.php?id=<?php echo $row[0]; ?>">Delete</a> | <a class="btn btn-success" href="update_date.php?id=<?php echo $row[0]; ?>">Update</a></td>
    
    </tr>

<?php }
?>





        </tbody>
      </table>  
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>