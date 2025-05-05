<?php
include("../backend/session.php");
include("../backend/db.php");

$customerId = $_SESSION['customer_id'];
$vehicles = mysqli_query($conn, "SELECT * FROM vehicles WHERE customer_id = '$customerId'");
$mechanics = mysqli_query($conn, "SELECT * FROM mechanics WHERE status = 'active'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Book Appointment</title>
  <link rel="stylesheet" href="../css/dashboard.css">
  <style>
    form {
      background: white;
      padding: 20px;
      max-width: 700px;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      margin-bottom: 40px;
    }
    input, select, textarea {
      width: 100%;
      margin: 10px 0;
      padding: 10px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }
    .success {
      background: #eafaf1;
      padding: 15px;
      border-left: 4px solid #2ecc71;
      margin-bottom: 20px;
      border-radius: 6px;
    }
  </style>
</head>
<body>

<div class="sidebar">
  <div class="logo"><h2>🚗 Car Repair</h2></div>
  <ul>
    <li><a href="dashboard.php">Dashboard</a></li>
    <li><a href="vehicles.php">Vehicles</a></li>
    <li><a class="active" href="appointments.php">Bookings</a></li>
    <li><a href="payments.php">Payments</a></li>
    <li><a href="support_tickets.php">Support</a></li>
    <li><a href="rate_service.php">Rate Service</a></li>
    <li><a href="profile_settings.php">Profile</a></li>
    <li><a href="logout.php">Logout</a></li>
  </ul>
</div>

<div class="main-content">
  <h2>Book a Service Appointment</h2>

  <?php if (isset($_GET['success'])): ?>
    <div class="success">
      ✅ Appointment booked! You'll receive a confirmation email once the admin approves it.
    </div>
  <?php endif; ?>

  <form method="POST" action="../backend/add_appointment.php">
    <label>Select Vehicle:</label>
    <select name="vehicle_id" required>
      <option value="">-- Choose Vehicle --</option>
      <?php while ($v = mysqli_fetch_assoc($vehicles)): ?>
        <option value="<?= $v['vehicle_id'] ?>"><?= $v['make'] ?> <?= $v['model'] ?> (<?= $v['number_plate'] ?>)</option>
      <?php endwhile; ?>
    </select>

    <label>Select Mechanic:</label>
    <select name="mechanic_id" required>
      <option value="">-- Choose Mechanic --</option>
      <?php while ($m = mysqli_fetch_assoc($mechanics)): ?>
        <option value="<?= $m['mechanic_id'] ?>"><?= $m['first_name'] . ' ' . $m['last_name'] ?> (<?= $m['specialty'] ?>)</option>
      <?php endwhile; ?>
    </select>

    <label>Service Date:</label>
    <input type="date" name="service_date" min="<?= date('Y-m-d') ?>" required>

    <label>Service Time:</label>
    <input type="time" name="service_time" required>

    <label>Issue Description (optional):</label>
    <textarea name="issue" rows="4" placeholder="E.g., engine noise, AC not working..."></textarea>

    <button type="submit">Confirm Appointment</button>
  </form>
</div>
</body>


</html>
