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
    $sql_check_email = "SELECT COUNT(*) AS count FROM student WHERE email='$email'";
    $result_check_email = $mysqli->query($sql_check_email);
    $row_check_email = $result_check_email->fetch_assoc();
    if ($row_check_email['count'] == 0) {
        echo "E-postadressen eksisterer ikke ";
        exit;
    }

    $newPassword = generatePassword();
    $timestamp = date("Y-m-d H:i:s"); // lagrer tiden passordet ble generert 
  
    $sql_update_password = "UPDATE lecturer SET temp_password='$newPassword', password_timestamp='$timestamp' WHERE email='$email'";
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
        echo 'Meldingen med et nytt passord er nå sendt, sjekk mailen din.';

    }

    $mysqli->close();
}
?>

