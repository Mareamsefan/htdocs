<?php
function generatePassword($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}

$_POST['email'] = 'mathias@mbq.no';

$pdo = new PDO('mysql:host=localhost;dbname=sandvac_1', 'MrRobot', '17%PepsiMaxDrikker');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        exit;
    }

    $newPassword = generatePassword();

    $sql = "UPDATE lecturer SET Password = :password WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['password' => $newPassword, 'email' => $email]);

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
