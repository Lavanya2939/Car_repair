<?php
include("../backend/session.php");
include("../backend/db.php");

$customerId = $_SESSION['customer_id'];

// Fetch notifications for this customer
$query = "
SELECT * FROM notifications 
WHERE customer_id = '$customerId' 
ORDER BY created_at DESC
";

$result = mysqli_query($conn, $query);

// Mark notifications as read
if (isset($_GET['mark_read'])) {
    $notification_id = $_GET['mark_read'];
    mysqli_query($conn, "UPDATE notifications SET status = 'read' WHERE notification_id = '$notification_id'");
    echo "<script>window.location.href = 'notifications.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Notifications</title>
  <link rel="stylesheet" href="../css/dashboard.css">
  <style>
    .main-content { padding: 30px; }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 30px;
      background: white;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      border-radius: 8px;
    }
    th, td {
      padding: 14px;
      text-align: left;
      border-bottom: 1px solid #eee;
    }
    th {
      background-color: #1e1e2f;
      color: white;
    }
    .unread {
      background-color: #f9e4e4;
    }
    .read {
      background-color: #ecf0f1;
    }
    .status-badge {
      font-weight: bold;
      color: #2ecc71;
    }
    .btn-read {
      background: #2980b9;
      color: white;
      padding: 6px 12px;
      text-decoration: none;
      border-radius: 6px;
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <div class="logo"><h2>🚗 Car Repair</h2></div>
  <ul>
    <li><a href="dashboard.php">Dashboard</a></li>
    <li><a class="active" href="notifications.php">Notifications</a></li>
    <li><a href="logout.php">Logout</a></li>
  </ul>
</div>

<!-- Main Content -->
<div class="main-content">
  <h2>Your Notifications</h2>

  <?php if (mysqli_num_rows($result) > 0): ?>
    <table>
      <tr>
        <th>Date</th>
        <th>Message</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr class="<?= ($row['status'] == 'unread') ? 'unread' : 'read' ?>">
          <td><?= date('d M Y H:i', strtotime($row['created_at'])) ?></td>
          <td><?= htmlspecialchars($row['message']) ?></td>
          <td><span class="status-badge"><?= ucfirst($row['status']) ?></span></td>
          <td>
            <?php if ($row['status'] == 'unread'): ?>
              <a href="notifications.php?mark_read=<?= $row['notification_id'] ?>" class="btn-read">Mark as Read</a>
            <?php else: ?>
              <span>Already read</span>
            <?php endif; ?>
          </td>
        </tr>
      <?php endwhile; ?>
    </table>
  <?php else: ?>
    <p>No notifications available.</p>
  <?php endif; ?>
</div>

</body>


</html>
