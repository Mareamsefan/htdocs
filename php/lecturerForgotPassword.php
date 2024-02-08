<?php
$mysqli = require __DIR__ . "/database.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Inkluder PHPMailer auto-loader
require 'C:\xampp\htdocs\PHPMailer-master\src\Exception.php';
require 'C:\xampp\htdocs\PHPMailer-master\src\PHPMailer.php';
require 'C:\xampp\htdocs\PHPMailer-master\src\SMTP.php';

// Opprett et nytt PHPMailer-objekt
$mail = new PHPMailer;

// Konfigurer SMTP-serverinnstillingene
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'dittnyepassord@gmail.com';
$mail->Password = 'jjakkudjbduziqqg';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;

function generatePassword($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}

if ($_SERVER["REQUEST_METHOD"]== "POST"){
 
    $email = $_POST["Email"];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        exit;
    }

    // Sjekk om e-postadressen eksisterer i databasen
    $sql_check_email = "SELECT COUNT(*) AS count FROM lecturer WHERE email='$email'";
    $result_check_email = $mysqli->query($sql_check_email);
    $row_check_email = $result_check_email->fetch_assoc();
    if ($row_check_email['count'] == 0) {
        echo "E-postadressen eksisterer ikke ";
        exit;
    }


    // Sjekke om det har gått 15 min siden passordet ble generert 
    $sql_check_timestamp = "SELECT password_timestamp FROM lecturer WHERE email='$email'";
    $result_check_timestamp = $mysqli->query($sql_check_timestamp);
    $row_check_timestamp = $result_check_timestamp->fetch_assoc();
    $password_timestamp = strtotime($row_check_timestamp['password_timestamp']);
    $current_time = time();
    $fifteen_minutes = 60 * 2; // 15 minutter i sekunder

    // sjekker om det har gått 15 minutter 
    if ($current_time - $password_timestamp > $fifteen_minutes) {
        $sql_delete_expired_password = "DELETE FROM lecturer WHERE password_timestamp < DATE_SUB(NOW(), INTERVAL 15 MINUTE)";
        $mysqli->query($sql_delete_expired_password);
    }

    $newPassword = generatePassword();
    $timestamp = date("Y-m-d H:i:s"); // lagrer tiden passordet ble generert 
  
    $sql_update_password = "UPDATE lecturer SET password='$newPassword', password_timestamp='$timestamp' WHERE email='$email'";
    $mysqli->query($sql_update_password);

    //Recipients
    $mail->setFrom('dittnyepassord@gmail.com', 'NotCanvas');
    $mail->addAddress($email);

        // Legg til emne og melding
    $mail->Subject = 'Gjenoppretting av kontoen din';
    $mail->Body = "\nHei, Dette er ditt nye passord: " . $newPassword . "\nVennligst bytt passordet 
    så fort du har logget deg inn." . "\nMed Vennlig Hilsen NotCanvas";


    // Send e-posts
    if(!$mail->send()) { 
        echo 'Meldingen kunne ikke sendes';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        echo $timestamp;  
        echo 'Meldingen med et nytt passord er nå sendt, sjekk mailen din.';

    }

    $mysqli->close();
}


?>
