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

    // Delete the customer from the database
    $sql = "DELETE FROM customers WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);

    // Bind the parameter and execute the statement
    mysqli_stmt_bind_param($stmt, "s", $line);
    mysqli_stmt_execute($stmt);

    // Close the statement and database connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    // Redirect back to the dashboard page after deletion
    header("Location: dashboard.php");
    exit();
} else {
    header("Location: dashboard.php");
    exit();
}
?>
