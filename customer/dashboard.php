<?php
include("../backend/session.php");
include("../backend/db.php");

$customerId = $_SESSION['customer_id'];
$customerName = $_SESSION['customer_name'];

// Fetch stats
$vehicleCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM vehicles WHERE customer_id = '$customerId'"))['total'];
$appointmentCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM appointments WHERE customer_id = '$customerId' AND status = 'upcoming'"))['total'];
$completedServices = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM billing WHERE customer_id = '$customerId' AND status = 'paid'"))['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Customer Dashboard</title>
  <link rel="stylesheet" href="../css/dashboard.css">
  <style>
    .tracker-container {
      display: flex;
      gap: 40px;
      margin-top: 30px;
      justify-content: center;
      flex-wrap: wrap;
    }
    .tracker-card {
      text-align: center;
      font-family: Arial, sans-serif;
    }
    .circle {
      position: relative;
      width: 120px;
      height: 120px;
      background: conic-gradient(#4CAF50 0% 0%, #ccc 0% 100%);
      border-radius: 50%;
      margin: 0 auto 10px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .inner-circle {
      background: #fff;
      width: 80px;
      height: 80px;
      border-radius: 50%;
      line-height: 80px;
      font-size: 22px;
      font-weight: bold;
      color: #333;
      text-align: center;
      box-shadow: inset 0 0 5px rgba(0,0,0,0.1);
    }

    .cards {
      display: flex;
      gap: 20px;
      margin-top: 30px;
    }
    .card {
      background: #fff;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
      flex: 1;
      text-align: center;
    }
    .card h3 {
      margin-bottom: 10px;
      color: #333;
    }

    .quick-access {
      margin-top: 20px;
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
    }
    .quick-access a {
      text-decoration: none;
      background: #eee;
      padding: 10px 20px;
      border-radius: 6px;
      font-weight: bold;
      color: #333;
      transition: 0.3s ease;
    }
    .quick-access a:hover {
      background: #ddd;
    }

    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }
    .profile-box {
      display: flex;
      align-items: center;
      gap: 10px;
      background: #f0f0f0;
      padding: 6px 12px;
      border-radius: 20px;
      text-decoration: none;
      color: #333;
    }
    .circle-profile {
      background: #4CAF50;
      width: 30px;
      height: 30px;
      color: white;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      font-weight: bold;
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <div class="logo">
    <h2>🚗 Car Repair</h2>
  </div>
  <ul>
    <li><a class="active" href="dashboard.php">Dashboard</a></li>
    <li><a href="vehicles.php">Vehicles</a></li>
    <li><a href="appointments.php">Bookings</a></li>
    <li><a href="payments.php">Payments</a></li>
    <li><a href="support_tickets.php">Support</a></li>
    <li><a href="rate_service.php">Rate Service</a></li>
    <li><a href="profile_settings.php">Profile</a></li>
    <li><a href="logout.php">Logout</a></li>
  </ul>
</div>

<!-- Main Content -->
<div class="main-content">
  <!-- Topbar -->
  <div class="topbar">
    <h2>Hi, <?php echo htmlspecialchars($customerName); ?></h2>
    <a href="profile_settings.php" class="profile-box" title="Profile">
      <div class="circle-profile"><?php echo strtoupper(substr($customerName, 0, 1)); ?></div>
      <span><?php echo $customerName; ?></span>
    </a>
  </div>

  <!-- Dashboard Cards -->
  <div class="cards">
    <div class="card">
      <h3>Total Vehicles</h3>
      <p><?php echo $vehicleCount; ?></p>
    </div>
    <div class="card">
      <h3>Upcoming Appointments</h3>
      <p><?php echo $appointmentCount; ?></p>
    </div>
    <div class="card">
      <h3>Completed Payments</h3>
      <p><?php echo $completedServices; ?></p>
    </div>
  </div>

  <!-- Tracker Summary -->
  <h3 style="margin-top: 40px;">Service Tracker</h3>
  <div class="tracker-container">
    <div class="tracker-card">
      <div class="circle" data-percentage="<?= $vehicleCount ?>">
        <div class="inner-circle"><?= $vehicleCount ?></div>
      </div>
      <p>Vehicles</p>
    </div>
    <div class="tracker-card">
      <div class="circle" data-percentage="<?= $appointmentCount ?>">
        <div class="inner-circle"><?= $appointmentCount ?></div>
      </div>
      <p>Appointments</p>
    </div>
    <div class="tracker-card">
      <div class="circle" data-percentage="<?= $completedServices ?>">
        <div class="inner-circle"><?= $completedServices ?></div>
      </div>
      <p>Payments</p>
    </div>
  </div>

  <!-- Quick Access Tools -->
  <h3 style="margin-top: 40px;">Your Tools</h3>
  <div class="quick-access">
    <a href="vehicles.php">🚗 My Vehicles</a>
    <a href="appointments.php">📅 Book Appointment</a>
    <a href="payments.php">💳 Payments</a>
    <a href="support_tickets.php">🆘 Support</a>
    <a href="rate_service.php">⭐ Rate Service</a>
    <a href="profile_settings.php">👤 Profile</a>
  </div>
</div>

<script>
  document.querySelectorAll(".circle").forEach(circle => {
    const percentage = parseInt(circle.getAttribute("data-percentage"));
    const max = 10;
    const percent = Math.min((percentage / max) * 100, 100);
    circle.style.background = `conic-gradient(#4CAF50 ${percent}%, #ccc ${percent}%)`;
  });
</script>

</body>


</html>
