<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login - Car Repair</title>
  <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
  <div class="signup-container">
    <form action="../backend/admin/admin_login.php" method="POST" class="signup-form">
      <h2>Admin Login</h2>

      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>

      <button type="submit">Login</button>

      <p>Don't have an account? <a href="admin-signup.php">Sign up here</a></p>
    </form>
  </div>
</body>
<footer style="text-align: center; padding: 15px 10px; margin-top: 40px; font-size: 14px; color: #777;">
  &copy; <?php echo date("Y"); ?> Car Repair & Service Management System. All rights reserved.
</footer>

</html>
