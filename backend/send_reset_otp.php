<?php
session_start();
include "db.php";
include "send_otp.php";

$email = $_POST["email"];

// Check if email exists in customers
$stmt = $conn->prepare("SELECT customer_id FROM customers WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    $otp = rand(100000, 999999);
    $expires = date("Y-m-d H:i:s", strtotime("+10 minutes"));

    $insert = $conn->prepare("INSERT INTO customer_otps (email, otp_code, expires_at) VALUES (?, ?, ?)");
    $insert->bind_param("sss", $email, $otp, $expires);
    $insert->execute();

    if (sendOTP($email, $otp)) {
        $_SESSION["reset_email"] = $email;
        header("Location: ../verify-reset-otp.php");
    } else {
        echo "Failed to send OTP. Please try again.";
    }
} else {
    echo "Email not found!";
}
?>
