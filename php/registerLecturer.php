<?php

$mysqli = require __DIR__ . "/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form data
    $FirstName = $_POST["FirstName"];
    $LastName = $_POST["LastName"];
    $Email = $_POST["Email"];
    $Password = $_POST["Password"];
    $SubjectCode = $_POST["SubjectCode"];
    $SubjectName = $_POST["SubjectName"];
    $SubjectPIN = $_POST["SubjectPIN"];

    // File upload
    $target_dir = "../img/";
    $target_file = $target_dir . basename($_FILES["lecturerImage"]["name"]);

    //file validation
    // File Type Validation
$allowedTypes = array('image/jpeg', 'image/png');
if (!in_array($_FILES["lecturerImage"]["type"], $allowedTypes)) {
    // Invalid file type
    echo "Invalid file type. Please upload a JPEG or PNG image.";
    exit; // Stop further processing
}

// File Size Limitation (in bytes)
$maxFileSize = 5 * 1024 * 1024; // 5 MB
if ($_FILES["lecturerImage"]["size"] > $maxFileSize) {
    // File size exceeds the limit
    echo "File size exceeds the limit of 5 MB.";
    exit; // Stop further processing
}

// File Name Sanitization
$fileName = basename($_FILES["lecturerImage"]["name"]);
$fileName = preg_replace('/[^a-zA-Z0-9_.-]/', '', $fileName); // Remove any characters except letters, numbers, underscore, dot, and hyphen

// File Content Validation (for images)
if ($_FILES["lecturerImage"]["type"] == 'image/jpeg' || $_FILES["lecturerImage"]["type"] == 'image/png') {
    // Check if the uploaded file is a valid image
    $imageInfo = getimagesize($_FILES["lecturerImage"]["tmp_name"]);
    if ($imageInfo === false) {
        // Invalid image file
        echo "Invalid image file.";
        exit; // Stop further processing
    }
}

// Prevent Overwriting
if (file_exists($target_file)) {
    // Append a unique identifier to the file name
    $fileName = uniqid() . '_' . $fileName;
    // Alternatively, you can reject the upload and display an error message
    // echo "File already exists.";
    // exit; // Stop further processing
}
//validation ends here

    if (move_uploaded_file($_FILES["lecturerImage"]["tmp_name"], $target_file)) {
        echo "The file " . htmlspecialchars(basename($_FILES["lecturerImage"]["name"])) . " has been uploaded.";

        // Perform database operations
        $sql = "INSERT INTO lecturer(FirstName, LastName, Email, Password, lecturerImage) VALUES('$FirstName', '$LastName', '$Email','$Password', '" . basename($_FILES["lecturerImage"]["name"]) . "')";
        $mysqli->query($sql);

        // 1. Opprett subject
        $sql = "INSERT INTO subject(SubjectCode, SubjectName, SubjectPIN) VALUES('$SubjectCode', '$SubjectName', '$SubjectPIN')";
        $mysqli->query($sql);

        // 2. Hent lecturer ID fra database
        $sql = "SELECT id FROM lecturer WHERE FirstName = '$FirstName' AND LastName = '$LastName' AND Email = '$Email'";
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
    } else {
        echo "Sorry, there was an error uploading your file.";
    }

    // Close the MySQL connection
    $mysqli->close();
}

?>
