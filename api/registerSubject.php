<?php

// Start Session
session_start();
$mysqli = require __DIR__ . "/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $content_type = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

    if ($content_type === "application/json") {
        // Handle JSON data
        $json_data = file_get_contents("api://input");
        $data = json_decode($json_data, true);

        $SubjectCode = $data["SubjectCode"];
        $SubjectName = $data["SubjectName"];
        $SubjectPIN = $data["SubjectPIN"];
        $userId = $_SESSION['user_id'];
    } elseif ($content_type === "application/x-www-form-urlencoded") {
        // Handle form data
        $SubjectCode = isset($_POST["SubjectCode"]) ? $_POST["SubjectCode"] : "";
        $SubjectName = isset($_POST["SubjectName"]) ? $_POST["SubjectName"] : "";
        $SubjectPIN = isset($_POST["SubjectPIN"]) ? $_POST["SubjectPIN"] : "";
        $userId = $_SESSION['user_id'];
    } else {
        // Unsupported content type
        die("Unsupported content type: $content_type");
    }

    // Perform database operations
    // 1. Legger til riktig data i subject-tabellen
    $sql = "INSERT INTO subject (SubjectCode, SubjectName, SubjectPIN)
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
        header("Location: /api/lecturerDashboard.php");
    } else {
        echo "Feil: : " . $mysqli->error;
    }

    // Close the MySQL connection
    $mysqli->close();
}

?>
