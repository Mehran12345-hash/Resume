<?php   
include_once("header.php");
include_once("connection.php");

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Add new record
    if (isset($_POST['add_new'])) {
        $about_title = $_POST['about_title'];
        $about_heading = $_POST['about_heading'];
        $about_paragrap = $_POST['about_paragrap'];
        $about_paragrap1 = $_POST['about_paragrap1'];
        $about_image = $_FILES['about_image']['name'];
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($about_image);

        if (move_uploaded_file($_FILES['about_image']['tmp_name'], $target_file)) {
            $insert_query = "INSERT INTO about_update (about_title, about_image, about_heading, about_paragrap, about_paragrap1) 
                             VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("sssss", $about_title, $about_image, $about_heading, $about_paragrap, $about_paragrap1);

            if ($stmt->execute()) {
                echo "<script>alert('Record added successfully');</script>";
            } else {
                echo "<script>alert('Error adding record: {$stmt->error}');</script>";
            }
        } else {
            echo "<script>alert('Error uploading image');</script>";
        }
    }

    // Update record
    if (isset($_POST['update_record'])) {
        $id = $_POST['id'];
        $about_title = $_POST['about_title'];
        $about_heading = $_POST['about_heading'];
        $about_paragrap = $_POST['about_paragrap'];
        $about_paragrap1 = $_POST['about_paragrap1'];
        $about_image = $_FILES['about_image']['name'];
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($about_image);

        if (!empty($about_image)) {
            if (move_uploaded_file($_FILES['about_image']['tmp_name'], $target_file)) {
                $update_query = "UPDATE about_update SET about_title = ?, about_image = ?, about_heading = ?, about_paragrap = ?, about_paragrap1 = ? WHERE id = ?";
                $stmt = $conn->prepare($update_query);
                $stmt->bind_param("sssssi", $about_title, $about_image, $about_heading, $about_paragrap, $about_paragrap1, $id);
            } else {
                echo "<script>alert('Error uploading new image');</script>";
                return;
            }
        } else {
            $update_query = "UPDATE about_update SET about_title = ?, about_heading = ?, about_paragrap = ?, about_paragrap1 = ? WHERE id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("ssssi", $about_title, $about_heading, $about_paragrap, $about_paragrap1, $id);
        }

        if ($stmt->execute()) {
            echo "<script>alert('Record updated successfully');</script>";
        } else {
            echo "<script>alert('Error updating record: {$stmt->error}');</script>";
        }
    }

    // Delete record
    if (isset($_POST['delete_record'])) {
        $id = $_POST['id'];
        $delete_query = "DELETE FROM about_update WHERE id = ?";
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
$sql = "SELECT id, about_title, about_image, about_heading, about_paragrap, about_paragrap1 FROM about_update ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Update Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <style>
        /* Change the background color of the list */
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f8f9fa; /* Light grey */
        }
        .table-striped tbody tr:nth-of-type(even) {
            background-color: #e9ecef; /* Darker grey */
        }
        .add-new-form {
            display: none;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center mb-4 text-white">Manage About Update</h1>

    <!-- Add New Button -->
    <button id="addNewBtn" class="btn btn-success mb-4" data-bs-toggle="modal" data-bs-target="#addNewModal">Add New</button>

    <!-- Add New Record Form Modal -->
    <div class="modal fade" id="addNewModal" tabindex="-1" aria-labelledby="addNewModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="addNewForm" method="post" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addNewModalLabel">Add New Record</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="about_title" class="form-label">About Title</label>
                            <input type="text" name="about_title" id="about_title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="about_image" class="form-label">About Image</label>
                            <input type="file" name="about_image" id="about_image" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="about_heading" class="form-label">About Heading</label>
                            <input type="text" name="about_heading" id="about_heading" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="about_paragrap" class="form-label">About Paragraph</label>
                            <textarea name="about_paragrap" id="about_paragrap" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="about_paragrap1" class="form-label">About Paragraph 1</label>
                            <textarea name="about_paragrap1" id="about_paragrap1" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add_new" class="btn btn-primary">Add New</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Records List -->
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>About Title</th>
                <th>About Image</th>
                <th>About Heading</th>
                <th>About Paragraph</th>
                <th>About Paragraph 1</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['about_title'] ?></td>
                    <td><img src="../uploads/<?= $row['about_image'] ?>" alt="Image" style="width: 100px;"></td>
                    <td><?= $row['about_heading'] ?></td>
                    <td><?= $row['about_paragrap'] ?></td>
                    <td><?= $row['about_paragrap1'] ?></td>
                    <td>
                        <button class="btn btn-success updateBtn" 
                                data-bs-toggle="modal" 
                                data-bs-target="#updateModal" 
                                data-id="<?= $row['id'] ?>" 
                                data-title="<?= $row['about_title'] ?>" 
                                data-heading="<?= $row['about_heading'] ?>" 
                                data-paragrap="<?= $row['about_paragrap'] ?>" 
                                data-paragrap1="<?= $row['about_paragrap1'] ?>">
                            Update
                        </button>
                        <button class="btn btn-danger deleteBtn" 
                                data-bs-toggle="modal" 
                                data-bs-target="#deleteModal" 
                                data-id="<?= $row['id'] ?>">
                            Delete
                        </button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Update Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="updateForm" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Update Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="update_id">
                    <div class="mb-3">
                        <label for="update_about_title" class="form-label">About Title</label>
                        <input type="text" name="about_title" id="update_about_title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="update_about_heading" class="form-label">About Heading</label>
                        <input type="text" name="about_heading" id="update_about_heading" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="update_about_paragrap" class="form-label">About Paragraph</label>
                        <textarea name="about_paragrap" id="update_about_paragrap" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="update_about_paragrap1" class="form-label">About Paragraph 1</label>
                        <textarea name="about_paragrap1" id="update_about_paragrap1" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="update_about_image" class="form-label">About Image</label>
                        <input type="file" name="about_image" id="update_about_image" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="update_record" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="deleteForm" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this record?</p>
                    <input type="hidden" name="id" id="delete_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="delete_record" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Set data for Update Modal
    document.querySelectorAll('.updateBtn').forEach(button => {
        button.addEventListener('click', function () {
            document.getElementById('update_id').value = this.getAttribute('data-id');
            document.getElementById('update_about_title').value = this.getAttribute('data-title');
            document.getElementById('update_about_heading').value = this.getAttribute('data-heading');
            document.getElementById('update_about_paragrap').value = this.getAttribute('data-paragrap');
            document.getElementById('update_about_paragrap1').value = this.getAttribute('data-paragrap1');
        });
    });

    // Set data for Delete Modal
    document.querySelectorAll('.deleteBtn').forEach(button => {
        button.addEventListener('click', function () {
            document.getElementById('delete_id').value = this.getAttribute('data-id');
        });
    });
</script>

</body>
</html>
<?php
include_once("footer.php");
?>