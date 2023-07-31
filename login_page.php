<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
    $login_email = $_POST['login_email'];
    $login_password = $_POST['login_password'];

    // Replace your_database_username and your_database_password with your actual database username and password
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

    // Prepare and bind the select statement
    $stmt = $conn->prepare("SELECT name, email, password FROM customers WHERE email = ?");
    $stmt->bind_param("s", $login_email);

    // Execute the select statement
    $stmt->execute();

    // Store the result
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        // Bind the result to variables
        $stmt->bind_result($name, $email, $hashed_password);

        // Fetch the result
        $stmt->fetch();

        // Verify the entered password with the hashed password from the database
        if (password_verify($login_password, $hashed_password)) {
            // Set the session variables after successful login
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;

            // Close the statement and connection
            $stmt->close();
            $conn->close();

            // Redirect to the dashboard page after successful login
            header("Location: dashboard.php");
            exit();
        }
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    // If login fails, show error message and redirect to the login page
    echo "Invalid login credentials. Please go back and try again.";
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <style>
    body {
    font-family: Arial, sans-serif;
    background-color: #f1f1f1;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.container {
    max-width: 400px;
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

form input[type="email"],
form input[type="password"] {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
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
</style>
</head>
<body>
<h2>Login Page</h2>
    <form action="" method="post">
        <label for="login_email">Email:</label>
        <input type="email" name="login_email" required>
        <br><br>
        <label for="login_password">Password:</label>
        <input type="password" name="login_password" required>
        <br><br>
        <input type="submit" name="submit" value="Login">
    </form>
</body>
</html>