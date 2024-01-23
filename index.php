<?php
// Login informasjon
$servername = "localhost";
$username = "MrRobot";
$password = "17%PepsiMaxDrikker";
$database = "mydb";

// Check for errors after connection attempt
$conn = mysqli_connect($servername, $username, $password, $database);

// Check for connection errors
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
}
/*
$sql = "INSERT INTO Student (FirstName, LastName, Email, StudyProgram, Class, Password) VALUES('$FirstName', '$LastName', '$Email', '$StudyProgram', '$Class','$Password')";
if ($conn->query($sql) === TRUE) {
echo "Data lagt til i databasen!";
} else {
echo "Feil: " . $sql . "<br>" . $conn->error;
}
*/


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
<body>
	<h1>This is home page</h1>

	<form action="mari.php" method="post">
        <label for="FirstName">Fornavn:</label>
        <input type="text" id="FirstName" name="FirstName" required>
        <br>
        <label for="LastName">Etternavn:</label>
        <input type="text" id="LastName" name="LastName" required>
        <br>
        <label for="Email">E-post:</label>
        <input type="email" id="Email" name="Email" required>
        <br>
        <label for="StudyProgram">Studieprogram:</label>
        <input type="text" id="StudyProgram" name="StudyProgram" required>
        <br>
        <label for="Class">Klasse:</label>
        <input type="text" id="Class" name="Class" required>
        <br>
        <label for="Password">Passord:</label>
        <input type="password" id="Password" name="Password" required>
        <br>
        <input type="submit" value="Legg til student">
    </form>
</body>
</html>
