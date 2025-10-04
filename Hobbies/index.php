<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form with Add/Delete Hobbies</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    .form-group {
      position: relative;
    }
    .form-group i {
      position: absolute;
      right: 10px;
      top: 35px;
    }
    .delete-hobby-btn {
      cursor: pointer;
      color: red;
    }
    .btn-custom {
      margin-left: auto;
      display: flex;
      align-items: center;
    }
    .error-message {
      color: red;
      font-size: 0.875rem;
    }
  </style>
</head>
<body>
<div class="container mt-5">
  <div class="d-flex justify-content-between mb-3">
    <h2>Registration Form</h2>
    <a href="list.php" class="btn btn-info text-white">
      <i class="fas fa-list"></i> List
    </a>
  </div>
  <form id="registration-form" method="POST" action="submit.php">
    <div class="form-group">
      <label for="name">Name</label>
      <input type="text" class="form-control" name="name" id="name" placeholder="Enter name" required>
      <i class="fas fa-user"></i>
    </div>

    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" class="form-control" name="email" id="email" placeholder="Enter email" required>
      <i class="fas fa-envelope"></i>
    </div>

    <div class="form-group">
      <label for="phone">Phone</label>
      <input type="tel" class="form-control" name="phone" id="phone" placeholder="Enter phone number" required>
      <i class="fas fa-phone"></i>
    </div>

    <div class="form-group">
      <label for="hobbies">Hobbies</label>
      <input type="text" class="form-control" id="hobbies" placeholder="Enter a hobby">
      <i class="fas fa-heart"></i>
      <small id="hobby-error" class="error-message"></small>
    </div>

    <button type="button" id="add-hobby" class="btn btn-info mb-2">
      <i class="fas fa-plus-circle"></i> Add Hobby
    </button>

    <div id="hobbies-list" class="mt-3">
      <h4>Added Hobbies</h4>
      <ul class="list-group" id="hobbies-container"></ul>
    </div>

    <!-- Hidden input to store hobbies as a JSON string -->
    <input type="hidden" name="hobbies" id="hobbies-input">

    <button type="submit" class="btn btn-success mb-2">
      <i class="fas fa-paper-plane"></i> Submit Form
    </button>
  </form>
</div>

<script>
  const hobbies = [];
  
  // Function to add hobby
  document.getElementById('add-hobby').addEventListener('click', function() {
    const hobbyInput = document.getElementById('hobbies');
    const hobby = hobbyInput.value.trim();
    const hobbyError = document.getElementById('hobby-error');

    // Clear previous error message
    hobbyError.textContent = '';

    if (hobby) {
      hobbies.push(hobby);

      const hobbyContainer = document.getElementById('hobbies-container');
      
      // Create new list item
      const listItem = document.createElement('li');
      listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
      listItem.textContent = hobby;

      // Create delete button for hobby
      const deleteButton = document.createElement('span');
      deleteButton.innerHTML = '<i class="fas fa-trash-alt delete-hobby-btn"></i>';
      deleteButton.addEventListener('click', function() {
        hobbyContainer.removeChild(listItem);
        hobbies.splice(hobbies.indexOf(hobby), 1); // Remove hobby from array
        updateHobbiesInput();
      });

      listItem.appendChild(deleteButton);
      hobbyContainer.appendChild(listItem);

      // Clear the input after adding
      hobbyInput.value = '';

      // Update hidden input with hobbies array
      updateHobbiesInput();
    } else {
      // Show error message if the hobby input is empty
      hobbyError.textContent = 'Please enter a hobby before adding.';
    }
  });

  // Update hidden input field with hobbies array
  function updateHobbiesInput() {
    document.getElementById('hobbies-input').value = JSON.stringify(hobbies);
  }

  // Prevent form submission for demonstration purposes

</script>

</body>
</html>
