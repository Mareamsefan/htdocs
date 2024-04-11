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

    if (move_uploaded_file($_FILES["lecturerImage"]["tmp_name"], $target_file)) {
        echo "The file " . htmlspecialchars(basename($_FILES["lecturerImage"]["name"])) . " has been uploaded.";

        // Prepare the SQL statement for inserting into the lecturer table
        $sql = "INSERT INTO lecturer(FirstName, LastName, Email, Password, lecturerImage) VALUES(?, ?, ?, ?, ?)";

        // Prepare the statement
        if ($stmt = $mysqli->prepare($sql)) {
            // Bind parameters
            $stmt->bind_param("sssss", $FirstName, $LastName, $Email, $Password, basename($_FILES["lecturerImage"]["name"]));

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
