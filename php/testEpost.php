<?php

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

//Recipients
$mail->setFrom('dittnyepassord@gmail.com', 'Mailer');
$mail->addAddress('yaya.abbdi@gmail.com', 'Yaya');

// Legg til emne og melding
$mail->Subject = 'Subject of Email';
$mail->Body = 'Content of your email goes here';

// Send e-post
if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}
?>
