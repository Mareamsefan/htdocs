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

    // Use single quotes around string values in the SQL query
    $sql = "SELECT COUNT(*) AS count_exists FROM Lecturer WHERE Email = '$Email' AND Password = '$Password'";

    // Execute the query and fetch the result
    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        $count_exists = $row['count_exists'];

        if ($count_exists == 1) {
            echo "Du er nå logget inn!";
            header("Location: /index.html");
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