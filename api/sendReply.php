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
        $sql = "INSERT INTO reply (Message, from_teacher, Message_ID) 
            VALUES ('$reply', 1, '$messageID')";
    }
    // Message from Guest
    else{
        $sql = "INSERT INTO reply (Message, from_teacher, Message_ID) 
            VALUES ('$reply', 0, '$messageID')";
    }

    if ($mysqli->query($sql) === TRUE) {
        header("Location: /api/emnePage.api/?subject_pin=$subjectPIN");
    } else {
        echo "Error submitting message. Please try again.";
    }

}
else{
    $subjectPIN = $_POST["subject_pin"];
    header("Location: /api/emnePage.api/?subject_pin=$subjectPIN");
}