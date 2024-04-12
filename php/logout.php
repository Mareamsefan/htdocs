<?php
session_start();
$mysqli = require __DIR__ . "/database.php";


$account_type = $_SESSION['account_type'];
// Unset all session variables
$_SESSION = array();

// Delete the remember token from the database if it exists
if (isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    if ($account_type == 1) { // If user is a student
        $sql = "UPDATE student SET remember_token = NULL WHERE remember_token = ?";
    } elseif ($account_type == 2) { // If user is a lecturer
        $sql = "UPDATE lecturer SET remember_token = NULL WHERE remember_token = ?";
    }
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();

    // Clear the remember token cookie
    setcookie("remember_token", "", time() - 3600, "/");
}

// Destroy the session
session_destroy();

// Redirect to the login page
header("Location: /index.html");
exit();
?>
