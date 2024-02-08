<?php
$mysqli = require __DIR__ . "/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Email = $_POST["Email"];
    $Password = $_POST["Password"];
    $RememberMe = isset($_POST["RememberMe"]); 


    $sql = "SELECT COUNT(*) AS count_exists, ID FROM Student WHERE Email = '$Email' AND Password = '$Password' GROUP BY ID";

  
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
            $sql = "UPDATE Student SET remember_token = '$token' WHERE ID = $userId"; 
            mysqli_query($mysqli, $sql); 

            $_SESSION['user_id'] = $userId;
            header("Location: /php/studentDashboard.php?&id=$userId");
            
            exit();
              $lecturerID = $row['ID'];
        } else {
            echo "Feil, sjekk at passord og e-post er riktig.";
        }
    } else {
        echo "Feil i SQL-uttalelse: " . $mysqli->error;
    }
}
// Close the connection
$mysqli->close();
?>

