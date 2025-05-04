<?php
session_start();
include("../db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = trim($_POST['email']);
  $password = $_POST['password'];

  $check = mysqli_query($conn, "SELECT * FROM admins WHERE email = '$email' AND status = 'approved'");
  if (mysqli_num_rows($check) === 1) {
    $admin = mysqli_fetch_assoc($check);

    if (password_verify($password, $admin['password'])) {
      $_SESSION['admin_id'] = $admin['admin_id'];
      $_SESSION['admin_name'] = $admin['full_name'];
      $_SESSION['is_primary'] = $admin['is_primary'];

      // ✅ Log login time
      $login_time = date('Y-m-d H:i:s');
      $admin_id = $admin['admin_id'];
      $insert_query = "INSERT INTO admin_login_logout_details (admin_id, login_time) VALUES ('$admin_id', '$login_time')";
      mysqli_query($conn, $insert_query);
      $_SESSION['login_log_id'] = mysqli_insert_id($conn); // store insert ID for logout

      // Redirect
      if ($admin['is_primary'] == 1) {
        header("Location: ../../admin/primary-options.php");
      } else {
        header("Location: ../../admin/dashboard.php");
      }
      exit();
    } else {
      echo "<script>alert('Invalid password'); window.history.back();</script>";
    }
  } else {
    echo "<script>alert('Account not approved or doesn\'t exist'); window.history.back();</script>";
  }
}
?>
