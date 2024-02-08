<?php

// Start Session
session_start();
$mysqli = require __DIR__ . "/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $content_type = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

    if ($content_type === "application/json") {
        // Handle JSON data
        $json_data = file_get_contents("php://input");
        $data = json_decode($json_data, true);

        $comment = $data["comment"];
        $userId = $_SESSION['user_id'];
    } elseif ($content_type === "application/x-www-form-urlencoded") {
        // Handle form data
        $comment = isset($_POST["comment"]) ? $_POST["SubjectCode"] : "";
        $userId = $_SESSION['user_id'];
    } else {
        // Unsupported content type
        die("Unsupported content type: $content_type");
    }

    // Perform database operations
    // 1. Legger til riktig data i subject-tabellen
    $sql = "INSERT INTO Message (Message, Student_ID)
            VALUES ('$comment', '$userId')";
    if ($mysqli->query($sql) === TRUE) {
        echo "Data lagt til i databasen!";
    } else {
        echo "Feil: " . $sql . "<br>" . $mysqli->error;
    }

    if ($mysqli->query($sql) === TRUE) {
        echo "Data lagt til i lecturer_has_subject!";
        header("Location: /html/StudentDashboard.php");
    } else {
        echo "Feil: : " . $mysqli->error;
    }

    // Close the MySQL connection
    $mysqli->close();
}

?>
