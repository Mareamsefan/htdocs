<?php
$target_dir = "../img/"; // Ensure this directory exists in your project
$target_file = $target_dir . basename($_FILES["lecturerImage"]["name"]);

if (move_uploaded_file($_FILES["lecturerImage"]["tmp_name"], $target_file)) {
    echo "The file " . htmlspecialchars(basename($_FILES["lecturerImage"]["name"])) . " er lastet opp.";
    header('Location: lecturerDashboard.php');
} else {
    echo "Her gikk noe galt!.";
}
?>
