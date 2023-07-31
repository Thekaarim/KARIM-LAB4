<?php
// Destroy the session and clear all session variables
session_start();
session_unset();
session_destroy();

// Redirect to the login page after logging out
header("Location: login_page.php");
exit();
?>
