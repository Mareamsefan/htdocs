<?php
$servername = "localhost";
$username = "MrRobot";
$password = "17%PepsiMaxDrikker";
$database = "sanvac_1";

// Check for errors after connection attempt
$conn = mysqli_connect($servername, $username, $password, $database);

// Check for connection errors
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "Connected successfully";

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

    if ($conn->query($sql) === TRUE) {
        echo "Data lagt til i databasen!";
        header("Location: /html/emnePage.php"); // Corrected header location
    } else {
        echo "Feil: " . $sql . "<br>" . $conn->error;
    }
}

// Close the connection
$conn->close();
?>
