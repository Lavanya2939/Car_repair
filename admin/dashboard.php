<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

include("../backend/db.php");

$admin_id = $_SESSION['admin_id'];
$admin_name = $_SESSION['admin_name'] ?? "Admin";
$is_primary = isset($_SESSION['is_primary']) && $_SESSION['is_primary'] == 1;

// Get profile image
$result = mysqli_query($conn, "SELECT profile_img FROM admins WHERE admin_id = '$admin_id'");
$row = mysqli_fetch_assoc($result);
$admin_img = $row['profile_img'] ?? 'default.png';

// Get count of open support tickets
$tickets_result = mysqli_query($conn, "SELECT COUNT(*) AS open_tickets FROM support_tickets WHERE status = 'open'");
$tickets_data = mysqli_fetch_assoc($tickets_result);
$open_ticket_count = $tickets_data['open_tickets'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../css/admin-dashboard.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    .top-profile {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
      margin-left: 10px;
      border: 2px solid #eee;
    }
    .stat-count {
      font-size: 16px;
      font-weight: bold;
      margin-top: 5px;
      color: #333;
    }
    .notification-icon {
      position: relative;
      cursor: pointer;
    }
    .notification-badge {
      position: absolute;
      top: -5px;
      right: -5px;
      background: red;
      color: white;
      border-radius: 50%;
      font-size: 10px;
      padding: 3px 6px;
    }
  </style>
</head>
<body>

<div class="admin-container">

  <!-- Sidebar -->
  <div class="sidebar">
    <h2>Admin</h2>
    <a href="dashboard.php" class="active"><i class="fas fa-gauge"></i> Dashboard</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </div>

  <!-- Main Content -->
  <div class="main-content">

    <!-- Topbar -->
    <div class="topbar">
      <h1>Dashboard</h1>
      <div class="topbar-icons">
        <div class="notification-icon" onclick="clearNotification()">
          <a href="support_tickets.php"><i class="fas fa-bell"></i></a>
          <?php if ($open_ticket_count > 0): ?>
            <div id="ticketBadge" class="notification-badge"><?= $open_ticket_count ?></div>
          <?php endif; ?>
        </div>
        <i class="fas fa-envelope"></i>
        <a href="profile.php" class="profile">
          <img src="../uploads/admin_profiles/<?php echo $admin_img; ?>" alt="Profile" class="top-profile">
          <span><?php echo htmlspecialchars($admin_name); ?></span>
        </a>
      </div>
    </div>

    <!-- Dashboard Cards -->
    <div class="card-grid">
      <a href="profile.php" class="card"><i class="fas fa-user"></i><span>Profile</span></a>
      <a href="manage_mechanics.php" class="card" data-type="mechanics"><i class="fas fa-user-cog"></i><span>Manage Mechanics</span></a>
      <a href="inventory.php" class="card" data-type="inventory"><i class="fas fa-boxes"></i><span>Inventory</span></a>
      <a href="appointments.php" class="card" data-type="appointments"><i class="fas fa-calendar-check"></i><span>Appointments</span></a>
      <a href="billing.php" class="card" data-type="billing"><i class="fas fa-file-invoice-dollar"></i><span>Billing</span></a>
      <a href="payments.php" class="card" data-type="payments"><i class="fas fa-credit-card"></i><span>Payments</span></a>
      <a href="support_tickets.php" class="card" data-type="tickets"><i class="fas fa-life-ring"></i><span>Support Tickets</span></a>
      <?php if ($is_primary): ?>
        <a href="approve_admins.php" class="card"><i class="fas fa-user-check"></i><span>Approve Admins</span></a>
      <?php endif; ?>
    </div>

  </div>

</div>

<script>
// Simulate clearing notification (for UI only)
function clearNotification() {
  const badge = document.getElementById("ticketBadge");
  if (badge) {
    badge.style.display = "none";
  }
}
</script>

</body>
<footer style="text-align: center; padding: 15px 10px; margin-top: 40px; font-size: 14px; color: #777;">
  &copy; <?php echo date("Y"); ?> Car Repair & Service Management System. All rights reserved.
</footer>

</html>
