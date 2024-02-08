<?php
define ('MAILHOST', "smtp.gmail.com");
define ('USERNAME', "dittnyepassord@gmail.com");
define ('PASSWORD', "jjak kudj bduz iqqg");
define ('SEND_FROM', "Helt trygg e-post!");


function generatePassword($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        exit;
    }

    $newPassword = generatePassword();

    $sql = "UPDATE student SET Password = :password WHERE Email = :Email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['password' => $newPassword, 'Email' => $email]);

    $to = $email;
    $subject = "Your new password";
    $message = "Your new password is: $newPassword";
    $headers = "From: thisaintspam@totallysafe.crazy";
    if (mail($to, $subject, $message, $headers)) {
        echo "New password generated and sent to your email.";
    } else {
        echo "Failed to send email. Please try again later.";
    }
}
?>
