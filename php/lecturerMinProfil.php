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
        } else {
            echo "User ID not found in session.";
        }

        // Connect to database
        $mysqli = require __DIR__ . "/../php/database.php";

        // Retrieve user information
        $sql = "SELECT FirstName, LastName, Email FROM lecturer WHERE ID = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $LecturerEmail = $row['Email'];
        $LecturerFirstName = $row['FirstName']; 
        $LecturerLastName = $row['LastName'];

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // If the form is submitted, update the user profile
            $firstName = $_POST['FirstName'];
            $lastName = $_POST['LastName'];
            $email = $_POST['Email'];

            // Prepare SQL statement for updating the user
            $sql = "UPDATE lecturer SET FirstName=?, LastName=?, Email=? WHERE ID=?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("sssi", $firstName, $lastName, $email, $userId);

            // Execute the query
            if ($stmt->execute()) {
                echo "Brukerinformasjon oppdatert.";

                // Check if password field is not empty, then update password
                if(!empty($_POST['password'])) {
                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $sql_update_password = "UPDATE lecturer SET password=? WHERE ID=?";
                    $stmt_password = $mysqli->prepare($sql_update_password);
                    $stmt_password->bind_param("si", $password, $userId);
                    $stmt_password->execute();
                }
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
            <input type="password" name="password" placeholder="Oppgi nytt passord">

            <button type="submit">Oppdater</button>
            
        </form>
    </main>
</body>
</html>
