<?php
include("../db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
  $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $phone = mysqli_real_escape_string($conn, $_POST['phone']);
  $specialty = mysqli_real_escape_string($conn, $_POST['specialty']);
  $rating = floatval($_POST['rating']);

  $full_name = $first_name . ' ' . $last_name;
  $status = 'active';

  // ✅ Check if email already exists
  $check_email = mysqli_query($conn, "SELECT * FROM mechanics WHERE email = '$email'");
  if (mysqli_num_rows($check_email) > 0) {
    echo "<script>alert('Email already exists.'); window.location.href='../../admin/manage_mechanics.php';</script>";
    exit();
  }

  // ✅ Insert mechanic without login fields
  $sql = "INSERT INTO mechanics (
            first_name, last_name, full_name, email, phone,
            specialty, rating, status
          ) VALUES (
            '$first_name', '$last_name', '$full_name', '$email', '$phone',
            '$specialty', '$rating', '$status'
          )";

  if (mysqli_query($conn, $sql)) {
    echo "<script>alert('Mechanic added successfully.'); window.location.href='../../admin/manage_mechanics.php';</script>";
  } else {
    echo "<script>alert('Error adding mechanic.'); window.location.href='../../admin/manage_mechanics.php';</script>";
  }
}
?>
