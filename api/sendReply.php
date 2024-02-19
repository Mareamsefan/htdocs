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

    // Message from (a) lecturer
    if ($accountType == 2){
        $check = "SELECT * FROM reply WHERE Message_ID = $messageID AND from_teacher = 1";
        $result = $mysqli->query($check);

        // Check if reply from a lecturer already exists
        if ($result->num_rows == 0){
            $sql = "INSERT INTO reply (Message, from_teacher, Message_ID) VALUES ('$reply', 1, '$messageID')";
            if ($mysqli->query($sql) === TRUE) {
                header("Location: /api/emnePage.php/?subject_pin=$subjectPIN");
            } else {
                echo "Error submitting message. Please try again.";
            }
        }
        else{
            header("Location: /api/emnePage.php/?subject_pin=$subjectPIN");
        }
    }
    // Message from Guest
    else{
        $sql = "INSERT INTO reply (Message, from_teacher, Message_ID) 
            VALUES ('$reply', 0, '$messageID')";
        if ($mysqli->query($sql) === TRUE) {
            header("Location: /api/emnePage.php/?subject_pin=$subjectPIN");
        } else {
            echo "Error submitting message. Please try again.";
        }
    }
}
else{
    $subjectPIN = $_POST["subject_pin"];
    header("Location: /api/emnePage.php/?subject_pin=$subjectPIN");
}