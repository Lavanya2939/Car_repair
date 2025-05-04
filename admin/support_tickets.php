<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header("Location: admin-login.php");
  exit();
}
include("../backend/db.php");

// Include PHPMailer
require '../backend/PHPMailer/src/PHPMailer.php';
require '../backend/PHPMailer/src/SMTP.php';
require '../backend/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Handle admin reply & close
if (isset($_POST['respond_ticket'])) {
  $ticket_id = $_POST['ticket_id'];
  $reply = mysqli_real_escape_string($conn, $_POST['admin_reply']);
  $customer_email = $_POST['customer_email'];
  $customer_name = $_POST['customer_name'];

  $mail = new PHPMailer(true);
  try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'carrepair4356@gmail.com'; // Gmail
    $mail->Password = 'mfvrbmfteiavlwad';        // App Password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('carrepair4356@gmail.com', 'Car Repair Support');
    $mail->addAddress($customer_email, $customer_name);
    $mail->Subject = "Support Ticket Response - Car Repair";
    $mail->Body = "Hi $customer_name,\n\nThank you for contacting support. Here's our response:\n\n$reply\n\nRegards,\nCar Repair Team";

    $mail->send();

    mysqli_query($conn, "UPDATE support_tickets SET status = 'closed', admin_reply = '$reply' WHERE ticket_id = '$ticket_id'");
    $msg = "✅ Reply sent and ticket #$ticket_id closed.";
  } catch (Exception $e) {
    $msg = "❌ Email Error: {$mail->ErrorInfo}";
  }
}

// Filter tickets
$statusFilter = $_GET['status'] ?? '';
$query = "
  SELECT s.*, c.first_name, c.last_name, c.email
  FROM support_tickets s
  JOIN customers c ON s.customer_id = c.customer_id
  " . ($statusFilter ? "WHERE s.status = '$statusFilter'" : "") . "
  ORDER BY s.created_at DESC
";
$tickets = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - Support Tickets</title>
  <link rel="stylesheet" href="../css/admin-dashboard.css">
  <style>
    body { font-family: 'Segoe UI', sans-serif; }
    .main-content { margin-left: 250px; padding: 30px; }
    .table-box {
      background: #fff; padding: 25px;
      border-radius: 12px; box-shadow: 0 0 10px rgba(0,0,0,0.08);
      max-width: 1100px; margin: auto;
    }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td {
      padding: 12px; border: 1px solid #ddd; text-align: left;
    }
    th { background: #1e1e2f; color: white; }
    .open { color: green; font-weight: bold; }
    .closed { color: red; font-weight: bold; }
    .btn-close {
      background: #1e1e2f; color: white;
      padding: 6px 12px; border: none;
      border-radius: 6px; cursor: pointer;
    }
    .filter-form {
      text-align: right;
      margin-bottom: 20px;
    }
    .msg-box {
      background: #e0f9e0;
      padding: 10px;
      color: #2e7d32;
      text-align: center;
      border-left: 5px solid green;
      margin-bottom: 15px;
    }
    textarea {
      width: 100%; height: 80px;
      border: 1px solid #ccc;
      border-radius: 6px;
      padding: 8px;
      margin-bottom: 8px;
    }
  </style>
</head>
<body>

<div class="admin-container">
  <div class="sidebar">
    <h2>Admin</h2>
    <a href="dashboard.php"><i class="fas fa-gauge"></i> Dashboard</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </div>

  <div class="main-content">
    <h1>Customer Support Tickets</h1>

    <?php if (isset($msg)) echo "<div class='msg-box'>$msg</div>"; ?>

    <form class="filter-form" method="GET">
      <label>Status:</label>
      <select name="status" onchange="this.form.submit()">
        <option value="">All</option>
        <option value="open" <?= $statusFilter == 'open' ? 'selected' : '' ?>>Open</option>
        <option value="closed" <?= $statusFilter == 'closed' ? 'selected' : '' ?>>Closed</option>
      </select>
    </form>

    <div class="table-box">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Subject</th>
            <th>Message</th>
            <th>Status</th>
            <th>Reply</th>
            <th>Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = mysqli_fetch_assoc($tickets)): ?>
            <tr>
              <td>#<?= $row['ticket_id'] ?></td>
              <td><?= $row['first_name'] . ' ' . $row['last_name'] ?></td>
              <td><?= htmlspecialchars($row['subject']) ?></td>
              <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
              <td class="<?= $row['status'] ?>"><?= ucfirst($row['status']) ?></td>
              <td>
                <?php if ($row['admin_reply']) {
                  echo nl2br(htmlspecialchars($row['admin_reply']));
                } else {
                  echo "<i>No reply</i>";
                } ?>
              </td>
              <td><?= date("d M Y", strtotime($row['created_at'])) ?></td>
              <td>
                <?php if ($row['status'] === 'open'): ?>
                  <form method="POST" onsubmit="return confirm('Send email and close this ticket?');">
                    <input type="hidden" name="ticket_id" value="<?= $row['ticket_id'] ?>">
                    <input type="hidden" name="customer_email" value="<?= $row['email'] ?>">
                    <input type="hidden" name="customer_name" value="<?= $row['first_name'] ?>">
                    <textarea name="admin_reply" placeholder="Type your reply..." required></textarea>
                    <button type="submit" name="respond_ticket" class="btn-close">Send Reply & Close</button>
                  </form>
                <?php else: ?>
                  <span style="color: gray;">Closed</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

</body>
<footer style="text-align: center; padding: 15px 10px; margin-top: 40px; font-size: 14px; color: #777;">
  &copy; <?php echo date("Y"); ?> Car Repair & Service Management System. All rights reserved.
</footer>

</html>
