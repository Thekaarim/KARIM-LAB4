<!DOCTYPE html>
<html>
<head>
    <title>Edit Customer Information</title>
<style>
    * edit_styles.css */
body {
  font-family: Arial, sans-serif;
  background-color: #f2f2f2;
  margin: 0;
  padding: 0;
}

.container {
  max-width: 500px;
  margin: 30px auto;
  background-color: #fff;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
}

h2 {
  text-align: center;
  margin-bottom: 20px;
}

form {
  display: flex;
  flex-direction: column;
}

label {
  font-weight: bold;
}

input[type="text"],
input[type="email"],
input[type="password"],
select {
  margin-bottom: 10px;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 4px;
  width: 100%;
}

input[type="submit"] {
  margin-top: 15px;
  padding: 10px;
  background-color: #4CAF50;
  color: #fff;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: bold;
}

a.back-btn {
  margin-top: 15px;
  padding: 10px;
  background-color: #f44336;
  color: #fff;
  text-align: center;
  display: block;
  width: 100px;
  text-decoration: none;
  border-radius: 4px;
}

a.back-btn:hover {
  background-color: #d32f2f;
}

.error-message {
  color: #f44336;
  margin-bottom: 10px;
}

.success-message {
  color: #4CAF50;
  margin-bottom: 10px;
}

/* Custom styles for the profile picture */
label[for="profile_picture"] {
  display: block;
  margin-top: 10px;
}

input[type="file"] {
  margin-bottom: 10px;
}

img {
  max-width: 200px;
  height: auto;
  display: block;
  margin-bottom: 10px;
}
</style>
</head>
<body>
    <h2>Edit Customer Information</h2>
<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['line'])) {
    $line = urldecode($_GET['line']);

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

    // Retrieve the customer data from the database
    $sql = "SELECT * FROM customers WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);

    // Bind the parameter and execute the statement
    mysqli_stmt_bind_param($stmt, "s", $line);
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);
    $user_data = mysqli_fetch_assoc($result);

    // Close the statement and database connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    header("Location: dashboard.php");
    exit();
}
?>
        <form action="edit_process.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="line" value="<?php echo urlencode($line); ?>">

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $name; ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>

            <label for="password">New Password:</label>
            <input type="password" id="password" name="password">

            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" id="confirm_password" name="confirm_password">

            <label for="room_number">Room No.:</label>
            <select id="room_number" name="room_number" required>
                <option value="Application1" <?php if ($room_number === "Application1") echo "selected"; ?>>Application1</option>
                <option value="Application2" <?php if ($room_number === "Application2") echo "selected"; ?>>Application2</option>
                <option value="cloud" <?php if ($room_number === "cloud") echo "selected"; ?>>cloud</option>
            </select>

            <label for="profile_picture">Profile Picture:</label>
            <input type="file" id="profile_picture" name="profile_picture" accept="image/*">

            <input type="submit" name="submit" value="Save Changes">
        </form>
    <?php
    } else {
        echo "<p class='error-message'>Invalid request. Please go back and try again.</p>";
    }
    ?>

    <a href="dashboard.php" class="back-btn">Back</a>
</body>
</html>
