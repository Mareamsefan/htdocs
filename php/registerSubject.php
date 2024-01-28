<?php
$servername = "localhost";
$username = "MrRobot";
$password = "17%PepsiMaxDrikker";
$database = "sanvac_1";

// Check for errors after connection attempt
 $conn = mysqli_connect($servername, $username, $password, $database);
// // Check for connection errors
 if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
   }

  echo "Connected successfully";
 if ($_SERVER["REQUEST_METHOD"]== "POST"){
 $SubjectCode = $_POST["SubjectCode"];
 $SubjectName = $_POST["SubjectName"];
 $SubjectPIN = $_POST["SubjectPIN"];



 $sql = "INSERT INTO Subject(SubjectCode, SubjectName, SubjectPIN)
 $VALUES('$SubjectCode', '$SubjectName', '$SubjectPIN')";
  if ($conn->query($sql) === TRUE) {
        echo "Data lagt til i databasen!";
        header("/lecturerDashboard.html");
    } else {
        echo "Feil: " . $sql . "<br>" . $conn->error;
    }
 }
?>
