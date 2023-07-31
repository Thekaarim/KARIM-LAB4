<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $room_number = $_POST['room_number'];

    // Validate if password and confirm_password match
    if ($password !== $confirm_password) {
        echo "Passwords do not match. Please go back and try again.";
        exit();
    }

    // Hash the password before storing it in the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Store customer data in the database
    $hostname = "localhost"; // Change this to your database hostname if different
    $username = "root"; // Replace with your database username
    $password = ""; // Replace with your database password
    $database = "customer_registration"; // Replace with your database name

    // Create a database connection
    $conn = new mysqli($hostname, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind the insert statement
    $stmt = $conn->prepare("INSERT INTO customers (name, email, password, room_number, profile_picture) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $hashed_password, $room_number, $profile_picture);

    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
        $targetDir = "uploads/images/";
        $profile_picture = $targetDir . basename($_FILES["profile_picture"]["name"]);
        move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $profile_picture);
    } else {
        // If no profile picture is uploaded, set a default image
        $profile_picture = "uploads/default_profile_picture.jpg";
    }

    // Execute the insert statement
    $stmt->execute();

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    // Redirect to login page
    header("Location: login_page.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registration Form</title>
    <style>
body {
    font-family: Arial, sans-serif;
    background-color: #f1f1f1;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

h2 {
    color: #00529b;
}

form label {
    display: block;
    font-weight: bold;
    margin-top: 10px;
}

form input[type="text"],
form input[type="email"],
form input[type="password"],
form select,
form input[type="file"] {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

form select {
    margin-bottom: 15px;
}

form input[type="submit"] {
    background-color: #00529b;
    color: #fff;
    border: none;
    padding: 10px 15px;
    cursor: pointer;
    border-radius: 4px;
}

form input[type="submit"]:hover {
    background-color: #00356b;
}

.error-message {
    color: red;
}

/* Additional styles for fancy file input */
.file-input-wrapper {
    position: relative;
    overflow: hidden;
    display: inline-block;
}

.file-input-wrapper input[type="file"] {
    font-size: 100px;
    position: absolute;
    left: 0;
    top: 0;
    opacity: 0;
}

.file-input-button {
    background-color: #00529b;
    color: #fff;
    padding: 8px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.file-input-button:hover {
    background-color: #00356b;
}    </style>
</head>
<body>
    <h2>Registration Form</h2>
    <form action="login.php" method="post" enctype="multipart/form-data">
        <label for="name">Name:</label>
        <input type="text" name="name" required>
        <br><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required>
        <br><br>

        <label for="password">Password (Only 8 characters, no special characters, only underscore allowed, no capital characters):</label>
        <input type="password" name="password" pattern="^[a-z0-9_]{8}$" title="Only 8 characters, no special characters, only underscore allowed, no capital characters" required>
        <br><br>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" name="confirm_password" required>
        <br><br>

        <label for="room_number">Room No.:</label>
        <select name="room_number" required>
            <option value="">Select Room No.</option>
            <option value="Application1">Application1</option>
            <option value="Application2">Application2</option>
            <option value="cloud">cloud</option>
        </select>
        <br><br>

        <label for="profile_picture">Profile Picture:</label>
        <input type="file" id="profile_picture" name="profile_picture">
        <br><br>

        <input type="submit" name="submit" value="Register">
    </form>
</body>
</html>
