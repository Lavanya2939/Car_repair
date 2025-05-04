<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header("Location: admin-login.php");
  exit();
}
include("../backend/db.php");

// Optional: filter by customer name
$search = $_GET['search'] ?? '';

// Fetch billing & customer data
$query = "
  SELECT b.billing_id, b.service_id, b.total_amount, b.created_at,
         c.first_name, c.last_name
  FROM billing b
  JOIN customers c ON b.customer_id = c.customer_id
  WHERE CONCAT(c.first_name, ' ', c.last_name) LIKE '%$search%'
  ORDER BY c.first_name ASC, b.created_at DESC
";
$results = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - Invoices</title>
  <link rel="stylesheet" href="../css/admin-dashboard.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    .main-content { margin-left: 250px; padding: 20px; }
    .table-box {
      background: #fff; padding: 25px;
      border-radius: 12px; box-shadow: 0 0 10px rgba(0,0,0,0.08);
      max-width: 1100px; margin: auto;
    }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { padding: 12px; border: 1px solid #ccc; text-align: center; }
    th { background-color: #f5f5f5; }
    .topbar {
      display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;
    }
    .search-box {
      text-align: right;
    }
    .search-box input {
      padding: 6px 10px; border-radius: 6px; border: 1px solid #ccc;
    }
    .btn-link {
      background: #007BFF;
      color: #fff;
      padding: 6px 10px;
      text-decoration: none;
      border-radius: 5px;
    }
    .btn-link:hover { background: #0056b3; }
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
      <h1>Generated Invoices</h1>
      <a href="dashboard.php"><i class="fas fa-arrow-left"></i> Go Back</a>
    </div>

    <div class="table-box">
      <form method="GET" class="search-box">
        <input type="text" name="search" placeholder="Search by customer name" value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Search</button>
      </form>

      <table>
        <thead>
          <tr>
            <th>Invoice</th>
            <th>Billing ID</th>
            <th>Service ID</th>
            <th>Customer</th>
            <th>Total ($)</th>
            <th>Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $invoiceDir = "../invoices/";
        $count = 0;
        while ($row = mysqli_fetch_assoc($results)):
          $invoiceFile = "invoice_" . $row['billing_id'] . ".pdf";
          $invoicePath = $invoiceDir . $invoiceFile;

          if (file_exists($invoicePath)):
            $count++;
        ?>
          <tr>
            <td><?= $invoiceFile ?></td>
            <td>#<?= $row['billing_id'] ?></td>
            <td>#<?= $row['service_id'] ?></td>
            <td><?= $row['first_name'] . ' ' . $row['last_name'] ?></td>
            <td>$<?= number_format($row['total_amount'], 2) ?></td>
            <td><?= $row['created_at'] ?></td>
            <td>
              <a class="btn-link" href="<?= $invoicePath ?>" target="_blank">View PDF</a>
            </td>
          </tr>
        <?php
          endif;
        endwhile;
        if ($count === 0) {
          echo "<tr><td colspan='7'>No invoices found.</td></tr>";
        }
        ?>
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
