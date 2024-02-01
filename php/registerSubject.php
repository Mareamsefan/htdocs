<?php

// Start Session
session_start();
$mysqli = require __DIR__ . "/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from form
    $SubjectCode = isset($_POST["SubjectCode"]) ? $_POST["SubjectCode"] : "";
    $SubjectName = isset($_POST["SubjectName"]) ? $_POST["SubjectName"] : "";
    $SubjectPIN = isset($_POST["SubjectPIN"]) ? $_POST["SubjectPIN"] : "";
    $userId = $_SESSION['user_id'];

    // 1. Legger til riktig data i subject-tabellen
    // Corrected SQL query syntax
    $sql = "INSERT INTO Subject (SubjectCode, SubjectName, SubjectPIN)
            VALUES ('$SubjectCode', '$SubjectName', '$SubjectPIN')";
    if ($mysqli->query($sql) === TRUE) {
        echo "Data lagt til i databasen!";
    } else {
        echo "Feil: " . $sql . "<br>" . $mysqli->error;
    }
    // 2. Legger til riktig data i lecturer_has_subject-tabellen
    $sql = "INSERT INTO lecturer_has_subject (Lecturer_ID, Subject_SubjectCode)
                VALUES ('$userId', '$SubjectCode')";

    if ($mysqli->query($sql) === TRUE) {
        echo "Data lagt til i lecturer_has_subject!";
        header("Location: /html/lecturerDashboard.php");
    } else {
        echo "Feil: : " . $mysqli->error;
    }
}

// Close the connection
$mysqli->close();
?>
