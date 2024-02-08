<?php

$mysqli = require __DIR__ . "/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $content_type = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

    if ($content_type === "application/json") {
        // Handle JSON data
        $json_data = file_get_contents("php://input");
        $data = json_decode($json_data, true);

        $FirstName = $data["FirstName"];
        $LastName = $data["LastName"];
        $Email = $data["Email"];
        $StudyProgram = $data["StudyProgram"];
        $Class = $data["Class"];
        $Password = $data["Password"];
    } elseif ($content_type === "application/x-www-form-urlencoded") {
        // Handle form data
        $FirstName = $_POST["FirstName"];
        $LastName = $_POST["LastName"];
        $Email = $_POST["Email"];
        $StudyProgram = $_POST["StudyProgram"];
        $Class = $_POST["Class"];
        $Password = $_POST["Password"];
    } else {
        // Unsupported content type
        die("Unsupported content type: $content_type");
    }

    // Perform database operations
    $sql = "INSERT INTO Student (FirstName, LastName, Email, StudyProgram, Class, Password) 
            VALUES ('$FirstName', '$LastName', '$Email', '$StudyProgram', '$Class', '$Password')";

    if ($mysqli->query($sql) === TRUE) {
        echo "Data lagt til i databasen!";
    } else {
        echo "Feil: " . $sql . "<br>" . $mysqli->error;
    }

    // Close the MySQL connection
    $mysqli->close();
}

?>
