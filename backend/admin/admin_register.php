<?php
include("../db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = trim($_POST['full_name']);
  $email = trim($_POST['email']);
  $password = $_POST['password'];
  $confirm = $_POST['confirm_password'];

  if ($password !== $confirm) {
    die("Passwords do not match.");
  }

  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Check if any admin already exists
  $check = mysqli_query($conn, "SELECT COUNT(*) as total FROM admins WHERE status = 'approved'");
  $row = mysqli_fetch_assoc($check);

  if ($row['total'] == 0) {
    // FIRST ADMIN — register immediately
    $insert = mysqli_query($conn, "INSERT INTO admins (full_name, email, password, status) VALUES ('$name', '$email', '$hashed_password', 'approved')");
    if ($insert) {
      echo "<script>alert('Primary Admin Registered Successfully!'); window.location.href='../../admin/admin-login.php';</script>";
    } else {
      echo "Error registering admin.";
    }
  } else {
    // SAVE AS REQUEST FOR APPROVAL
    $insert = mysqli_query($conn, "INSERT INTO admin_requests (full_name, email, password) VALUES ('$name', '$email', '$hashed_password')");
    if ($insert) {
      echo "<script>alert('Signup request sent to Admin. You will be contacted once approved.'); window.location.href='../../admin/admin-login.php';</script>";
    } else {
      echo "Error sending request.";
    }
  }
}
?>
