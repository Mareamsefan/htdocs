<?php
$servername = "localhost";
$username = "MrRobot";
$password = "17%PepsiMaxDrikker";
$database = "sanvac_1";

// Check for errors after connection attempt
$conn = mysqli_connect($servername, $username, $password, $database);

// Check for connection errors
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "Connected successfully";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Email = $_POST["Email"];
    $Password = $_POST["Password"];
    $RememberMe = isset($_POST["RememberMe"]); 


    $sql = "SELECT COUNT(*) AS count_exists, ID FROM Student WHERE Email = '$Email' AND Password = '$Password' GROUP BY ID";

  
    $result = $conn->query($sql);

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
            mysqli_query($conn, $sql); 

            $_SESSION['user_id'] = $userId;
            header("Location: /html/studentDashboard.html?&id=$userId");
            
            exit();
              $lecturerID = $row['ID'];
        } else {
            echo "Feil, sjekk at passord og e-post er riktig.";
        }
    } else {
        echo "Feil i SQL-uttalelse: " . $conn->error;
    }
}
// Close the connection
$conn->close();
?>

