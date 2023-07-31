<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $room_number = $_POST['room_number'];

    // Connect to the database
    $host = "localhost"; // Change this if your MySQL server is on a different host
    $username = "root"; // Your MySQL username
    $password = ""; // Your MySQL password
    $database = "customer_registration"; // Your database name

    $conn = mysqli_connect($host, $username, $password, $database);

    // Check if the connection is successful
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if password and confirm password match
    if ($password !== $confirm_password) {
        echo "<p class='error-message'>Password and Confirm Password do not match.</p>";
    } else {
        // Handle profile picture upload
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
            $targetDir = "uploads/images/";
            $profile_picture = $targetDir . basename($_FILES["profile_picture"]["name"]);
            move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $profile_picture);
        }

        // Update the customer data in the database
        $sql = "UPDATE customers SET name = ?, password = ?, room_number = ?, profile_picture = ? WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);

        // Bind the parameters and execute the statement
        mysqli_stmt_bind_param($stmt, "sssss", $name, $password, $room_number, $profile_picture, $_SESSION['email']);
        mysqli_stmt_execute($stmt);

        // Close the statement and database connection
        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        // Redirect back to the dashboard page after update
        header("Location: dashboard.php");
        exit();
    }
} else {
    header("Location: dashboard.php");
    exit();
}
?>
