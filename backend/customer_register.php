<?php
session_start();
include "db.php";
include "send_otp.php"; // PHPMailer reusable function

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first = trim($_POST["first_name"]);
    $last = trim($_POST["last_name"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

    // ✅ Check if user already exists
    $check = $conn->prepare("SELECT customer_id FROM customers WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "Email already exists.";
        $check->close();
        $conn->close();
        exit();
    }
    $check->close();

    // ✅ Generate OTP
    $otp = rand(100000, 999999);
    $expires = date("Y-m-d H:i:s", strtotime("+10 minutes"));

    // ✅ Store OTP in database
    $save_otp = $conn->prepare("INSERT INTO customer_otps (email, otp_code, expires_at) VALUES (?, ?, ?)");
    $save_otp->bind_param("sss", $email, $otp, $expires);
    $save_otp->execute();
    $save_otp->close();

    // ✅ Send OTP via PHPMailer
    if (sendOTP($email, $otp)) {
        // ✅ Temporarily store user signup data in session (NOT in database yet)
        $_SESSION["signup_data"] = [
            "first_name" => $first,
            "last_name" => $last,
            "email" => $email,
            "password" => $password
        ];
        $_SESSION["user_email"] = $email;

        header("Location: ../verify-otp.html");
        exit();
    } else {
        echo "Failed to send OTP to email. Please try again.";
    }
    if ($stmt->execute()) {
      // ✅ redirect to login with success flag
      header("Location: ../customer-login.php?signup=success");
      exit();
  }
  

    $conn->close();
}
?>
