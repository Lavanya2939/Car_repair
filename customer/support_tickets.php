<?php
include("../backend/session.php");
include("../backend/db.php");

$customerId = $_SESSION['customer_id'];
$result = mysqli_query($conn, "SELECT * FROM support_tickets WHERE customer_id = '$customerId' ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Support Tickets</title>
  <link rel="stylesheet" href="../css/dashboard.css">
  <style>
    .main-content { padding: 30px; font-family: 'Segoe UI', sans-serif; }
    .ticket-card {
      background: #fff; border-left: 6px solid #1e1e2f;
      padding: 20px; margin-bottom: 20px;
      border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .ticket-header {
      display: flex; justify-content: space-between;
      align-items: center;
    }
    .ticket-status {
      padding: 5px 10px;
      border-radius: 5px;
      font-size: 13px;
      font-weight: bold;
    }
    .ticket-status.open { background: #e0f9e0; color: #2e7d32; }
    .ticket-status.closed { background: #fddede; color: #d32f2f; }
    .admin-reply {
      background: #f5f5f5;
      border-left: 4px solid #1e1e2f;
      padding: 12px;
      margin-top: 10px;
      border-radius: 6px;
    }
    form {
      background: #fff; padding: 20px;
      border-radius: 10px; margin-bottom: 40px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      max-width: 700px;
    }
    input, textarea {
      width: 100%; padding: 12px;
      margin-top: 10px; margin-bottom: 15px;
      border: 1px solid #ccc; border-radius: 6px;
    }
    button {
      background: #1e1e2f; color: white;
      padding: 10px 20px; border: none;
      border-radius: 6px; cursor: pointer;
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <div class="logo"><h2>🚗 Car Repair</h2></div>
  <ul>
    <li><a href="dashboard.php">Dashboard</a></li>
    <li><a class="active" href="support_tickets.php">Support</a></li>
    <li><a href="logout.php">Logout</a></li>
  </ul>
</div>

<div class="main-content">
  <h2>Raise a Support Ticket</h2>

  <form method="POST" action="../backend/save_ticket.php">
    <label>Subject:</label>
    <input type="text" name="subject" required>
    <label>Message:</label>
    <textarea name="message" rows="4" required></textarea>
    <button type="submit">Submit Ticket</button>
  </form>

  <h2>My Tickets</h2>

  <?php if (mysqli_num_rows($result) > 0): ?>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
      <div class="ticket-card">
        <div class="ticket-header">
          <h4><?= htmlspecialchars($row['subject']) ?></h4>
          <span class="ticket-status <?= strtolower($row['status']) ?>"><?= ucfirst($row['status']) ?></span>
        </div>
        <p><strong>Submitted:</strong> <?= date("d M Y, H:i A", strtotime($row['created_at'])) ?></p>
        <p><?= nl2br(htmlspecialchars($row['message'])) ?></p>
        <?php if ($row['admin_reply']): ?>
          <div class="admin-reply">
            <strong>Admin Reply:</strong><br>
            <?= nl2br(htmlspecialchars($row['admin_reply'])) ?>
          </div>
        <?php endif; ?>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p>No tickets found.</p>
  <?php endif; ?>
</div>

</body>
<footer style="text-align: center; padding: 15px 10px; margin-top: 40px; font-size: 14px; color: #777;">
  &copy; <?php echo date("Y"); ?> Car Repair & Service Management System. All rights reserved.
</footer>

</html>
