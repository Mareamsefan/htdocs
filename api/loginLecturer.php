<?php
session_start();
$mysqli = require __DIR__ . "/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Email = $_POST["Email"];
    $Password = $_POST["Password"];
    $RememberMe = isset($_POST["RememberMe"]); 

    // Sjekk om det er et midlertidig passord
    $sql_check_temp_password = "SELECT ID, password_timestamp FROM lecturer WHERE Email = '$Email' AND temp_password = '$Password'";
    $result_check_temp_password = $mysqli->query($sql_check_temp_password);
    $row_check_temp_password = $result_check_temp_password->fetch_assoc();

    if ($row_check_temp_password && strtotime($row_check_temp_password['password_timestamp']) > strtotime("-15 minutes")) {
        // Hvis det midlertidige passordet eksisterer og er innenfor gyldighetsperioden
        $userId = $row_check_temp_password['ID'];

        // Slett det midlertidige passordet
        $sql_delete_temp_password = "UPDATE lecturer SET temp_password = NULL WHERE ID = $userId";
        $mysqli->query($sql_delete_temp_password);

        // Logg inn brukeren
        login($mysqli, $userId, $RememberMe);
    } else {
        // Hvis det midlertidige passordet ikke eksisterer eller er utløpt, sjekk det permanente passordet
        $sql = "SELECT COUNT(*) AS count_exists, ID FROM lecturer WHERE Email = '$Email' AND Password = '$Password' GROUP BY ID";
        $result = $mysqli->query($sql);
        
        if ($result) {
            $row = $result->fetch_assoc();
            if($row){
                $count_exists = $row['count_exists'];
                $userId = $row['ID'];
                if ($count_exists == 1) {
                // Logg inn brukeren
                login($mysqli, $userId, $RememberMe);
                } 

            } else {
                echo "Feil, sjekk at passord og e-post er riktig.";
            } 
        } else {
            echo "Feil ved pålogging. Vennligst prøv igjen.";
        }
    }
    // Close the connection
    $mysqli->close();
}
function login($mysqli, $userId, $RememberMe) {
    echo "Du er nå logget inn!";
    $token = uniqid(); 
    if ($RememberMe) {
        setcookie("remember_token", $token, time() + 3600*24*7); 
    }
    $sql = "UPDATE lecturer SET remember_token = '$token' WHERE ID = $userId"; 
    mysqli_query($mysqli, $sql); 

    $_SESSION['user_id'] = $userId;
    $_SESSION['account_type'] = 2; // 2 = user is a lecturer
    header("Location: /api/lecturerDashboard.php");
    exit();
}
?>
