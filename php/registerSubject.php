<?php
$mysqli = require __DIR__ . "/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
      echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    $SubjectCode = isset($_POST["SubjectCode"]) ? $_POST["SubjectCode"] : "";
    $SubjectName = isset($_POST["SubjectName"]) ? $_POST["SubjectName"] : "";
    $SubjectPIN = isset($_POST["SubjectPIN"]) ? $_POST["SubjectPIN"] : "";

    // Corrected SQL query syntax
    $sql = "INSERT INTO Subject (SubjectCode, SubjectName, SubjectPIN)
            VALUES ('$SubjectCode', '$SubjectName', '$SubjectPIN')";

    if ($mysqli->query($sql) === TRUE) {
        echo "Data lagt til i databasen!";
        header("Location: /html/emnePage.php"); // Corrected header location
    } else {
        echo "Feil: " . $sql . "<br>" . $mysqli->error;
    }
}

// Close the connection
$mysqli->close();
?>
