<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header("Location: admin-login.php");
  exit();
}
include("../backend/db.php");

// Generate Bill
if (isset($_POST['generate_bill'])) {
  $service_id = $_POST['service_id'];
  $customer_id = $_POST['customer_id'];
  $repair_cost = floatval($_POST['repair_cost']);
  $tax_percent = floatval($_POST['tax_percent']);

  $item_ids = $_POST['item_id'] ?? [];
  $quantities = $_POST['quantity'] ?? [];

  $base_amount = $repair_cost;
  $item_data = [];

  for ($i = 0; $i < count($item_ids); $i++) {
    $item_id = intval($item_ids[$i]);
    $qty = intval($quantities[$i]);
    if ($qty <= 0) continue;

    $item = mysqli_fetch_assoc(mysqli_query($conn, "SELECT unit_price, quantity FROM inventory WHERE item_id = $item_id"));
    if (!$item || $item['quantity'] < $qty) continue;

    $unit_price = floatval($item['unit_price']);
    $total_price = $unit_price * $qty;
    $base_amount += $total_price;

    $item_data[] = [
      'item_id' => $item_id,
      'quantity' => $qty,
      'total_price' => $total_price
    ];
  }

  if ($base_amount > 0) {
    $tax_amount = ($base_amount * $tax_percent) / 100;
    $total_amount = $base_amount + $tax_amount;

    $check = mysqli_query($conn, "SELECT * FROM billing WHERE service_id = '$service_id'");
    if (mysqli_num_rows($check) == 0) {
      $insert = mysqli_query($conn, "INSERT INTO billing (service_id, customer_id, total_amount, repair_cost, status) 
                    VALUES ('$service_id', '$customer_id', '$total_amount', '$repair_cost', 'unpaid')");
      $billing_id = mysqli_insert_id($conn);

      foreach ($item_data as $item) {
        mysqli_query($conn, "INSERT INTO billing_items (billing_id, item_id, quantity, cost)
                             VALUES ('$billing_id', '{$item['item_id']}', '{$item['quantity']}', '{$item['total_price']}')");
        mysqli_query($conn, "UPDATE inventory SET quantity = quantity - {$item['quantity']} WHERE item_id = {$item['item_id']}");
      }

      $msg = "Bill generated successfully.";
    } else {
      $msg = "Bill already exists for this service.";
    }
  } else {
    $msg = "Please select at least one valid item or enter a repair cost.";
  }
}

// Get data
$services = mysqli_query($conn, "
  SELECT a.appointment_id, c.customer_id, c.first_name, c.last_name, v.vehicle_model, v.vehicle_number
  FROM appointments a
  JOIN customers c ON a.customer_id = c.customer_id
  JOIN vehicles v ON a.vehicle_id = v.vehicle_id
  WHERE a.status = 'completed'
");

$inventory = mysqli_query($conn, "SELECT * FROM inventory WHERE status = 'available' AND quantity > 0");

$bills = mysqli_query($conn, "
  SELECT b.*, c.first_name, c.last_name 
  FROM billing b 
  JOIN customers c ON b.customer_id = c.customer_id 
  ORDER BY b.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Billing</title>
  <link rel="stylesheet" href="../css/admin-dashboard.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    .main-content { margin-left: 250px; padding: 20px; }
    .form-box, .table-box {
      background: #fff; padding: 25px; border-radius: 12px;
      box-shadow: 0 0 10px rgba(0,0,0,0.08); margin-bottom: 30px;
      max-width: 1100px; margin-left: auto; margin-right: auto;
    }
    input, select {
      width: 100%; padding: 10px; margin-bottom: 15px;
      border: 1px solid #ccc; border-radius: 5px;
    }
    button { padding: 8px 16px; border-radius: 6px; cursor: pointer; }
    .success-msg {
      background: #e0f9e0; color: #2e7d32;
      padding: 10px; border-left: 5px solid #4caf50;
      text-align: center; margin-bottom: 15px;
    }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    table th, table td {
      padding: 10px; border: 1px solid #ccc; text-align: center;
    }
    .paid { color: green; font-weight: bold; }
    .unpaid { color: red; font-weight: bold; }
    .topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .item-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    .item-table th, .item-table td {
      border: 1px solid #ccc; padding: 10px; text-align: center;
    }
    .item-table th { background-color: #f9f9f9; }
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
      <h1>Billing</h1>
      <a href="dashboard.php"><i class="fas fa-arrow-left"></i> Go Back</a>
    </div>

    <div class="form-box">
      <h2>Generate New Bill</h2>
      <?php if (isset($msg)) echo "<div class='success-msg'>$msg</div>"; ?>
      <form method="POST">
        <label>Select Completed Service:</label>
        <select name="service_id" required onchange="autoFillCustomer(this)">
          <option value="">-- Choose --</option>
          <?php while ($s = mysqli_fetch_assoc($services)): ?>
            <option value="<?= $s['appointment_id'] ?>" data-customer="<?= $s['customer_id'] ?>">
              #<?= $s['appointment_id'] ?> - <?= $s['first_name'] ?> <?= $s['last_name'] ?> (<?= $s['vehicle_model'] ?> <?= $s['vehicle_number'] ?>)
            </option>
          <?php endwhile; ?>
        </select>
        <input type="hidden" name="customer_id" id="customer_id">

        <label>Repair Cost ($):</label>
        <input type="number" name="repair_cost" step="0.01" required>

        <h3>Items Used:</h3>
        <table class="item-table">
          <thead>
            <tr>
              <th>Item Name</th>
              <th>Unit Price ($)</th>
              <th>In Stock</th>
              <th>Quantity Used</th>
            </tr>
          </thead>
          <tbody>
            <?php mysqli_data_seek($inventory, 0); while ($item = mysqli_fetch_assoc($inventory)): ?>
              <tr>
                <td><?= $item['item_name'] ?></td>
                <td><?= number_format($item['unit_price'], 2) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td>
                  <input type="hidden" name="item_id[]" value="<?= $item['item_id'] ?>">
                  <input type="number" name="quantity[]" min="0" max="<?= $item['quantity'] ?>" style="width: 80px;">
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>

        <label>Tax Percentage (%):</label>
        <input type="number" name="tax_percent" step="0.01" required>

        <button type="submit" name="generate_bill">Generate Bill</button>
      </form>
    </div>

    <div class="table-box">
      <h2>Existing Bills</h2>
      <table>
        <tr>
          <th>Billing ID</th>
          <th>Customer</th>
          <th>Total ($)</th>
          <th>Repair Cost</th>
          <th>Status</th>
          <th>Payment Method</th>
          <th>Action</th>
        </tr>
        <?php mysqli_data_seek($bills, 0); while ($b = mysqli_fetch_assoc($bills)): ?>
          <tr>
            <td>#<?= $b['billing_id'] ?></td>
            <td><?= $b['first_name'] . ' ' . $b['last_name'] ?></td>
            <td>$<?= number_format($b['total_amount'], 2) ?></td>
            <td>$<?= number_format($b['repair_cost'], 2) ?></td>
            <td class="<?= strtolower($b['status']) ?>"><?= ucfirst($b['status']) ?></td>
            <td><?= $b['payment_method'] ?? 'N/A' ?></td>
            <td>
              <?php if ($b['status'] === 'paid'): ?>
                ✅ Paid by Customer
              <?php else: ?>
                <span style="color: orange;">Awaiting Payment</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; ?>
      </table>
    </div>
  </div>
</div>

<script>
function autoFillCustomer(select) {
  const selected = select.options[select.selectedIndex];
  const customerId = selected.getAttribute("data-customer");
  document.getElementById("customer_id").value = customerId;
}
</script>

</body>
<footer style="text-align: center; padding: 15px 10px; margin-top: 40px; font-size: 14px; color: #777;">
  &copy; <?php echo date("Y"); ?> Car Repair & Service Management System. All rights reserved.
</footer>

</html>
