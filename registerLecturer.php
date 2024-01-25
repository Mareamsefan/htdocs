<?php
$servername = "localhost";
$username = "MrRobot";
$password = "17%PepsiMaxDrikker";
$database = "mydb";

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
 $Password = $_POST["Password"];


 $sql = "INSERT INTO Lecturer(FirstName, LastName, Email, Password) VALUES('$FirstName', '$LastName', '$Email','$Password')";
  if ($conn->query($sql) === TRUE) {
        echo "Data lagt til i databasen!";
        header("/lecturerDashboard.html");
    } else {
        echo "Feil: " . $sql . "<br>" . $conn->error;
    }
 }
?>
