<?php
$mysqli = require __DIR__ . "/database.php";

 if ($_SERVER["REQUEST_METHOD"]== "POST") {
     $FirstName = $_POST["FirstName"];
     $LastName = $_POST["LastName"];
     $Email = $_POST["Email"];
     $Password = $_POST["Password"];
     $SubjectCode = $_POST["SubjectCode"];
     $SubjectName = $_POST["SubjectName"];
     $SubjectPIN = $_POST["SubjectPIN"];


     $sql = "INSERT INTO Lecturer(FirstName, LastName, Email, Password) VALUES('$FirstName', '$LastName', '$Email','$Password')";
     $mysqli->query($sql);
     // 1. Opprett subject
     $sql = "INSERT INTO subject(SubjectCode, SubjectName, SubjectPIN) VALUES('$SubjectCode', '$SubjectName', '$SubjectPIN')";
     $mysqli->query($sql);
     // 2. Hent lecturer ID fra database
     $sql = "SELECT id FROM Lecturer WHERE FirstName = '$FirstName' AND LastName = '$LastName' AND Email = '$Email'";
     $result = $mysqli->query($sql);
     $row = $result->fetch_assoc();
     $LecturerID = $row['id'];
     // 3. Opprett lecturer_has_subject
     $sql = "INSERT INTO lecturer_has_subject(Lecturer_ID, Subject_SubjectCode) VALUES('$LecturerID', '$SubjectCode')";
     if ($mysqli->query($sql) === TRUE) {
         echo "Data lagt til i databasen!";
         header("Location: /html/lecturerDashboard.php");
     } else {
         echo "Feil: " . $sql . "<br>" . $mysqli->error;
     }

}


?>
