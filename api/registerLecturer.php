<?php

$mysqli = require __DIR__ . "/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $content_type = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

    if ($content_type === "application/json") {
        // Handle JSON data
        $json_data = file_get_contents("api://input");
        $data = json_decode($json_data, true);

        $FirstName = $data["FirstName"];
        $LastName = $data["LastName"];
        $Email = $data["Email"];
        $Password = $data["Password"];
        $SubjectCode = $data["SubjectCode"];
        $SubjectName = $data["SubjectName"];
        $SubjectPIN = $data["SubjectPIN"];
    } elseif ($content_type === "application/x-www-form-urlencoded") {
        // Handle form data
        $FirstName = $_POST["FirstName"];
        $LastName = $_POST["LastName"];
        $Email = $_POST["Email"];
        $Password = $_POST["Password"];
        $SubjectCode = $_POST["SubjectCode"];
        $SubjectName = $_POST["SubjectName"];
        $SubjectPIN = $_POST["SubjectPIN"];
    } else {
        // Unsupported content type
        die("Unsupported content type: $content_type");
    }

    // Perform database operations
    $sql = "INSERT INTO Lecturer(FirstName, LastName, Email, Password) VALUES('$FirstName', '$LastName', '$Email','$Password')";
    $mysqli->query($sql);

    // 1. Opprett subject
    $sql = "INSERT INTO subject(SubjectCode, SubjectName, SubjectPIN) VALUES('$SubjectCode', '$SubjectName', '$SubjectPIN')";
    $mysqli->query($sql);

    // 2. Hent lecturer ID fra database
    $sql = "SELECT id FROM Lecturer WHERE FirstName = '$FirstName' AND LastName = '$LastName' AND Email = '$Email'";
    $result = $mysqli->query($sql);
    $row = $result->fetch_assoc();
    $LecturerID = $row['id'];

    // 3. Opprett lecturer_has_subject
    $sql = "INSERT INTO lecturer_has_subject(Lecturer_ID, Subject_SubjectCode) VALUES('$LecturerID', '$SubjectCode')";
    
    if ($mysqli->query($sql) === TRUE) {
        echo "Data lagt til i databasen!";
        header("Location: /html/loginLecturer.html");
    } else {
        echo "Feil: " . $sql . "<br>" . $mysqli->error;
    }

    // Close the MySQL connection
    $mysqli->close();
}

?>
