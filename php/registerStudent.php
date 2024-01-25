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
 $FirstName = $_POST["FirstName"];
 $LastName = $_POST["LastName"];
 $Email = $_POST["Email"];
 $StudyProgram = $_POST["StudyProgram"];
 $Class = $_POST["Class"];
 $Password = $_POST["Password"];


 $sql = "INSERT INTO Student (FirstName, LastName, Email, StudyProgram, Class, Password) VALUES('$FirstName', '$LastName', '$Email', '$StudyProgram', '$Class','$Password')";
  if ($conn->query($sql) === TRUE) {
        echo "Data lagt til i databasen!";
    } else {
        echo "Feil: " . $sql . "<br>" . $conn->error;
    }
 }
?>
