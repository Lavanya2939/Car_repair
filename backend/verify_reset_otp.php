<?php
session_start();
include "db.php";

if (!isset($_SESSION["reset_email"])) {
  echo "Session expired.";
  exit();
}

$email = $_SESSION["reset_email"];
$otp = $_POST["otp"];

$stmt = $conn->prepare("SELECT id FROM customer_otps WHERE email = ? AND otp_code = ? AND expires_at > NOW()");
$stmt->bind_param("ss", $email, $otp);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 1) {
  // ✅ OTP is correct — go to reset password page
  header("Location: ../reset-password.php");
  exit();
} else {
  echo "<script>alert('Invalid or expired OTP'); window.location.href = '../verify-reset-otp.php';</script>";
  exit();
}
