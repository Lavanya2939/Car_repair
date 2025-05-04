<?php
session_start();
if (!isset($_SESSION['customer_id'])) {
  header("Location: customer-login.php");
  exit();
}

include("../backend/db.php");

$customer_id = $_SESSION['customer_id'];
$msg = "";

// Handle rating submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_rating'])) {
  $service_id = $_POST['service_id'];
  $mechanic_id = $_POST['mechanic_id'];
  $rating = intval($_POST['rating']);
  $feedback = mysqli_real_escape_string($conn, $_POST['feedback']);

  // Prevent duplicate rating
  $check = mysqli_query($conn, "SELECT * FROM service_ratings WHERE service_id = '$service_id' AND customer_id = '$customer_id'");
  if (mysqli_num_rows($check) == 0) {
    mysqli_query($conn, "INSERT INTO service_ratings (customer_id, mechanic_id, service_id, rating, feedback) 
                         VALUES ('$customer_id', '$mechanic_id', '$service_id', '$rating', '$feedback')");
    $msg = "Thank you for your feedback!";
  } else {
    $msg = "You've already submitted a rating for this service.";
  }
}

// Fetch completed appointments (services) not yet rated
$appointments = mysqli_query($conn, "
  SELECT a.appointment_id AS service_id, m.mechanic_id, m.first_name, m.last_name
  FROM appointments a
  JOIN mechanics m ON a.mechanic_id = m.mechanic_id
  WHERE a.customer_id = '$customer_id'
    AND a.status = 'completed'
    AND NOT EXISTS (
      SELECT 1 FROM service_ratings sr 
      WHERE sr.service_id = a.appointment_id AND sr.customer_id = '$customer_id'
    )
");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Rate Service</title>
  <link rel="stylesheet" href="../css/dashboard.css">
  <style>
    .main-content { padding: 30px; }
    .rating-card {
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      margin-bottom: 20px;
    }
    select, textarea {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border-radius: 5px;
      border: 1px solid #ccc;
    }
    .submit-btn {
      background: #28a745;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }
    .success-msg {
      color: green;
      margin-bottom: 20px;
    }
    .sidebar {
      width: 230px;
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      background: #1e1e2f;
      padding-top: 60px;
      color: white;
    }
    .sidebar h2 {
      text-align: center;
      margin-bottom: 30px;
    }
    .sidebar ul {
      list-style-type: none;
      padding: 0;
    }
    .sidebar ul li a {
      display: block;
      padding: 12px 20px;
      color: white;
      text-decoration: none;
      transition: background 0.2s ease-in-out;
    }
    .sidebar ul li a:hover,
    .sidebar ul li a.active {
      background-color: #444;
    }
    .main-content {
      margin-left: 250px;
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <h2>Customer</h2>
  <ul>
    <li><a href="dashboard.php">Dashboard</a></li>
    <li><a class="active" href="rate_service.php">Rate Service</a></li>
    <li><a href="logout.php">Logout</a></li>
  </ul>
</div>

<!-- Main Content -->
<div class="main-content">
  <h2>Rate Your Completed Services</h2>

  <?php if ($msg): ?>
    <p class="success-msg"><?= $msg ?></p>
  <?php endif; ?>

  <?php if (mysqli_num_rows($appointments) > 0): ?>
    <?php while ($row = mysqli_fetch_assoc($appointments)): ?>
      <div class="rating-card">
        <form method="POST">
          <p><strong>Mechanic:</strong> <?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></p>
          <input type="hidden" name="service_id" value="<?= $row['service_id'] ?>">
          <input type="hidden" name="mechanic_id" value="<?= $row['mechanic_id'] ?>">

          <label for="rating">Rating (1 to 5):</label>
          <select name="rating" required>
            <option value="">-- Choose Rating --</option>
            <option value="5">★★★★★ (5)</option>
            <option value="4">★★★★☆ (4)</option>
            <option value="3">★★★☆☆ (3)</option>
            <option value="2">★★☆☆☆ (2)</option>
            <option value="1">★☆☆☆☆ (1)</option>
          </select>

          <label for="feedback">Feedback:</label>
          <textarea name="feedback" required placeholder="Write your experience..."></textarea>

          <button type="submit" name="submit_rating" class="submit-btn">Submit Rating</button>
        </form>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p>No completed services found to rate.</p>
  <?php endif; ?>
</div>

</body>
<footer style="text-align: center; padding: 15px 10px; margin-top: 40px; font-size: 14px; color: #777;">
  &copy; <?php echo date("Y"); ?> Car Repair & Service Management System. All rights reserved.
</footer>

</html>
