<?php
session_start();
include "db.php";

if (!isset($_SESSION["signup_data"])) {
  echo "Session expired.";
  exit();
}

$email = $_SESSION["signup_data"]["email"];
$otp = $_POST["otp"];

$stmt = $conn->prepare("SELECT id FROM customer_otps WHERE email = ? AND otp_code = ? AND expires_at > NOW()");
$stmt->bind_param("ss", $email, $otp);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 1) {
  // ✅ Insert user now (only after OTP is valid)
  $data = $_SESSION["signup_data"];
  $insert = $conn->prepare("INSERT INTO customers (first_name, last_name, email, password, is_verified) VALUES (?, ?, ?, ?, 1)");
  $insert->bind_param("ssss", $data["first_name"], $data["last_name"], $data["email"], $data["password"]);
  $insert->execute();

  // ✅ Login user
  $_SESSION["user_id"] = $insert->insert_id;
  $_SESSION["user_name"] = $data["first_name"];
  $_SESSION["role"] = "customer";

  // ✅ Clear OTP
  $delete = $conn->prepare("DELETE FROM customer_otps WHERE email = ?");
  $delete->bind_param("s", $email);
  $delete->execute();

  unset($_SESSION["signup_data"]);
  header("Location: ../customer/dashboard.php");
  exit();
} else {
  echo "Invalid or expired OTP.";
}

$stmt->close();
$conn->close();
?>
