<?php
session_start();
include "db.php";

if (!isset($_SESSION["reset_email"])) {
  echo "Session expired.";
  exit();
}

$email = $_SESSION["reset_email"];
$new = $_POST["new_password"];
$confirm = $_POST["confirm_password"];

if ($new !== $confirm) {
  echo "Passwords do not match!";
  exit();
}

$hashed = password_hash($new, PASSWORD_BCRYPT);

$stmt = $conn->prepare("UPDATE customers SET password = ? WHERE email = ?");
$stmt->bind_param("ss", $hashed, $email);

if ($stmt->execute()) {
    // Clear OTP
    $delete = $conn->prepare("DELETE FROM customer_otps WHERE email = ?");
    $delete->bind_param("s", $email);
    $delete->execute();
  
    session_destroy();
    header("Location: ../customer-login.php?reset=success");
    exit();
  }
  
?>
