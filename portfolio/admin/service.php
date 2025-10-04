<?php   
include_once("header.php");
include_once("connection.php");

// Function to upload images
function uploadFile($fileField) {
    if (!empty($_FILES[$fileField]['name'])) {
        $fileName = time() . "_" . basename($_FILES[$fileField]['name']);
        $uploadPath = "uploads/.." . $fileName;

        if (move_uploaded_file($_FILES[$fileField]['tmp_name'], $uploadPath)) {
            return $fileName;
        }
    }
    return ''; // Return empty if no file uploaded
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_new'])) {
        $title = $_POST['title'] ?? '';
        $title2 = $_POST['title2'] ?? '';
        $service_img = uploadFile('Services_Img'); // 🔹 Use correct column name

        $insert_query = "INSERT INTO services (Services_Img, title, title2) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("sss", $service_img, $title, $title2);
        $stmt->execute();
    }
    if (isset($_POST['update_record'])) { 
        $id = $_POST['id'];
        $title = $_POST['title'] ?? '';
        $title2 = $_POST['title2'] ?? '';
        
        // Check if a new image is uploaded
        if (!empty($_FILES['Services_Img']['name'])) {
            $service_img = uploadFile('Services_Img');
        } else {
            // Fetch the existing image from the database
            $query = "SELECT Services_Img FROM services WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
    
            // ✅ Check if the query returned any row
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $service_img = $row['Services_Img']; // Keep the old image
            } else {
                $service_img = ''; // No image found, set empty
            }
        }
    
        // Update the record
        $update_query = "UPDATE services SET Services_Img=?, title=?, title2=? WHERE id=?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("sssi", $service_img, $title, $title2, $id);
    
        if ($stmt->execute()) {
            echo "<script>alert('Record updated successfully');</script>";
        } else {
            echo "<script>alert('Error updating record: {$stmt->error}');</script>";
        }
    }
    


    if (isset($_POST['delete_record'])) {
        $id = $_POST['id'];
        $delete_query = "DELETE FROM services WHERE id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}

// Fetch data from database
$sql = "SELECT * FROM services ORDER BY id DESC";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header Update</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center text-white">Header Update</h2>
    <div class="mb-3">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">Add New</button>
    </div>
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Service</th>
                <th>title</th>
                <th>title2</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['id']}</td>
                   <td><img src='uploads/..{$row['Services_Img']}' alt='Image2' style='width: 100px;'></td>
                    <td>{$row['title']}</td>
                    <td>{$row['title2']}</td>
                    <td>
                        <button class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#updateModal' onclick='setUpdateData(".json_encode($row).")'>Update</button>
                        <form method='POST' class='d-inline'>
                            <input type='hidden' name='id' value='{$row['id']}'>
                            <button type='submit' name='delete_record' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure to delete this record?\")'>Delete</button>
                        </form>
                    </td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='11' class='text-center'>No records found</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                <input type="hidden" id="id" name="id">
                    <input type="file" class="form-control" name="Services_Img">
                    <input type="hidden" id="existing_img" name="existing_img">
                    <input type="text" class="form-control mt-2" id="title" name="title" required>
                    <textarea class="form-control mt-2" id="title2" name="title2" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="add_new">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Modal -->
<div class="modal fade" id="updateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Update Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id" name="id">
                    <input type="file" class="form-control" name="Services_Img">
                    <input type="hidden" id="existing_img" name="existing_img">
                    <input type="text" class="form-control mt-2" id="title" name="title">
                    <textarea class="form-control mt-2" id="title2" name="title2"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="update_record">Update</button>
                </div>
            </form>
        </div>
    </div>  
</div>

<script>    
function setUpdateData(data) {
    document.getElementById('id').value = data.id;
    document.getElementById('title').value = data.title;
    document.getElementById('title2').value = data.title2;
    document.getElementById('existing_img').value = data.Services_Img;
}
</script>

</body>
</html>

<?php include_once("footer.php"); ?>
