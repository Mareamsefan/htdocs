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
        $comment = isset($_POST["comment"]) ? $_POST["comment"] : "";
        $userId = $_SESSION['user_id'];
    } else {
        // Unsupported content type
        die("Unsupported content type: $content_type");
    }

    $subject_subjectPin = $_GET["subject_pin"];

    // Prepare the SQL statement
    $sql = "INSERT INTO Message (Message, Student_ID, Subject_SubjectPin)
            VALUES (?, ?, ?)";

    // Prepare the statement
    if ($stmt = $mysqli->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("sis", $comment, $userId, $subject_subjectPin);

        // Execute the statement
        if ($stmt->execute()) {
            echo "Data lagt til i databasen!";
        } else {
            echo "Feil: " . $sql . "<br>" . $mysqli->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $mysqli->error;
    }

    // Close the MySQL connection
    $mysqli->close();
}
?>
