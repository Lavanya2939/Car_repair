<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header("Location: admin-login.php");
  exit();
}
include("../backend/db.php");

// Filter by status
$statusFilter = $_GET['status'] ?? '';

// Export to CSV
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
  header('Content-Type: text/csv');
  header('Content-Disposition: attachment;filename=payments.csv');

  $output = fopen("php://output", "w");
  fputcsv($output, ['Payment ID', 'Billing ID', 'Service ID', 'Customer', 'Amount', 'Method', 'Status', 'Paid At']);

  $filterSQL = $statusFilter ? "AND p.status = '$statusFilter'" : "";
  $exportQuery = "
    SELECT p.*, b.service_id, c.first_name, c.last_name
    FROM payments p
    JOIN billing b ON p.billing_id = b.billing_id
    JOIN customers c ON b.customer_id = c.customer_id
    WHERE 1 $filterSQL
  ";
  $exportRes = mysqli_query($conn, $exportQuery);
  while ($row = mysqli_fetch_assoc($exportRes)) {
    fputcsv($output, [
      $row['payment_id'], $row['billing_id'], $row['service_id'],
      $row['first_name'] . ' ' . $row['last_name'],
      $row['amount'], $row['method'], $row['status'], $row['paid_at']
    ]);
  }
  fclose($output);
  exit();
}

// Main query
$filterSQL = $statusFilter ? "AND p.status = '$statusFilter'" : "";
$query = "
  SELECT p.*, b.service_id, c.first_name, c.last_name
  FROM payments p
  JOIN billing b ON p.billing_id = b.billing_id
  JOIN customers c ON b.customer_id = c.customer_id
  WHERE 1 $filterSQL
  ORDER BY p.paid_at DESC
";
$payments = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - Payments</title>
  <link rel="stylesheet" href="../css/admin-dashboard.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    .main-content { margin-left: 250px; padding: 20px; }
    .table-box {
      background: #fff; padding: 25px;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0,0,0,0.08);
      max-width: 1100px;
      margin: 0 auto;
    }
    table {
      width: 100%; border-collapse: collapse; margin-top: 20px;
    }
    th, td {
      padding: 12px;
      border: 1px solid #ccc;
      text-align: center;
    }
    th { background-color: #f5f5f5; }
    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }
    .filter-form {
      display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;
    }
    .paid { color: green; font-weight: bold; }
    .pending { color: orange; font-weight: bold; }
    .btn-export {
      background: #007BFF;
      color: #fff;
      padding: 6px 12px;
      border: none;
      border-radius: 6px;
      margin-left: 10px;
      cursor: pointer;
    }
    .btn-export:hover {
      background: #0056b3;
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
    <div class="topbar">
      <h1>Customer Payments</h1>
      <a href="dashboard.php"><i class="fas fa-arrow-left"></i> Go Back</a>
    </div>

    <div class="table-box">
      <div class="filter-form">
        <form method="GET" action="payments.php">
          <label for="status">Filter by Status:</label>
          <select name="status" onchange="this.form.submit()">
            <option value="">All</option>
            <option value="paid" <?= $statusFilter === 'paid' ? 'selected' : '' ?>>Paid</option>
            <option value="pending" <?= $statusFilter === 'pending' ? 'selected' : '' ?>>Pending</option>
          </select>
        </form>
        <div>
          <a href="payments.php?export=csv<?= $statusFilter ? '&status=' . $statusFilter : '' ?>" class="btn-export">Export CSV</a>
        </div>
      </div>

      <table>
        <thead>
          <tr>
            <th>Payment ID</th>
            <th>Billing ID</th>
            <th>Service ID</th>
            <th>Customer</th>
            <th>Amount</th>
            <th>Method</th>
            <th>Status</th>
            <th>Paid At</th>
          </tr>
        </thead>
        <tbody>
          <?php if (mysqli_num_rows($payments) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($payments)): ?>
              <tr>
                <td>#<?= $row['payment_id'] ?></td>
                <td>#<?= $row['billing_id'] ?></td>
                <td>#<?= $row['service_id'] ?></td>
                <td><?= $row['first_name'] . ' ' . $row['last_name'] ?></td>
                <td>$<?= number_format($row['amount'], 2) ?></td>
                <td><?= $row['method'] ?></td>
                <td class="<?= $row['status'] ?>"><?= ucfirst($row['status']) ?></td>
                <td><?= $row['paid_at'] ?? '—' ?></td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="8">No payment records found.</td></tr>
          <?php endif; ?>
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
