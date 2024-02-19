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
        $mysqli = require __DIR__ . "/../api/database.php";
        $sql = "SELECT FirstName, LastName, Email, password FROM student WHERE ID = '$userId'";
        $result = $mysqli->query($sql);
        $row = $result->fetch_assoc();
        $StudentEmail = $row['Email'];
        $Studentpassword = $row['password'];
        $StudentFirstName = $row['FirstName']; 
        $StudentLastName = $row['LastName'];

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Hvis skjemaet er sendt, oppdater brukerprofilen
            $email = $_POST['Email'];
            $password = $_POST['password'];
                    
        // Forbered en SQL-setning for oppdatering av brukeren
            $sql = "UPDATE student SET Email='$email', password='$password' WHERE ID='$userId'";
                    
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
            <input type="text" name="FirstName" value="<?php echo $StudentFirstName ?>">
                
            <label for="LastName">Etternavn: </label>
            <input type="text" name="LastName" value="<?php echo $StudentLastName ?>">

            <label for="Email">Brukernavn: </label>
            <input type="text" name="Email" value="<?php echo $StudentEmail ?>">
                
            <label for="password">Passord: </label>
            <input type="text" name="password" value="<?php echo $Studentpassword ?>">
                
            <button type="submit">Oppdater</button>
            
        </form>
    </main>
</body>
</html>