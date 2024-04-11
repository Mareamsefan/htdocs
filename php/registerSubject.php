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

    // Prepare the SQL statement for inserting into the subject table
    $sql1 = "INSERT INTO subject (SubjectCode, SubjectName, SubjectPIN)
            VALUES (?, ?, ?)";

    // Prepare the statement
    if ($stmt1 = $mysqli->prepare($sql1)) {
        // Bind parameters
        $stmt1->bind_param("sss", $SubjectCode, $SubjectName, $SubjectPIN);

        // Execute the statement for inserting into the subject table
        if ($stmt1->execute()) {
            echo "Data lagt til i databasen!";

            // Prepare the SQL statement for inserting into the lecturer_has_subject table
            $sql2 = "INSERT INTO lecturer_has_subject (Lecturer_ID, Subject_SubjectCode)
                    VALUES (?, ?)";

            // Prepare the statement
            if ($stmt2 = $mysqli->prepare($sql2)) {
                // Bind parameters
                $stmt2->bind_param("is", $userId, $SubjectCode);

                // Execute the statement for inserting into the lecturer_has_subject table
                if ($stmt2->execute()) {
                    echo "Data lagt til i lecturer_has_subject!";
                    header("Location: /php/lecturerDashboard.php");
                } else {
                    echo "Feil: " . $mysqli->error;
                }

                // Close the statement for lecturer_has_subject
                $stmt2->close();
            } else {
                echo "Feil: " . $mysqli->error;
            }
        } else {
            echo "Feil: " . $mysqli->error;
        }

        // Close the statement for subject
        $stmt1->close();
    } else {
        echo "Feil: " . $mysqli->error;
    }

    // Close the MySQL connection
    $mysqli->close();
}

?>
