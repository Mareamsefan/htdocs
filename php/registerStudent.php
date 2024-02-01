<?php
$mysqli = require __DIR__ . "/database.php";

 if ($_SERVER["REQUEST_METHOD"]== "POST"){
 $FirstName = $_POST["FirstName"];
 $LastName = $_POST["LastName"];
 $Email = $_POST["Email"];
 $StudyProgram = $_POST["StudyProgram"];
 $Class = $_POST["Class"];
 $Password = $_POST["Password"];


// Specify the columns in the INSERT statement
$sql = "INSERT INTO Student (FirstName, LastName, Email, StudyProgram, Class, Password) 
        VALUES ('$FirstName', '$LastName', '$Email', '$StudyProgram', '$Class', '$Password')";

  if ($mysqli->query($sql) === TRUE) {
        echo "Data lagt til i databasen!";
    } else {
        echo "Feil: " . $sql . "<br>" . $mysqli->error;
    }
 }
?>
