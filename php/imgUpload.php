<?php
 session_start();
 include_once "database.php";
 // Check if user ID is set in the session
 if (isset($_SESSION['user_id'])) {
     // Retrieve user ID
     $userId = $_SESSION['user_id'];
     // echo "User ID: " . $userId;
 } else {
     echo "User ID not found in session.";
 }
$target_dir = "../img/"; // Ensure this directory exists in your project
$target_file = $target_dir . basename($_FILES["lecturerImage"]["name"]);

if (move_uploaded_file($_FILES["lecturerImage"]["tmp_name"], $target_file)) {
   // header('Location: lecturerDashboard.php');
    echo "The file " . htmlspecialchars(basename($_FILES["lecturerImage"]["name"])) . " er lastet opp.";
    $file_name = htmlspecialchars(basename($_FILES["lecturerImage"]["name"]));
    echo $userId;

    $sql = "UPDATE lecturer
    SET prof = '$file_name'
    WHERE ID = $userId;";
    $result = $mysqli->query($sql);

} else {
    echo "Her gikk noe galt!.";
}
?>
