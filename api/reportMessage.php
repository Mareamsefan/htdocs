<?php
$mysqli = require __DIR__ . "/database.php";
$messageID = $_POST["message_id"];
$subjectPIN = $_POST["subject_pin"];

$sql = "UPDATE message SET Reported = 1 WHERE message.ID = $messageID";

if ($mysqli->query($sql) === TRUE) {
    header("Location: /api/emnePage.api/?subject_pin=$subjectPIN");
} else {
    echo "Error reporting message. Please try again.";
}