<?php
session_start();
$mysqli = require __DIR__ . "/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Email = $_POST["Email"];
    $Password = $_POST["Password"];
    $RememberMe = isset($_POST["RememberMe"]);

    // Check the permanent password
    $stmt = $mysqli->prepare("SELECT ID, Password FROM student WHERE Email = ?");
    $stmt->bind_param("s", $Email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        if (password_verify($Password, $row['Password'])) {
            // Password is correct
            login($mysqli, $row['ID'], $RememberMe);
        } else {
            // Password is incorrect
            echo "Feil, sjekk at passord og e-post er riktig.";
        }
    } else {
        // User does not exist
        echo "Feil ved pålogging. Vennligst prøv igjen.";
    }
    // Close the connection
    $stmt->close();
    $mysqli->close();
}

function login($mysqli, $userId, $RememberMe) {
    echo "Du er nå logget inn!";
    $token = uniqid('', true); // Generating a unique token for "Remember Me" functionality
    if ($RememberMe) {
        setcookie("remember_token", $token, time() + 3600*24*7, "/"); // Setting a cookie with a 1-week expiration
    }
    // Updating the remember_token in the database
    $stmt = $mysqli->prepare("UPDATE student SET remember_token = ? WHERE ID = ?");
    $stmt->bind_param("si", $token, $userId);
    $stmt->execute();
    $stmt->close();

    // Setting session variables
    $_SESSION['user_id'] = $userId;
    $_SESSION['account_type'] = 1; // Assuming 1 = user is a student

    // Redirecting to the student dashboard
    header("Location: /php/studentDashboard.php");
    exit();
}
?>
