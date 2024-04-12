<?php
session_start();
$mysqli = require __DIR__ . "/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Email = $_POST["Email"];
    $Password = $_POST["Password"];
    $RememberMe = isset($_POST["RememberMe"]); 

    // Check if it's a temporary password
    $sql_check_temp_password = "SELECT ID, password_timestamp FROM lecturer WHERE Email = ? AND temp_password = ?";
    $stmt = $mysqli->prepare($sql_check_temp_password);
    $stmt->bind_param("ss", $Email, $Password);
    $stmt->execute();
    $result_check_temp_password = $stmt->get_result();
    $row_check_temp_password = $result_check_temp_password->fetch_assoc();

    if ($row_check_temp_password && strtotime($row_check_temp_password['password_timestamp']) > strtotime("-15 minutes")) {
        // If the temporary password exists and is within the validity period
        $userId = $row_check_temp_password['ID'];

        // Delete the temporary password
        $sql_delete_temp_password = "UPDATE lecturer SET temp_password = NULL WHERE ID = ?";
        $stmt = $mysqli->prepare($sql_delete_temp_password);
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        // Log in the user
        login($mysqli, $userId, $RememberMe);
    } else {
        // If the temporary password does not exist or has expired, check the permanent password
        $sql = "SELECT ID, Password FROM lecturer WHERE Email = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $Email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $userId = $row['ID'];
            $storedPassword = $row['Password'];
            if(password_verify($Password, $storedPassword)){
                // Log in the user
                login($mysqli, $userId, $RememberMe);
            } else {
                echo "Feil, sjekk at passord og e-post er riktig.";
            } 
        } else {
            echo "Feil ved pålogging. Vennligst prøv igjen.";
        }
    }
    // Close the connection
    $mysqli->close();
}

function login($mysqli, $userId, $RememberMe) {
    echo "Du er nå logget inn!";
    $token = uniqid('', true);
    if ($RememberMe) {
        setcookie("remember_token", $token, time() + 3600*24*7); 
    }
    $sql = "UPDATE lecturer SET remember_token = ? WHERE ID = ?"; 
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("si", $token, $userId);
    $stmt->execute();

    $_SESSION['user_id'] = $userId;
    $_SESSION['account_type'] = 2; // 2 = user is a lecturer
    header("Location: /php/lecturerDashboard.php");
    exit();
}
?>
