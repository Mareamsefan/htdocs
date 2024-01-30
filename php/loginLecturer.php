<?php
session_start(); // Start session

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

    // Use single quotes around string values in the SQL query
    $sql = "SELECT COUNT(*) AS count_exists, ID FROM Lecturer WHERE Email = '$Email' AND Password = '$Password' GROUP BY ID";

    // Execute the query and fetch the result
    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        $count_exists = $row['count_exists'];

        //NB! ***************************Important info here**************************
        if ($count_exists == 1) {
            echo "Du er nå logget inn!";
            $token = uniqid(); 
            if ($RememberMe){
                setcookie("remember_token", $token, time() + 3600*24*7); 
            }
            $userId = $row['ID']; 
            $sql = "UPDATE Lecturer SET remember_token = '$token' WHERE ID = $userId"; 
            mysqli_query($conn, $sql); 

            $_SESSION['user_id'] = $userId;

            header("Location: /index.html");
            exit();
              $lecturerID = $row['ID'];
            //When redirecting, you can choose what data to send in url parameter
             header("Location: /html/lecturerDashboard.php?email=$Email&password=$Password&id=$lecturerID");
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
