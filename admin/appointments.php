<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header("Location: admin-login.php");
  exit();
}
include("../backend/db.php");

// PHPMailer
require_once(__DIR__ . '/../backend/PHPMailer/src/PHPMailer.php');
require_once(__DIR__ . '/../backend/PHPMailer/src/SMTP.php');
require_once(__DIR__ . '/../backend/PHPMailer/src/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Handle status update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
  $appointment_id = $_POST['appointment_id'];
  $new_status = $_POST['new_status'];

  // Get appointment + customer + mechanic info
  $result = mysqli_query($conn, "SELECT a.*, c.email AS customer_email, c.first_name AS customer_fname, c.last_name AS customer_lname, m.first_name AS mech_fname, m.last_name AS mech_lname
    FROM appointments a
    JOIN customers c ON a.customer_id = c.customer_id
    LEFT JOIN mechanics m ON a.mechanic_id = m.mechanic_id
    WHERE a.appointment_id = $appointment_id");

  $data = mysqli_fetch_assoc($result);

  // Update appointment status
  mysqli_query($conn, "UPDATE appointments SET status = '$new_status' WHERE appointment_id = $appointment_id");

  // Send email if status is approved or completed
  if (in_array($new_status, ['approved', 'completed'])) {
    $mail = new PHPMailer(true);
    try {
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->Username = 'carrepair4356@gmail.com';
      $mail->Password = 'mfvrbmfteiavlwad';
      $mail->SMTPSecure = 'tls';
      $mail->Port = 587;

      $mail->setFrom('carrepair4356@gmail.com', 'Car Repair System');
      $mail->addAddress($data['customer_email'], $data['customer_fname']);
      $mail->isHTML(true);

      if ($new_status == 'approved') {
        $mail->Subject = "Your Appointment is Approved!";
        $mail->Body = "
          <h3>Hello {$data['customer_fname']} {$data['customer_lname']},</h3>
          <p>Your appointment has been <strong>approved</strong>.</p>
          <ul>
            <li>Date: {$data['service_date']}</li>
            <li>Time: {$data['service_time']}</li>
            <li>Assigned Mechanic: {$data['mech_fname']} {$data['mech_lname']}</li>
          </ul>
          <p>Please be on time. Thank you for choosing our service!</p>
        ";
      } elseif ($new_status == 'completed') {
        $mail->Subject = "Your Service is Complete!";
        $mail->Body = "
          <h3>Hello {$data['customer_fname']} {$data['customer_lname']},</h3>
          <p>Your vehicle service has been <strong>completed</strong>.</p>
          <p>Please proceed to the payments section for billing and download your invoice.</p>
          <br><p style='color:gray;'>– Car Repair Team</p>
        ";
      }

      $mail->send();
    } catch (Exception $e) {
      error_log("Email error: " . $mail->ErrorInfo);
    }
  }

  $msg = "Appointment status updated successfully.";
}

// Filter logic
$status_filter = $_GET['status'] ?? '';
$where = $status_filter ? "WHERE a.status = '$status_filter'" : "";

// Fetch appointments
$query = "SELECT a.*, 
                 CONCAT(c.first_name, ' ', c.last_name) AS customer_name,
                 CONCAT(m.first_name, ' ', m.last_name) AS mechanic_name
          FROM appointments a
          JOIN customers c ON a.customer_id = c.customer_id
          LEFT JOIN mechanics m ON a.mechanic_id = m.mechanic_id
          $where
          ORDER BY a.service_date DESC";
$appointments = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Appointments - Admin</title>
  <link rel="stylesheet" href="../css/admin-dashboard.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    .content { max-width: 1100px; margin: 20px auto; background: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 0 10px rgba(0,0,0,0.08); }
    .success { background: #e0f9e0; color: #2e7d32; padding: 10px; margin-bottom: 15px; text-align: center; border-left: 6px solid #4caf50; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    table th, table td { padding: 12px; text-align: center; border: 1px solid #ccc; }
    .status-badge { padding: 6px 12px; border-radius: 6px; font-weight: bold; color: white; }
    .pending { background: #ffc107; }
    .approved { background: #007bff; }
    .completed { background: #28a745; }
    .filters { display: flex; justify-content: space-between; margin-bottom: 15px; }
    .filters select { padding: 8px; }
    .form-inline { display: flex; align-items: center; gap: 8px; justify-content: center; }
    .form-inline select { padding: 6px; }
    .form-inline button { padding: 6px 12px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
  </style>
</head>
<body>

<div class="admin-container">
  <div class="sidebar">
    <h2>Admin</h2>
    <a href="dashboard.php"><i class="fas fa-gauge"></i> Dashboard</a>
    <a href="appointments.php" class="active"><i class="fas fa-calendar-check"></i> Appointments</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </div>

  <div class="main-content">
    <div class="topbar">
      <h1>Appointments</h1>
      <a href="dashboard.php"><i class="fas fa-arrow-left"></i> Go Back</a>
    </div>

    <div class="content">
      <?php if (isset($msg)) echo "<div class='success'>$msg</div>"; ?>

      <div class="filters">
        <form method="GET">
          <label for="status">Filter by Status: </label>
          <select name="status" onchange="this.form.submit()">
            <option value="">All</option>
            <option value="pending" <?= $status_filter == 'pending' ? 'selected' : '' ?>>Pending</option>
            <option value="approved" <?= $status_filter == 'approved' ? 'selected' : '' ?>>Approved</option>
            <option value="completed" <?= $status_filter == 'completed' ? 'selected' : '' ?>>Completed</option>
          </select>
        </form>
      </div>

      <table>
        <tr>
          <th>ID</th>
          <th>Customer</th>
          <th>Date</th>
          <th>Time</th>
          <th>Issue</th>
          <th>Status</th>
          <th>Mechanic</th>
          <th>Update</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($appointments)): ?>
          <tr>
            <td><?= $row['appointment_id'] ?></td>
            <td><?= $row['customer_name'] ?></td>
            <td><?= $row['service_date'] ?></td>
            <td><?= $row['service_time'] ?></td>
            <td><?= $row['issue_description'] ?></td>
            <td><span class="status-badge <?= $row['status'] ?>"><?= ucfirst($row['status']) ?></span></td>
            <td><?= $row['mechanic_name'] ?? 'Not Assigned' ?></td>
            <td>
              <form method="POST" class="form-inline">
                <input type="hidden" name="appointment_id" value="<?= $row['appointment_id'] ?>">
                <select name="new_status">
                  <option value="pending" <?= $row['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                  <option value="approved" <?= $row['status'] == 'approved' ? 'selected' : '' ?>>Approved</option>
                  <option value="completed" <?= $row['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                </select>
                <button type="submit" name="update_status">Update</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
      </table>
    </div>
  </div>
</div>

</body>
<footer style="text-align: center; padding: 15px 10px; margin-top: 40px; font-size: 14px; color: #777;">
  &copy; <?php echo date("Y"); ?> Car Repair & Service Management System. All rights reserved.
</footer>

</html>
