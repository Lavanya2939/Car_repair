<?php
session_start();
include("../db.php");

if (isset($_POST['update_admin'])) {
  $admin_id = $_SESSION['admin_id'];
  $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
  $password = $_POST['password'];
  $image_name = $_FILES['profile_img']['name'];

  if (!empty($image_name)) {
    $ext = pathinfo($image_name, PATHINFO_EXTENSION);
    $new_name = 'admin_' . time() . '.' . $ext;
    $target = "../../uploads/admin_profiles/" . $new_name;
    move_uploaded_file($_FILES['profile_img']['tmp_name'], $target);

    mysqli_query($conn, "UPDATE admins SET profile_img = '$new_name' WHERE admin_id = '$admin_id'");
  }

  mysqli_query($conn, "UPDATE admins SET full_name = '$full_name' WHERE admin_id = '$admin_id'");

  if (!empty($password)) {
    $hashed = password_hash($password, PASSWORD_BCRYPT);
    mysqli_query($conn, "UPDATE admins SET password = '$hashed' WHERE admin_id = '$admin_id'");
  }

  header("Location: ../../admin/profile.php?updated=true");
  exit();
}
?>
