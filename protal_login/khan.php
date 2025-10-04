<?php
session_start();
if(!$_SESSION['login']){
    header("location:index.html");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="stylem.css">
</head>
<body>
            <div class="container">
            <h1 id="c">Register</h1>
            <form action="insert.php" method="post">
            <input type="name" id="name" name="name" placeholder="Username" class="w"><br>
            <input type="email" id="email" name="email" placeholder="email" class="w"><br>
            <input type="phone" id="phone" name="phone" placeholder="Phone" class="w"><br>
            <input type="city" id="city" name="city" placeholder="city" class="w"><br>
            <input type="password" id="password" name="password" placeholder="Password" class="w"><br>
            <a id="a" href="#">Forget Password?</a><br>
            <button type="Register" class="v">Register</button>
            <p>Come back Login?  <a id="q" href="logout.php">logout</a></p>
             </form> 
             </div>
</body>
</html>
