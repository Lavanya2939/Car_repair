<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

function sendOTP($toEmail, $otp) {
    $mail = new PHPMailer(true);

    try {
        // Server config
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;

        // ✨ Use your Gmail + app password here
        $mail->Username   = 'carrepair4356@gmail.com';
        $mail->Password   = 'mfvrbmfteiavlwad
';

        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Sender & recipient
        $mail->setFrom('carrepair4356@gmail.com', 'Car Repair App');
        $mail->addAddress($toEmail);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP - Car Repair';
        $mail->Body    = "Your OTP is: <b>$otp</b><br>It expires in 10 minutes.";

        $mail->send();
        return true;

    } catch (Exception $e) {
        return false;
    }
}
?>
