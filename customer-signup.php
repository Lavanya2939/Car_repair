<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Customer Sign Up</title>
  <link rel="stylesheet" href="css/signup.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="auth-body">

  <!-- Top Navigation -->
  <header class="top-nav">
    <div class="logo">
      <img src="images/logo.png" alt="Logo">
      <span><span style="color: #D9E84E;">Car</span> <b>Repair</b></span>
    </div>
    <nav>
      <a href="index.html"><i class="fas fa-home"></i> Home</a>
    </nav>
  </header>

  <!-- Signup Form -->
  <div class="auth-box">
    <h2>Create your ID</h2>
    <form action="backend/customer_register.php" method="POST">
      <div class="row">
        <input type="text" name="first_name" placeholder="First name" required>
        <input type="text" name="last_name" placeholder="Last name" required>
      </div>
      <input type="email" name="email" placeholder="Email" required><br>
      <input type="password" name="password" placeholder="Password" required><br>

      <label><input type="checkbox" required> I agree to the Terms and Conditions</label><br>
      <button type="submit" class="yellow-btn">Sign up with email</button>
    </form>

    <p class="alt-text">Already have an account? <a href="customer-login.php">Login Now</a></p>
  </div>

</body>
<footer style="text-align: center; padding: 15px 10px; margin-top: 40px; font-size: 14px; color: #777;">
  &copy; <?php echo date("Y"); ?> Car Repair & Service Management System. All rights reserved.
</footer>

</html>
