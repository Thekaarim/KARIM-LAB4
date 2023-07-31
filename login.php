<!-- login.php -->
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

    // Store customer data in users.txt
    $profile_picture = $_FILES['profile_picture']['name'];
    $data = "$name,$email,$password,$room_number,$profile_picture" . PHP_EOL;
    file_put_contents("users.txt", $data, FILE_APPEND);

    // Redirect to login page
    header("Location: login_page.php");
    exit();
}
?>
