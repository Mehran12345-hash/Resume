<?php
include_once("conn.php");

$name = $_POST['name'];
$description = $_POST['description'];

if(isset($_POST['id']) && !empty($_POST['id'])){
    $id = $_POST['id'];



    $sql = "update products set name='$name', description='$description' where  id='$id'";

    $result = $conn->query($sql);
    
    if($result){
        header("location:products.php");
    }


}else{


$sql = "insert into products(name, description) values ('$name', '$description')";

$result = $conn->query($sql);

if($result){
    header("location:products.php");
}

}
?>