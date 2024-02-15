<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/style/registerStudent.css">
    <title>Min profil</title>
</head>
<body>
    <header>
   
    </header>

    <main>
        <h2>Brukerinformasjon</h2>
        <?php
        session_start();
        // Check if user ID is set in the session
        if (isset($_SESSION['user_id'])) {
        // Retrieve user ID
            $userId = $_SESSION['user_id'];
        // echo "User ID: " . $userId;
        } else {
            echo "User ID not found in session.";
        }

        // testing
        $mysqli = require __DIR__ . "/../php/database.php";
        $sql = "SELECT FirstName, LastName, Email, password FROM Lecturer WHERE ID = '$userId'";
        $result = $mysqli->query($sql);
        $row = $result->fetch_assoc();
        $LecturerEmail = $row['Email'];
        $Lecturerpassword = $row['password'];
        $LecturerFirstName = $row['FirstName']; 
        $LecturerLastName = $row['LastName'];

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Hvis skjemaet er sendt, oppdater brukerprofilen
            $email = $_POST['email'];
            $password = $_POST['password'];
                    
        // Forbered en SQL-setning for oppdatering av brukeren
            $sql = "UPDATE Lecturer SET Email='$email', password='$password' WHERE ID='$userId'";
                    
            // Utfør spørringen
            if ($mysqli->query($sql) === TRUE) {
                echo "Brukerinformasjon oppdatert.";
            } else {
                    echo "Feil ved oppdatering av brukerinformasjon: " . $mysqli->error;
                }
            }
            ?>

            <form id="register" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="FirstName">Fornavn: </label>
            <input type="text" name="FirstName" value="<?php echo $LecturerFirstName ?>">
                
            <label for="LastName">Etternavn: </label>
            <input type="text" name="LastName" value="<?php echo $LecturerLastName ?>">

            <label for="Email">Brukernavn: </label>
            <input type="text" name="Email" value="<?php echo $LecturerEmail ?>">
                
            <label for="password">Passord: </label>
            <input type="text" name="password" value="<?php echo $Lecturerpassword ?>">
                
            <button type="submit">Oppdater</button>
            
        </form>
    </main>
</body>
</html>