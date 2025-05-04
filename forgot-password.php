<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password</title>
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
    <h2>Forgot Password</h2>
    <form action="backend/send_reset_otp.php" method="POST">
      <input type="email" name="email" placeholder="Enter your registered email" required>
      <button type="submit" class="yellow-btn">Send OTP</button>
    </form>
  </div>
</body>
<footer style="text-align: center; padding: 15px 10px; margin-top: 40px; font-size: 14px; color: #777;">
  &copy; <?php echo date("Y"); ?> Car Repair & Service Management System. All rights reserved.
</footer>

</html>
