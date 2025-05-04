<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password</title>
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="auth-body">

  <header class="top-nav">
    <div class="logo">
      <img src="images/logo.png" alt="Logo">
      <span><span style="color: #D9E84E;">Car</span> <b>Repair</b></span>
    </div>
    <nav>
      <a href="index.html"><i class="fas fa-home"></i> Home</a>
    </nav>
  </header>

  <div class="auth-box">
    <h2>Create New Password</h2>
    <form action="backend/update_password.php" method="POST">
      <input type="password" name="new_password" placeholder="New Password" required>
      <input type="password" name="confirm_password" placeholder="Confirm Password" required>
      <button type="submit" class="yellow-btn">Reset Password</button>
    </form>
  </div>
  <footer style="text-align: center; padding: 15px 10px; margin-top: 40px; font-size: 14px; color: #777;">
  &copy; <?php echo date("Y"); ?> Car Repair & Service Management System. All rights reserved.
</footer>

</body>
</html>
