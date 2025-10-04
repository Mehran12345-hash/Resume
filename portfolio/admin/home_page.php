<?php
include_once("header.php");
include_once("connection.php");

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Add new record
    if (isset($_POST['add_new'])) {
        $heading = isset($_POST['heading']) ? $_POST['heading'] : '';
        $paragrap = isset($_POST['paragrap']) ? $_POST['paragrap'] : '';
        $heading_2nd = isset($_POST['heading_2nd']) ? $_POST['heading_2nd'] : '';
        $paragrap_2nd = isset($_POST['paragrap_2nd']) ? $_POST['paragrap_2nd'] : '';
        $heading_3rd = isset($_POST['heading_3rd']) ? $_POST['heading_3rd'] : '';
        $paragrap_3rd = isset($_POST['paragrap_3rd']) ? $_POST['paragrap_3rd'] : '';
        $heading_image1 = isset($_FILES['heading_image1']) ? $_FILES['heading_image1']['name'] : '';
        $heading_image2 = isset($_FILES['heading_image2']) ? $_FILES['heading_image2']['name'] : '';
        $heading_image3 = isset($_FILES['heading_image3']) ? $_FILES['heading_image3']['name'] : '';
        
        if ($heading_image1) {
            move_uploaded_file($_FILES['heading_image1']['tmp_name'], "../uploads/$heading_image1");
        }
        if ($heading_image2) {
            move_uploaded_file($_FILES['heading_image2']['tmp_name'], "../uploads/$heading_image2");
        }
        if ($heading_image3) {
            move_uploaded_file($_FILES['heading_image3']['tmp_name'], "../uploads/$heading_image3");
        }

        $insert_query = "INSERT INTO header_update (heading_image1, heading, paragrap, heading_image2, heading_2nd, paragrap_2nd, heading_image3, heading_3rd, paragrap_3rd) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("sssssssss", $heading_image1, $heading, $paragrap, $heading_image2, $heading_2nd, $paragrap_2nd, $heading_image3, $heading_3rd, $paragrap_3rd);

        if ($stmt->execute()) {
            echo "<script>alert('Record added successfully');</script>";
        } else {
            echo "<script>alert('Error adding record: {$stmt->error}');</script>";
        }
    }

    // Update record
    if (isset($_POST['update_record'])) {
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $heading = isset($_POST['heading']) ? $_POST['heading'] : '';
        $paragrap = isset($_POST['paragrap']) ? $_POST['paragrap'] : '';
        $heading_2nd = isset($_POST['heading_2nd']) ? $_POST['heading_2nd'] : '';
        $paragrap_2nd = isset($_POST['paragrap_2nd']) ? $_POST['paragrap_2nd'] : '';
        $heading_3rd = isset($_POST['heading_3rd']) ? $_POST['heading_3rd'] : '';
        $paragrap_3rd = isset($_POST['paragrap_3rd']) ? $_POST['paragrap_3rd'] : '';
        $heading_image1 = isset($_FILES['heading_image1']) ? $_FILES['heading_image1']['name'] : '';
        $heading_image2 = isset($_FILES['heading_image2']) ? $_FILES['heading_image2']['name'] : '';
        $heading_image3 = isset($_FILES['heading_image3']) ? $_FILES['heading_image3']['name'] : '';

        if ($heading_image1) {
            move_uploaded_file($_FILES['heading_image1']['tmp_name'], "../uploads/$heading_image1");
        }
        if ($heading_image2) {
            move_uploaded_file($_FILES['heading_image2']['tmp_name'], "../uploads/$heading_image2");
        }
        if ($heading_image3) {
            move_uploaded_file($_FILES['heading_image3']['tmp_name'], "../uploads/$heading_image3");
        }

        $update_query = "UPDATE header_update SET heading_image1 = ?, heading = ?, paragrap = ?, heading_image2 = ?, heading_2nd = ?, paragrap_2nd = ?, heading_image3 = ?, heading_3rd = ?, paragrap_3rd = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("sssssssssi", $heading_image1, $heading, $paragrap, $heading_image2, $heading_2nd, $paragrap_2nd, $heading_image3, $heading_3rd, $paragrap_3rd, $id);

        if ($stmt->execute()) {
            echo "<script>alert('Record updated successfully');</script>";
            header("Location: home_page.php");
        } else {
            echo "<script>alert('Error updating record: {$stmt->error}');</script>";
        }
    }

    // Delete record
    if (isset($_POST['delete_record'])) {
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $delete_query = "DELETE FROM header_update WHERE id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo "<script>alert('Record deleted successfully');</script>";
        } else {
            echo "<script>alert('Error deleting record: {$stmt->error}');</script>";
        }
    }
}

// Fetch records
$sql = "SELECT id, heading_image1, heading, paragrap, heading_image2, heading_2nd, paragrap_2nd, heading_image3, heading_3rd, paragrap_3rd FROM header_update ORDER BY id DESC";
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
                <th>Heading Image 1</th>
                <th>Heading</th>
                <th>Paragraph</th>
                <th>Heading Image 2</th>
                <th>Heading 2nd</th>
                <th>Paragraph 2nd</th>
                <th>Heading Image 3</th>
                <th>Heading 3rd</th>
                <th>Paragraph 3rd</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td><img src='../uploads/{$row['heading_image1']}' alt='Image' style='width: 100px;'></td>
                    <td>{$row['heading']}</td>
                    <td>{$row['paragrap']}</td>
                    <td><img src='../uploads/{$row['heading_image2']}' alt='Image' style='width: 100px;'></td>
                    <td>{$row['heading_2nd']}</td>
                    <td>{$row['paragrap_2nd']}</td>
                    <td><img src='../uploads/{$row['heading_image3']}' alt='Image' style='width: 100px;'></td>
                    <td>{$row['heading_3rd']}</td>
                    <td>{$row['paragrap_3rd']}</td>
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
            echo "<tr><td colspan='11'>No records found</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Add New Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="heading_image1" class="form-label">Heading Image 1</label>
                        <input type="file" class="form-control" id="heading_image1" name="heading_image1" required>
                    </div>
                    <div class="mb-3">
                        <label for="heading" class="form-label">Heading</label>
                        <input type="text" class="form-control" id="heading" name="heading" required>
                    </div>
                    <div class="mb-3">
                        <label for="paragrap" class="form-label">Paragraph</label>
                        <textarea class="form-control" id="paragrap" name="paragrap" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="heading_image2" class="form-label">Heading Image 2</label>
                        <input type="file" class="form-control" id="heading_image2" name="heading_image2" required>
                    </div>
                    <div class="mb-3">
                        <label for="heading_2nd" class="form-label">Heading 2nd</label>
                        <input type="text" class="form-control" id="heading_2nd" name="heading_2nd" required>
                    </div>
                    <div class="mb-3">
                        <label for="paragrap_2nd" class="form-label">Paragraph 2nd</label>
                        <textarea class="form-control" id="paragrap_2nd" name="paragrap_2nd" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="heading_image3" class="form-label">Heading Image 3</label>
                        <input type="file" class="form-control" id="heading_image3" name="heading_image3" required>
                    </div>
                    <div class="mb-3">
                        <label for="heading_3rd" class="form-label">Heading 3rd</label>
                        <input type="text" class="form-control" id="heading_3rd" name="heading_3rd" required>
                    </div>
                    <div class="mb-3">
                        <label for="paragrap_3rd" class="form-label">Paragraph 3rd</label>
                        <textarea class="form-control" id="paragrap_3rd" name="paragrap_3rd" required></textarea>
                    </div>
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
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Update Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <div class="mb-3">
                        <label for="heading_image1" class="form-label">Heading Image 1</label>
                        <input type="file" class="form-control" id="heading_image1" name="heading_image1" required>
                    </div>
                    <div class="mb-3">
                        <label for="heading" class="form-label">Heading</label>
                        <input type="text" class="form-control" id="heading" name="heading" required>
                    </div>
                    <div class="mb-3">
                        <label for="paragrap" class="form-label">Paragraph</label>
                        <textarea class="form-control" id="paragrap" name="paragrap" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="heading_image2" class="form-label">Heading Image 2</label>
                        <input type="file" class="form-control" id="heading_image2" name="heading_image2" required>
                    </div>
                    <div class="mb-3">
                        <label for="heading_2nd" class="form-label">Heading 2nd</label>
                        <input type="text" class="form-control" id="heading_2nd" name="heading_2nd" required>
                    </div>
                    <div class="mb-3">
                        <label for="paragrap_2nd" class="form-label">Paragraph 2nd</label>
                        <textarea class="form-control" id="paragrap_2nd" name="paragrap_2nd" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="heading_image3" class="form-label">Heading Image 3</label>
                        <input type="file" class="form-control" id="heading_image3" name="heading_image3" required>
                    </div>
                    <div class="mb-3">
                        <label for="heading_3rd" class="form-label">Heading 3rd</label>
                        <input type="text" class="form-control" id="heading_3rd" name="heading_3rd" required>
                    </div>
                    <div class="mb-3">
                        <label for="paragrap_3rd" class="form-label">Paragraph 3rd</label>
                        <textarea class="form-control" id="paragrap_3rd" name="paragrap_3rd" required></textarea>
                    </div>
                </div>
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
    // Function to set update data in modal
    function setUpdateData(data) {
        document.getElementById('heading').value = data.heading;
        document.getElementById('paragrap').value = data.paragrap;
        document.getElementById('heading_2nd').value = data.heading_2nd;
        document.getElementById('paragrap_2nd').value = data.paragrap_2nd;
        document.getElementById('heading_3rd').value = data.heading_3rd;
        document.getElementById('paragrap_3rd').value = data.paragrap_3rd;
        // Add additional form elements as needed
    }
</script>
</body>
</html>

<?php
include_once("footer.php");
?>