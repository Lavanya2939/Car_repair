<?php
include("../backend/session.php");
include("../backend/db.php");

$customerId = $_SESSION['customer_id'];
$msg = "";

// Handle payment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay_bill'])) {
  $billing_id = $_POST['billing_id'];
  $amount_paid = floatval($_POST['amount_paid']);
  $method = $_POST['method'];
  $card_number = $_POST['card_number'] ?? '';
  $card_holder = $_POST['card_holder'] ?? '';
  $upi_id = $_POST['upi_id'] ?? '';

  $masked_card = $card_number ? str_repeat("*", strlen($card_number) - 4) . substr($card_number, -4) : null;
  $transaction_success = true;

  if ($method === "Credit Card" || $method === "Debit Card") {
    if (!$card_holder || !$card_number || strlen($card_number) < 12) {
      $transaction_success = false;
      $msg = "❌ Invalid card details.";
    }
  } elseif ($method === "UPI") {
    if ($upi_id !== "user@upi") {
      $transaction_success = false;
      $msg = "❌ UPI transaction failed. Invalid UPI ID.";
    }
  }

  if ($transaction_success) {
    $bill_query = mysqli_query($conn, "SELECT total_amount FROM billing WHERE billing_id = '$billing_id' AND customer_id = '$customerId' AND status = 'pending'");
    if (mysqli_num_rows($bill_query) == 1) {
      $bill = mysqli_fetch_assoc($bill_query);
      $total = floatval($bill['total_amount']);

      if ($amount_paid >= $total) {
        $payment_id = uniqid("PAY");

        mysqli_query($conn, "INSERT INTO payments (billing_id, amount, method, cardholder_name, masked_card_number, paid_at, status)
                             VALUES ('$billing_id', '$amount_paid', '$method', '$card_holder', '$masked_card', NOW(), 'paid')");

        mysqli_query($conn, "UPDATE billing SET status = 'paid', payment_method = '$method', payment_id = '$payment_id' WHERE billing_id = '$billing_id'");

        $msg = "✅ Payment successful!";
      } else {
        $msg = "❌ Paid amount is less than total.";
      }
    }
  }
}

// Fetch bills and history
$bills = mysqli_query($conn, "
  SELECT b.billing_id, b.total_amount, v.vehicle_model AS model, v.vehicle_number AS number_plate
  FROM billing b
  JOIN appointments a ON b.service_id = a.appointment_id
  JOIN vehicles v ON a.vehicle_id = v.vehicle_id
  WHERE b.customer_id = '$customerId' AND b.status = 'pending'
");

$history = mysqli_query($conn, "
  SELECT p.*, b.total_amount 
  FROM payments p
  JOIN billing b ON p.billing_id = b.billing_id
  WHERE b.customer_id = '$customerId'
  ORDER BY p.paid_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payments</title>
  <link rel="stylesheet" href="../css/dashboard.css">
  <style>
    body { font-family: 'Segoe UI', sans-serif; background: #f5f7fa; }
    .main-content { padding: 40px; }
    .card {
      background: #fff;
      padding: 25px;
      border-radius: 12px;
      margin-bottom: 30px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }
    .card h3 { margin-top: 0; color: #1e1e2f; }
    .success-msg {
      background: #e0f9e0;
      color: #207d32;
      padding: 12px 20px;
      border-left: 5px solid #28a745;
      border-radius: 8px;
      font-weight: 500;
      margin-bottom: 25px;
    }
    input, select {
      width: 100%;
      padding: 12px;
      margin: 12px 0;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 15px;
    }
    .payment-btn {
      background-color: #1e1e2f;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 15px;
      transition: background 0.3s ease;
    }
    .payment-btn:hover {
      background-color: #33334d;
    }
    .card-fields, .upi-field {
      display: none;
    }
    .card-fields.active, .upi-field.active {
      display: block;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 30px;
      background: white;
      box-shadow: 0 4px 10px rgba(0,0,0,0.08);
      border-radius: 10px;
      overflow: hidden;
    }
    th, td {
      padding: 16px;
      text-align: left;
      border-bottom: 1px solid #eee;
      font-size: 15px;
    }
    th {
      background: #1e1e2f;
      color: white;
      font-weight: 600;
    }
  </style>
</head>
<body>

<div class="sidebar">
  <div class="logo"><h2>🚗 Car Repair</h2></div>
  <ul>
    <li><a href="dashboard.php">Dashboard</a></li>
    <li><a class="active" href="payments.php">Payments</a></li>
    <li><a href="logout.php">Logout</a></li>
  </ul>
</div>

<div class="main-content">
  <h2>Pending Payments</h2>

  <?php if ($msg): ?>
    <div class="success-msg"><?= $msg ?></div>
  <?php endif; ?>

  <?php if (mysqli_num_rows($bills) > 0): ?>
    <?php while ($bill = mysqli_fetch_assoc($bills)): ?>
      <div class="card">
        <h3><?= $bill['model'] ?> (<?= $bill['number_plate'] ?>)</h3>
        <p><strong>Total Amount:</strong> $<?= number_format($bill['total_amount'], 2) ?></p>

        <form method="POST">
          <input type="hidden" name="billing_id" value="<?= $bill['billing_id'] ?>">
          <input type="number" name="amount_paid" step="0.01" min="1" max="<?= $bill['total_amount'] ?>" required placeholder="Amount to Pay">
          
          <select name="method" onchange="toggleFields(this)" required>
            <option value="">-- Select Payment Method --</option>
            <option value="Cash">Cash</option>
            <option value="UPI">UPI</option>
            <option value="Debit Card">Debit Card</option>
            <option value="Credit Card">Credit Card</option>
          </select>

          <div class="upi-field">
            <input type="text" name="upi_id" placeholder="Enter UPI ID (e.g. user@upi)">
          </div>

          <div class="card-fields">
            <input type="text" name="card_holder" placeholder="Cardholder Name">
            <input type="text" name="card_number" placeholder="Card Number (e.g. 1234123412341234)" maxlength="19">
          </div>

          <button type="submit" name="pay_bill" class="payment-btn">Pay Now</button>
        </form>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p>No pending bills.</p>
  <?php endif; ?>

  <h2>Payment History</h2>
  <?php if (mysqli_num_rows($history) > 0): ?>
    <table>
      <tr>
        <th>Payment ID</th>
        <th>Amount</th>
        <th>Method</th>
        <th>Date</th>
        <th>Card (if used)</th>
        <th>Bill Total</th>
      </tr>
      <?php while ($p = mysqli_fetch_assoc($history)): ?>
        <tr>
          <td><?= $p['payment_id'] ?></td>
          <td>$<?= number_format($p['amount'], 2) ?></td>
          <td><?= $p['method'] ?></td>
          <td><?= date("d M Y", strtotime($p['paid_at'])) ?></td>
          <td><?= $p['masked_card_number'] ? $p['masked_card_number'] . " (" . $p['cardholder_name'] . ")" : "N/A" ?></td>
          <td>$<?= number_format($p['total_amount'], 2) ?></td>
        </tr>
      <?php endwhile; ?>
    </table>
  <?php else: ?>
    <p>No past payments found.</p>
  <?php endif; ?>
</div>

<script>
function toggleFields(select) {
  const form = select.closest('form');
  const cardFields = form.querySelector('.card-fields');
  const upiField = form.querySelector('.upi-field');

  cardFields.classList.remove('active');
  upiField.classList.remove('active');

  if (select.value === 'UPI') {
    upiField.classList.add('active');
  } else if (select.value === 'Credit Card' || select.value === 'Debit Card') {
    cardFields.classList.add('active');
  }
}
</script>

</body>


</html>
