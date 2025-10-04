<?php
include_once("header.php");
?>
    <style>
       body {
    /* background-image: url("images/contact.png"); Correct syntax for background image */
    /* background-size: cover; /* Ensures the image covers the entire background */
    background-repeat: no-repeat; Prevents the image from repeating */
    font-family: Arial, sans-serif; /* Sets the font family */
}

        .contact-form {
            max-width: 600px;
            margin: 50px auto;
            padding:30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: 2px solid #0f054c;
        }
        .contact-form h2 {
            color: #0f054c;
            text-align: center;
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color: #0f054c;
            border: none;
        }
        .btn-primary:hover {
            background-color: #150a69;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="contact-form">
            <h2>Contact Us</h2>
            <form action="insert_contact.php" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" id="phone" name="phone" required>
                </div>
                <div class="mb-3">
                    <label for="website_type" class="form-label">Do you Have Website</label>
                    <input type="text" class="form-control" id="website_type" name="website_type">
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control" id="address" name="address" required>
                </div>
                   <div class="mb-3">
                    <label for="message" class="form-label">Message</label>
                    <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100">Submit</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php include_once("footer.php")

?>
