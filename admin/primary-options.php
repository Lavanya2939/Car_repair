<?php
include("../backend/admin/session.php");

if (!isset($_SESSION['is_primary']) || $_SESSION['is_primary'] != 1) {
  // Only primary admin allowed here
  header("Location: dashboard.php");
  exit();
}

$adminName = $_SESSION['admin_name'];
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Admin Panel Options</title>
  <link rel="stylesheet" href="../css/admin.css">
  <style>
    body {
      background-color: #fcfc74;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      font-family: 'Segoe UI', sans-serif;
    }
    .panel-box {
      background: white;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.1);
      text-align: center;
    }
    h2 {
      margin-bottom: 30px;
    }
    .btn {
      display: inline-block;
      margin: 10px;
      padding: 14px 30px;
      background-color: #111;
      color: white;
      border: none;
      border-radius: 8px;
      text-decoration: none;
      font-size: 16px;
    }
    .btn:hover {
      background-color: #333;
    }
  </style>
</head>
<body>
  <div class="panel-box">
    <h2>Welcome, <?php echo htmlspecialchars($adminName); ?> 👋</h2>
    <p>Please choose an action:</p>
    <a href="dashboard.php" class="btn">Go to Dashboard</a>
    <a href="approve_admins.php" class="btn">Approve Admin Requests</a>
  </div>
</body>
<footer style="text-align: center; padding: 15px 10px; margin-top: 40px; font-size: 14px; color: #777;">
  &copy; <?php echo date("Y"); ?> Car Repair & Service Management System. All rights reserved.
</footer>

</html>
