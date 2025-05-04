<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';

function sendMechanicCredentials($to_email, $username, $password, $name) {
  $mail = new PHPMailer(true);

  try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'carrepair4356@gmail.com'; // ✅ Replace
    $mail->Password   = 'mfvrbmfteiavlwad'; // ✅ Replace
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom('carrepair4356@gmail.com', 'Car Repair Admin');
    $mail->addAddress($to_email, $name);

    $mail->isHTML(true);
    $mail->Subject = 'Mechanic Login Credentials';
    $mail->Body    = "
      Hello <strong>$name</strong>,<br><br>
      You have been added as a mechanic.<br>
      <strong>Username:</strong> $username<br>
      <strong>Password:</strong> $password<br><br>
      Please login and update your password.<br><br>
      Regards,<br>Admin Team
    ";

    $mail->send();
  } catch (Exception $e) {
    echo "Mailer Error: {$mail->ErrorInfo}";
  }
}
?>
