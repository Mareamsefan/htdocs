<?php
session_start(); 

$mysqli = require __DIR__ . "/database.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Email = $_POST["Email"];
    $Password = $_POST["Password"];
    $RememberMe = isset($_POST["RememberMe"]); 

    $sql = "SELECT COUNT(*) AS count_exists, ID FROM Lecturer WHERE Email = '$Email' AND Password = '$Password' GROUP BY ID";

    $result = $mysqli->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        $count_exists = $row['count_exists'];

        if ($count_exists == 1) {
            echo "Du er nå logget inn!";
           
            $token = uniqid(); 
            if ($RememberMe){
                setcookie("remember_token", $token, time() + 3600*24*7); 
            }
            $userId = $row['ID']; 
            $sql = "UPDATE Lecturer SET remember_token = '$token' WHERE ID = $userId"; 
            mysqli_query($mysqli, $sql); 

            $_SESSION['user_id'] = $userId;

            header("Location: /php/lecturerDashboard.php?email=$Email&password=$Password&id=$userId");
            header("Location: /php/lecturerDashboard.php?");
            
        } else {
            echo "Feil, sjekk at passord og e-post er riktig.";
        }
    } else {
        echo "Feil i SQL-uttalelse: " . $mysqli->error;
    }
}

$mysqli->close();

?>
