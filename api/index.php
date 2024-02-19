<?php

$mysqli = require __DIR__ . "/database.php";

if ($_SERVER["REQUEST_METHOD"]== "POST") {
    $subjectPIN = $_POST["subjectPIN"];

    $sql = "SELECT SubjectPIN from subject WHERE SubjectPIN = '$subjectPIN'";
    $result = $mysqli->query($sql);
    $row = $result->fetch_assoc();

    // If subjectPIN actually exists, link to relevant site
    // else return to index page
    if ($row['SubjectPIN'] == null){
        header("Location: /index.html");
    }
    else {
        header("Location: /api/emnePage.php/?subject_pin={$subjectPIN}");
    }
}
?>
