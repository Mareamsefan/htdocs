<?php
 session_start();
 // Check if user ID is set in the session
 if (isset($_SESSION['user_id'])) {
     // Retrieve user ID
     $userId = $_SESSION['user_id'];
     // echo "User ID: " . $userId;
 } else {
     echo "User ID not found in session.";
 }
 $mysqli = require __DIR__ . "/../../../database.php";
 
 // kode hentet lecturerDashboard.api fil
 // SQL for å finne alle subjects til innlogget lecturer :)
                // Hentet for det meste kode fra chatGPT for denne. Se log under.
                // https://chat.openai.com/share/d8d4c359-b56e-4983-acac-acd2e754765f
                $sql = "SELECT DISTINCT s.* 
                FROM subject AS s
                INNER JOIN lecturer_has_subject AS lhs ON s.SubjectCode = lhs.Subject_SubjectCode
                INNER JOIN lecturer AS l ON lhs.Lecturer_ID = l.ID
                WHERE l.ID = $userId";
                $result = $mysqli->query($sql);
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

    

?>
