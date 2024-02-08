<?php
$mysqli = require __DIR__ . "/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Email = $_POST["Email"];
    $Password = $_POST["Password"];
    $RememberMe = isset($_POST["RememberMe"]); 

    $sql = "SELECT COUNT(*) AS count_exists, ID, password_timestamp FROM lecturer WHERE Email = '$Email' AND Password = '$Password' GROUP BY ID";
    $result = $mysqli->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        $count_exists = $row['count_exists'];
        $password_timestamp = strtotime($row['password_timestamp']);
        
        $current_time = time();
        $fifteen_minutes = 60*15; // 15 minutter i sekunder

        // Sjekk om passordet har utløpt
        if ($current_time - $password_timestamp > $fifteen_minutes) {
            $sql_delete_expired_password = "UPDATE lecturer SET Password = NULL";
            $mysqli->query($sql_delete_expired_password);
            echo "Feil, sjekk at passord og e-post er riktig.";
            exit;
        }
           
        if ($count_exists == 1) {
            echo "Du er nå logget inn!";
            $token = uniqid(); 
            if ($RememberMe){
                setcookie("remember_token", $token, time() + 3600*24*7); 
            }
            $userId = $row['ID']; 
            $sql = "UPDATE lecturer SET remember_token = '$token' WHERE ID = $userId"; 
            mysqli_query($mysqli, $sql); 

            $_SESSION['user_id'] = $userId;
            header("Location: /php/studentDashboard.php");
            exit();
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
