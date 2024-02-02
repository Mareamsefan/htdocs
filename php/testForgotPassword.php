<?php
ini_set("SMTP", "localhost");
ini_set("smtp_port", "25");

function generatePassword($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}

$email = 'mathibr@hiof.no'; // Manually set the email address

$pdo = new PDO('mysql:host=localhost;dbname=sanvac_1', 'MrRobot', '17%PepsiMaxDrikker');

// Remove the condition if you're not expecting a form submission
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        exit;
    }

    $newPassword = generatePassword();

    $sql = "UPDATE lecturer SET Password = :password WHERE ID = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['password' => $newPassword]);


    $to = $email;
    $subject = "Your new password";
    $message = "Your new password is: $newPassword";
    $headers = "From: thisaintspam@totallysafe.crazy";
    if (mail($to, $subject, $message, $headers)) {
        echo "New password generated and sent to your email.";
    } else {
        echo "Failed to send email. Please try again later.";
    }
// }
?>