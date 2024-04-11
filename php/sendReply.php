<?php
$mysqli = require __DIR__ . "/database.php";
session_start();

// If the reply has
// - no text
// - or no message ID
// - or is a student account
// - or has no subject ID
// redirect back to the page they came from
if (($_POST["reply"] != null) && ($_POST["message_id"] != null) &&
    ($_SESSION["account_type"] != 1) && ($_POST["subject_pin"] != null) ) {
    $accountType = $_SESSION['account_type'];
    $reply = $_POST["reply"];
    $messageID = $_POST["message_id"];
    $subjectPIN = $_POST["subject_pin"];

    if ($accountType == 2) {
        // Prepare and execute the SELECT statement to check if a reply from a lecturer already exists
        $checkSql = "SELECT * FROM reply WHERE Message_ID = ? AND from_teacher = 1";
        if ($stmt = $mysqli->prepare($checkSql)) {
            $stmt->bind_param("i", $messageID);
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if reply from a lecturer already exists
            if ($result->num_rows == 0) {
                // Prepare and execute the INSERT statement for a reply from a lecturer
                $insertSql = "INSERT INTO reply (Message, from_teacher, Message_ID) VALUES (?, 1, ?)";
                if ($stmt = $mysqli->prepare($insertSql)) {
                    $stmt->bind_param("si", $reply, $messageID);
                    $stmt->execute();
                    header("Location: /php/emnePage.php/?subject_pin=$subjectPIN");
                } else {
                    echo "Error preparing statement. Please try again.";
                }
            } else {
                // Redirect if a reply from a lecturer already exists
                header("Location: /php/emnePage.php/?subject_pin=$subjectPIN");
            }

            // Close the statement
            $stmt->close();
        } else {
            echo "Error preparing statement. Please try again.";
        }
    } else {
        // Prepare and execute the INSERT statement for a reply from a guest
        $sql = "INSERT INTO reply (Message, from_teacher, Message_ID) VALUES (?, 0, ?)";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("si", $reply, $messageID);
            $stmt->execute();
            header("Location: /php/emnePage.php/?subject_pin=$subjectPIN");
        } else {
            echo "Error preparing statement. Please try again.";
        }
    }
}
else{
    $subjectPIN = $_POST["subject_pin"];
    header("Location: /php/emnePage.php/?subject_pin=$subjectPIN");
}
