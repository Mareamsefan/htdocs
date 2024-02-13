<?php
$mysqli = require __DIR__ . "/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Email = $_POST["Email"];
    $Password = $_POST["Password"];
    $RememberMe = isset($_POST["RememberMe"]); 

    // Sjekk om det er et midlertidig passord
    $sql_check_temp_password = "SELECT ID, password_timestamp FROM Student WHERE Email = '$Email' AND temp_password = '$Password'";
    $result_check_temp_password = $mysqli->query($sql_check_temp_password);
    $row_check_temp_password = $result_check_temp_password->fetch_assoc();

    // Hvis et midlertidig passord ble funnet og det ikke har gått mer enn 15 minutter siden det ble generert
    if ($row_check_temp_password && strtotime($row_check_temp_password['password_timestamp']) > strtotime("-15 minutes")) {
        $userId = $row_check_temp_password['ID'];

        // Slett det midlertidige passordet
        $sql_delete_temp_password = "UPDATE lecturer SET temp_password = NULL WHERE ID = $userId";
        $mysqli->query($sql_delete_temp_password);

        // Logg inn brukeren
        login($mysqli, $userId, $RememberMe);

    } elseif ($row_check_temp_password == null) {
        echo "Feil, sjekk at passord og e-post er riktig.";
    } else {
        // Hvis det ikke er et midlertidig passord eller det har gått mer enn 15 minutter
        $sql = "SELECT COUNT(*) AS count_exists, ID FROM lecturer WHERE Email = '$Email' AND Password = '$Password' GROUP BY ID";
        $result = $mysqli->query($sql);

        if ($result) {
            $row = $result->fetch_assoc();
            $count_exists = $row['count_exists'];
            $userId = $row['ID'];

            if ($count_exists == 1) {
                // Logg inn brukeren
                login($mysqli, $userId, $RememberMe);
            } else {
                echo "Feil, sjekk at passord og e-post er riktig.";
            }
        } 
    }
}

// Close the connection
$mysqli->close();

function login($mysqli, $userId, $RememberMe) {
    echo "Du er nå logget inn!";
    $token = uniqid(); 
    if ($RememberMe) {
        setcookie("remember_token", $token, time() + 3600*24*7); 
    }
    $sql = "UPDATE lecturer SET remember_token = '$token' WHERE ID = $userId"; 
    mysqli_query($mysqli, $sql); 

    $_SESSION['user_id'] = $userId;
    header("Location: /php/lecturerDashboard.php");
    exit();
}
?>
