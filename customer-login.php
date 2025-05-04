<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Customer Login</title>
  <link rel="stylesheet" href="css/login.css">
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

  <!-- Message Display -->
  <div style="text-align: center; margin-top: 20px;">
    <?php if (isset($_GET['signup']) && $_GET['signup'] === 'success') : ?>
      <p style="color: green; font-weight: bold;">Account created successfully! Please login.</p>
    <?php endif; ?>

    <?php if (isset($_GET['reset']) && $_GET['reset'] === 'success') : ?>
      <p style="color: green; font-weight: bold;">Password reset successful! You can now login.</p>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
      <p style="color: red; font-weight: bold;">
        <?php
          if ($_GET['error'] === 'wrongpass') echo "Incorrect password.";
          elseif ($_GET['error'] === 'notfound') echo "Account not found. Please sign up.";
          elseif ($_GET['error'] === 'unverified') echo "Please verify your account using the OTP sent to your email.";
        ?>
      </p>
    <?php endif; ?>
  </div>

  <!-- Login Form -->
  <div class="auth-box">
    <h2>Login to your account</h2>
    <form action="backend/customer_login.php" method="POST">
      <input type="email" name="email" placeholder="Email" required><br>
      <input type="password" name="password" placeholder="Password" required><br>
      
      <div class="form-bottom">
        <a href="forgot-password.php" class="forgot-link">Forgot Password?</a>
      </div>

      <button type="submit" class="yellow-btn">Sign in with email</button>
    </form>

    <p class="alt-text">Don't have an account? <a href="customer-signup.php">Get Started</a></p>
  </div>

</body>
<footer style="text-align: center; padding: 15px 10px; margin-top: 40px; font-size: 14px; color: #777;">
  &copy; <?php echo date("Y"); ?> Car Repair & Service Management System. All rights reserved.
</footer>

</html>
