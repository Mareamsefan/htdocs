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

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Corrected SQL query syntax
    //change "LEFT JOIN" to "INNER JOIN" when there is actual link between lecturer and teacher tables. 
    $sql = "SELECT sb.SubjectCode, sb.SubjectName, sb.SubjectPIN, lec.FirstName, lec.LastName FROM
subject AS sb
INNER JOIN lecturer_has_subject AS lhs 
ON lhs.Subject_SubjectCode = sb.SubjectCode
INNER JOIN lecturer AS lec
ON lec.ID = lhs.Lecturer_ID";
    
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $subjects = array();
        while($row = $result->fetch_assoc()) {
            $subjects[] = $row;
        }

        // Convert the array to JSON and return
        header('Content-Type: application/json');
        echo json_encode($subjects);
    } else {
        echo "No subjects found.";
    }

    // Close the connection
    $conn->close();
}
?>
