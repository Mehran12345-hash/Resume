<?php
include_once("header.php");
include_once("connection.php");

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Add new record
    if (isset($_POST['add_new'])) {
        $name1 = $_POST['Team_Member_Name1'] ?? '';
        $name2 = $_POST['Team_Member_Name2'] ?? '';
        $name3 = $_POST['Team_Member_Name3'] ?? '';
        $name4 = $_POST['Team_Member_Name4'] ?? '';

        $title1 = $_POST['title1'] ?? '';
        $title2 = $_POST['title2'] ?? '';
        $title3 = $_POST['title3'] ?? '';
        $title4 = $_POST['title4'] ?? '';

        $target_dir = "../uploads/";
        $teamimage1 = $_FILES['teamimage1']['name'] ?? '';
        $teamimage2 = $_FILES['teamimage2']['name'] ?? '';
        $teamimage3 = $_FILES['teamimage3']['name'] ?? '';
        $teamimage4 = $_FILES['teamimage4']['name'] ?? '';

        // Handle file uploads
        if ($teamimage1) move_uploaded_file($_FILES['teamimage1']['tmp_name'], $target_dir . $teamimage1);
        if ($teamimage2) move_uploaded_file($_FILES['teamimage2']['tmp_name'], $target_dir . $teamimage2);
        if ($teamimage3) move_uploaded_file($_FILES['teamimage3']['tmp_name'], $target_dir . $teamimage3);
        if ($teamimage4) move_uploaded_file($_FILES['teamimage4']['tmp_name'], $target_dir . $teamimage4);

        $insert_query = "INSERT INTO teams (Team_Member_Name1, teamimage1, title1, Team_Member_Name2, teamimage2, title2, Team_Member_Name3, teamimage3, title3, Team_Member_Name4, teamimage4, title4) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("ssssssssssss", $name1, $teamimage1, $title1, $name2, $teamimage2, $title2, $name3, $teamimage3, $title3, $name4, $teamimage4, $title4);

        if ($stmt->execute()) {
            echo "<script>alert('Record added successfully');</script>";
        } else {
            echo "<script>alert('Error adding record: {$stmt->error}');</script>";
        }
    }

    // Update record
    if (isset($_POST['update_record'])) {
        $id = $_POST['id'] ?? '';
        $name1 = $_POST['Team_Member_Name1'] ?? '';
        $name2 = $_POST['Team_Member_Name2'] ?? '';
        $name3 = $_POST['Team_Member_Name3'] ?? '';
        $name4 = $_POST['Team_Member_Name4'] ?? '';

        $title1 = $_POST['title1'] ?? '';
        $title2 = $_POST['title2'] ?? '';
        $title3 = $_POST['title3'] ?? '';
        $title4 = $_POST['title4'] ?? '';

        $target_dir = "../uploads/";
        $teamimage1 = $_FILES['teamimage1']['name'] ? $_FILES['teamimage1']['name'] : ($_POST['existing_teamimage1'] ?? '');
        $teamimage2 = $_FILES['teamimage2']['name'] ? $_FILES['teamimage2']['name'] : ($_POST['existing_teamimage2'] ?? '');
        $teamimage3 = $_FILES['teamimage3']['name'] ? $_FILES['teamimage3']['name'] : ($_POST['existing_teamimage3'] ?? '');
        $teamimage4 = $_FILES['teamimage4']['name'] ? $_FILES['teamimage4']['name'] : ($_POST['existing_teamimage4'] ?? '');

        // Handle file uploads
        if ($teamimage1) move_uploaded_file($_FILES['teamimage1']['tmp_name'], $target_dir . $teamimage1);
        if ($teamimage2) move_uploaded_file($_FILES['teamimage2']['tmp_name'], $target_dir . $teamimage2);
        if ($teamimage3) move_uploaded_file($_FILES['teamimage3']['tmp_name'], $target_dir . $teamimage3);
        if ($teamimage4) move_uploaded_file($_FILES['teamimage4']['tmp_name'], $target_dir . $teamimage4);

        $update_query = "UPDATE teams SET Team_Member_Name1 = ?, teamimage1 = ?, title1 = ?, Team_Member_Name2 = ?, teamimage2 = ?, title2 = ?, Team_Member_Name3 = ?, teamimage3 = ?, title3 = ?, Team_Member_Name4 = ?, teamimage4 = ?, title4 = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssssssssssssi", $name1, $teamimage1, $title1, $name2, $teamimage2, $title2, $name3, $teamimage3, $title3, $name4, $teamimage4, $title4, $id);

        if ($stmt->execute()) {
            echo "<script>alert('Record updated successfully');</script>";
        } else {
            echo "<script>alert('Error updating record: {$stmt->error}');</script>";
        }
    }

    // Delete record
    if (isset($_POST['delete_record'])) {
        $id = $_POST['id'] ?? '';
        if ($id) {
            $delete_query = "DELETE FROM teams WHERE id = ?";
            $stmt = $conn->prepare($delete_query);
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                echo "<script>alert('Record deleted successfully');</script>";
            } else {
                echo "<script>alert('Error deleting record: {$stmt->error}');</script>";
            }
        }
    }
}

// Fetch records
$sql = "SELECT * FROM teams";
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
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f8f9fa;
        }
        .table-striped tbody tr:nth-of-type(even) {
            background-color: #e9ecef;
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
                            <!-- Form Fields for Add New -->
                            <div class="mb-3">
                                <label for="update_name1" class="form-label">Team Member Name 1</label>
                                <input type="text" name="Team_Member_Name1" id="update_name1" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="update_teamimage1" class="form-label">Team Member Image 1</label>
                                <input type="file" name="teamimage1" id="update_teamimage1" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="update_title1" class="form-label">Title 1</label>
                                <input type="text" name="title1" id="update_title1" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="update_name2" class="form-label">Team Member Name 2</label>
                                <input type="text" name="Team_Member_Name2" id="update_name2" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="update_teamimage2" class="form-label">Team Member Image 2</label>
                                <input type="file" name="teamimage2" id="update_teamimage2" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="update_title2" class="form-label">Title 2</label>
                                <input type="text" name="title2" id="update_title2" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="update_name3" class="form-label">Team Member Name 3</label>
                                <input type="text" name="Team_Member_Name3" id="update_name3" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="update_teamimage3" class="form-label">Team Member Image 3</label>
                                <input type="file" name="teamimage3" id="update_teamimage3" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="update_title3" class="form-label">Title 3</label>
                                <input type="text" name="title3" id="update_title3" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="update_name4" class="form-label">Team Member Name 4</label>
                                <input type="text" name="Team_Member_Name4" id="update_name4" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="update_teamimage4" class="form-label">Team Member Image 4</label>
                                <input type="file" name="teamimage4" id="update_teamimage4" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="update_title4" class="form-label">Title 4</label>
                                <input type="text" name="title4" id="update_title4" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="add_new" class="btn btn-primary">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Displaying Data in Table -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Name 1</th>
                        <th>Image 1</th>
                        <th>Title 1</th>
                        <th>Name 2</th>
                        <th>Image 2</th>
                        <th>Title 2</th>
                        <th>Name 3</th>
                        <th>Image 3</th>
                        <th>Title 3</th>
                        <th>Name 4</th>
                        <th>Image 4</th>
                        <th>Title 4</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Assuming the database connection is established
                    // Fetch data from the database and display
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['Team_Member_Name1']}</td>
                            <td><img src='../uploads/{$row['teamimage1']}' width='50'></td>
                            <td>{$row['title1']}</td>
                            <td>{$row['Team_Member_Name2']}</td>
                            <td><img src='../uploads/{$row['teamimage2']}' width='50'></td>
                            <td>{$row['title2']}</td>
                            <td>{$row['Team_Member_Name3']}</td>
                            <td><img src='../uploads/{$row['teamimage3']}' width='50'></td>
                            <td>{$row['title3']}</td>
                            <td>{$row['Team_Member_Name4']}</td>
                            <td><img src='../uploads/{$row['teamimage4']}' width='50'></td>
                            <td>{$row['title4']}</td>
                            <td>
                                <button class='btn btn-info' data-bs-toggle='modal' data-bs-target='#updateModal' data-id='{$row['id']}'>Update</button>
                                <form method='POST' action='' style='display:inline'>
                                    <input type='hidden' name='id' value='{$row['id']}'>
                                    <button type='submit' name='delete_record' class='btn btn-danger'>Delete</button>
                                </form>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Update Record Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="updateForm" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateModalLabel">Update Record</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="update_name1" class="form-label">Team Member Name 1</label>
                            <input type="text" name="Team_Member_Name1" id="update_name1" class="form-control" >
                        </div>
                        <div class="mb-3">
                            <label for="update_teamimage1" class="form-label">Team Member Image 1</label>
                            <input type="file" name="teamimage1" id="update_teamimage1" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="update_title1" class="form-label">Title 1</label>
                            <input type="text" name="title1" id="update_title1" class="form-control" >
                        </div>

                        <div class="mb-3">
                            <label for="update_name2" class="form-label">Team Member Name 2</label>
                            <input type="text" name="Team_Member_Name2" id="update_name2" class="form-control" >
                        </div>
                        <div class="mb-3">
                            <label for="update_teamimage2" class="form-label">Team Member Image 2</label>
                            <input type="file" name="teamimage2" id="update_teamimage2" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="update_title2" class="form-label">Title 2</label>
                            <input type="text" name="title2" id="update_title2" class="form-control" >
                        </div>

                        <div class="mb-3">
                            <label for="update_name3" class="form-label">Team Member Name 3</label>
                            <input type="text" name="Team_Member_Name3" id="update_name3" class="form-control" >
                        </div>
                        <div class="mb-3">
                            <label for="update_teamimage3" class="form-label">Team Member Image 3</label>
                            <input type="file" name="teamimage3" id="update_teamimage3" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="update_title3" class="form-label">Title 3</label>
                            <input type="text" name="title3" id="update_title3" class="form-control" >
                        </div>

                        <div class="mb-3">
                            <label for="update_name4" class="form-label">Team Member Name 4</label>
                            <input type="text" name="Team_Member_Name4" id="update_name4" class="form-control" >
                        </div>
                        <div class="mb-3">
                            <label for="update_teamimage4" class="form-label">Team Member Image 4</label>
                            <input type="file" name="teamimage4" id="update_teamimage4" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="update_title4" class="form-label">Title 4</label>
                            <input type="text" name="title4" id="update_title4" class="form-control" >
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
include_once("footer.php");
?>