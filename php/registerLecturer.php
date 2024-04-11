<?php

$mysqli = require __DIR__ . "/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form data
    $FirstName = $_POST["FirstName"];
    $LastName = $_POST["LastName"];
    $Email = $_POST["Email"];
    $Password = $_POST["Password"];
    $Salt = "supertrygt salt";
    $HashedPass = hash('sha256', $Password, $Salt)
    $SubjectCode = $_POST["SubjectCode"];
    $SubjectName = $_POST["SubjectName"];
    $SubjectPIN = $_POST["SubjectPIN"];

    // File upload
    $target_dir = "../../imges/";
    $target_file = $target_dir . basename($_FILES["lecturerImage"]["name"]);

    //file validation
    // File Type Validation
$allowedTypes = array('image/jpeg', 'image/png', 'image/webp');
if (!in_array($_FILES["lecturerImage"]["type"], $allowedTypes)) {
    // Invalid file type
    echo "Invalid file type. Not an image file";
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
if ($_FILES["lecturerImage"]["type"] == 'image/jpeg' || $_FILES["lecturerImage"]["type"] == 'image/png' || $_FILES["lecturerImage"]["type"] == 'image/webp') {
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
    $fileName = uniqid('', true) . '_' . $fileName;
    // Alternatively, you can reject the upload and display an error message
    // echo "File already exists.";
    // exit; // Stop further processing
}
//validation ends here

    if (move_uploaded_file($_FILES["lecturerImage"]["tmp_name"], $target_file)) {
        echo "The file " . htmlspecialchars(basename($_FILES["lecturerImage"]["name"])) . " has been uploaded.";

        // Prepare the SQL statement for inserting into the lecturer table
        $sql = "INSERT INTO lecturer(FirstName, LastName, Email, Password, lecturerImage) VALUES(?, ?, ?, ?, ?)";

        // Prepare the statement
        if ($stmt = $mysqli->prepare($sql)) {
            // Bind parameters
            $stmt->bind_param("sssss", $FirstName, $LastName, $Email, $HashedPass, basename($_FILES["lecturerImage"]["name"]));

            // Execute the statement
            if ($stmt->execute()) {
                // Prepare the SQL statement for inserting into the subject table
                $sql = "INSERT INTO subject(SubjectCode, SubjectName, SubjectPIN) VALUES(?, ?, ?)";

                // Prepare the statement
                if ($stmt = $mysqli->prepare($sql)) {
                    // Bind parameters
                    $stmt->bind_param("sss", $SubjectCode, $SubjectName, $SubjectPIN);

                    // Execute the statement
                    if ($stmt->execute()) {
                        // Prepare the SQL statement for selecting lecturer ID from the database
                        $sql = "SELECT id FROM lecturer WHERE FirstName = ? AND LastName = ? AND Email = ?";

                        // Prepare the statement
                        if ($stmt = $mysqli->prepare($sql)) {
                            // Bind parameters
                            $stmt->bind_param("sss", $FirstName, $LastName, $Email);

                            // Execute the statement
                            if ($stmt->execute()) {
                                // Get the result
                                $result = $stmt->get_result();
                                $row = $result->fetch_assoc();
                                $LecturerID = $row['id'];

                                // Prepare the SQL statement for inserting into the lecturer_has_subject table
                                $sql = "INSERT INTO lecturer_has_subject(Lecturer_ID, Subject_SubjectCode) VALUES(?, ?)";

                                // Prepare the statement
                                if ($stmt = $mysqli->prepare($sql)) {
                                    // Bind parameters
                                    $stmt->bind_param("is", $LecturerID, $SubjectCode);

                                    // Execute the statement
                                    if ($stmt->execute()) {
                                        echo "Data lagt til i databasen!";
                                        header("Location: /html/loginLecturer.html");
                                    } else {
                                        echo "Feil: " . $mysqli->error;
                                    }
                                } else {
                                    echo "Feil: " . $mysqli->error;
                                }
                            } else {
                                echo "Feil: " . $mysqli->error;
                            }
                        } else {
                            echo "Feil: " . $mysqli->error;
                        }
                    } else {
                        echo "Feil: " . $mysqli->error;
                    }
                } else {
                    echo "Feil: " . $mysqli->error;
                }
            } else {
                echo "Feil: " . $mysqli->error;
            }
        } else {
            echo "Feil: " . $mysqli->error;
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }

    // Close the MySQL connection
    $mysqli->close();
}

?>
