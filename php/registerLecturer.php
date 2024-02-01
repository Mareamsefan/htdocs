<?php
$mysqli = require __DIR__ . "/database.php";

 if ($_SERVER["REQUEST_METHOD"]== "POST"){
 $FirstName = $_POST["FirstName"];
 $LastName = $_POST["LastName"];
 $Email = $_POST["Email"];
 $Password = $_POST["Password"];


 $sql = "INSERT INTO Lecturer(FirstName, LastName, Email, Password) VALUES('$FirstName', '$LastName', '$Email','$Password')";
  if ($mysqli->query($sql) === TRUE) {
        echo "Data lagt til i databasen!";
        header("/lecturerDashboard.html");
    } else {
        echo "Feil: " . $sql . "<br>" . $mysqli->error;
    }
 }
?>
