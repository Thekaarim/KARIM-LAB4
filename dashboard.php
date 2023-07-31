<?php
session_start();

// Check if the user is logged in
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
    $login_email = $_POST['login_email'];
    $login_password = $_POST['login_password'];

    // Add the mysqli connection code here
    $host = "localhost"; // Change this if your MySQL server is on a different host
    $username = "root"; // Your MySQL username
    $password = ""; // Your MySQL password
    $database = "customer_registration"; // Your database name

    $conn = mysqli_connect($host, $username, $password, $database);

    // Check if the connection is successful
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepare the SQL statement to fetch user data based on email and password
    $sql = "SELECT * FROM customers WHERE email = ? AND password = ?";
    $stmt = mysqli_prepare($conn, $sql);

    // Bind the parameters and execute the statement
    mysqli_stmt_bind_param($stmt, "ss", $login_email, $login_password);
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);

    // Check if the user is found in the database
    if (mysqli_num_rows($result) > 0) {
        // Fetch user data
        $row = mysqli_fetch_assoc($result);

        // Set the session variables after successful login
        $_SESSION['name'] = $row['name'];
        $_SESSION['email'] = $row['email'];
    } else {
        echo "Invalid login credentials. Please go back and try again.";
        exit();
    }

    // Close the statement and database connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    // If the user is not logged in or accessed the page directly, redirect to the login page
    header("Location: login_page.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer List</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    margin: 20px;
}

h2 {
    color: #00529b;
}

h3 {
    color: #4CAF50;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    padding: 8px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #f2f2f2;
}

tr:hover {
    background-color: #f5f5f5;
}

img {
    max-width: 100px;
    max-height: 100px;
}

.delete-btn, .edit-btn, .logout-btn {
    padding: 6px 10px;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    margin-right: 5px;
}

.delete-btn {
    background-color: #dc3545;
}

.edit-btn {
    background-color: #ffc107;
}

.logout-btn {
    background-color: #00529b;
}

.delete-btn:hover, .edit-btn:hover, .logout-btn:hover {
    background
    }

    </style>
</head>
<body>
    <!-- Display customer data using PHP from the database -->
    <h2>Welcome, <?php echo $_SESSION['name']; ?>!</h2>
    <h3>Customer List</h3>
    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Room No.</th>
            <th>Profile Picture</th>
            <th>Actions</th>
        </tr>
        <?php
        // Connect to the database
        $conn = mysqli_connect($host, $username, $password, $database);

        // Check if the connection is successful
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Fetch customer data from the database
        $sql = "SELECT * FROM customers";
        $result = mysqli_query($conn, $sql);

        // Loop through the data and display it in the table
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['room_number'] . "</td>";
            echo "<td>";
            echo "<img src='uploads/images/" . $row['profile_picture'] . "' alt='Profile Picture' width='100'>";
            echo "</td>";
            echo "<td>";
            echo "<a href='delete.php?id=" . $row['id'] . "' class='delete-btn'>Delete</a>";
            echo "<a href='edit.php?id=" . $row['id'] . "' class='edit-btn'>Edit</a>";
            echo "</td>";
            echo "</tr>";
        }

        // Close the database connection
        mysqli_close($conn);
        ?>
    </table>
    <br>
    <a href="logout.php" class="logout-btn">Logout</a>
</body>
</html>