<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Signup - Car Repair</title>
  <link rel="stylesheet" href="../css/admin.css"> <!-- same as customer signup -->
</head>
<body>
    <!-- Logo and Home Button -->
<div style="position: absolute; top: 20px; left: 20px;">
  <a href="../index.html" style="text-decoration: none; font-weight: bold; color: #000; font-size: 16px;">← Home</a>
</div>

  <div class="signup-container">
    <form action="../backend/admin/admin_register.php" method="POST" class="signup-form">
      <h2>Admin Sign Up</h2>

      <input type="text" name="full_name" placeholder="Full Name" required>

      <input type="email" name="email" placeholder="Email" required>

      <input type="password" name="password" placeholder="Password" required>

      <input type="password" name="confirm_password" placeholder="Confirm Password" required>

      <button type="submit">Register</button>

      <p>Already have an account? <a href="admin-login.php">Login here</a></p>
    </form>
  </div>
</body>
<footer style="text-align: center; padding: 15px 10px; margin-top: 40px; font-size: 14px; color: #777;">
  &copy; <?php echo date("Y"); ?> Car Repair & Service Management System. All rights reserved.
</footer>

</html>
