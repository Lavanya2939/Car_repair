<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header("Location: admin-login.php");
  exit();
}
include("../backend/db.php");

// Add New Inventory Item
if (isset($_POST['add_item'])) {
  $name = mysqli_real_escape_string($conn, $_POST['item_name']);
  $type = mysqli_real_escape_string($conn, $_POST['item_type']);
  $quantity = intval($_POST['quantity']);
  $price = floatval($_POST['unit_price']);
  $status = mysqli_real_escape_string($conn, $_POST['status']);

  $imageName = "";
  if (!empty($_FILES['image']['name'])) {
    $imageName = time() . "_" . basename($_FILES['image']['name']);
    $target = "../uploads/inventory/" . $imageName;
    move_uploaded_file($_FILES['image']['tmp_name'], $target);
  }

  $insert = "INSERT INTO inventory (item_name, item_type, image, quantity, unit_price, status)
             VALUES ('$name', '$type', '$imageName', '$quantity', '$price', '$status')";
  $msg = mysqli_query($conn, $insert) ? "Item added successfully!" : "Failed to add item.";
}

// Delete Item
if (isset($_GET['delete'])) {
  $deleteId = intval($_GET['delete']);
  mysqli_query($conn, "DELETE FROM inventory WHERE item_id = $deleteId");
  header("Location: inventory.php");
  exit();
}

// Update Inventory Item
if (isset($_POST['update_item'])) {
  $id = intval($_POST['item_id']);
  $name = mysqli_real_escape_string($conn, $_POST['edit_name']);
  $type = mysqli_real_escape_string($conn, $_POST['edit_type']);
  $quantity = intval($_POST['edit_quantity']);
  $price = floatval($_POST['edit_price']);
  $status = mysqli_real_escape_string($conn, $_POST['edit_status']);

  $update = "UPDATE inventory SET 
              item_name = '$name', 
              item_type = '$type',
              quantity = $quantity,
              unit_price = $price,
              status = '$status'
            WHERE item_id = $id";
  $msg = mysqli_query($conn, $update) ? "Item updated successfully." : "Update failed.";
}

// Filter setup
$typeFilter = $_GET['type'] ?? '';
$statusFilter = $_GET['status'] ?? '';

$whereClause = "WHERE 1";
if ($typeFilter) {
  $whereClause .= " AND item_type = '" . mysqli_real_escape_string($conn, $typeFilter) . "'";
}
if ($statusFilter) {
  $whereClause .= " AND status = '" . mysqli_real_escape_string($conn, $statusFilter) . "'";
}
$items = mysqli_query($conn, "SELECT * FROM inventory $whereClause ORDER BY created_at DESC");

// Low stock check
$lowStockItems = mysqli_query($conn, "SELECT COUNT(*) as low_count FROM inventory WHERE quantity <= 5");
$lowStockCount = mysqli_fetch_assoc($lowStockItems)['low_count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Inventory - Admin</title>
  <link rel="stylesheet" href="../css/admin-dashboard.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    .form-box, .table-box {
      max-width: 1100px; margin: 20px auto; background: #fff;
      padding: 25px; border-radius: 12px; box-shadow: 0 0 10px rgba(0,0,0,0.08);
    }
    input, select {
      width: 100%; padding: 10px; margin-bottom: 15px;
      border: 1px solid #ccc; border-radius: 5px;
    }
    button {
      padding: 10px 18px; background: #007bff; color: white;
      border: none; border-radius: 6px; cursor: pointer;
    }
    .success-msg {
      background: #e0f9e0; color: #2e7d32;
      padding: 10px; border-left: 5px solid #4caf50;
      text-align: center; margin-bottom: 15px;
    }
    .low-stock-banner {
      background: #fff3cd; color: #856404;
      border-left: 6px solid #ffc107;
      padding: 12px; margin-bottom: 20px;
      font-weight: bold; text-align: center;
    }
    table {
      width: 100%; border-collapse: collapse; margin-top: 20px;
    }
    table th, table td {
      padding: 10px; border: 1px solid #ccc; text-align: center;
    }
    .low-stock { background-color: #fff9e6 !important; }
    .status-badge {
      padding: 5px 10px; border-radius: 6px; font-weight: bold;
    }
    .available { background: #e0fbe0; color: green; }
    .unavailable { background: #fdecea; color: red; }
    img.item-thumb {
      width: 60px; height: 60px; object-fit: cover; border-radius: 6px;
    }
    .filters {
      display: flex; justify-content: space-between; margin-bottom: 20px; max-width: 1100px; margin: 0 auto;
    }
    .filters form {
      display: flex; gap: 15px;
    }
  </style>
</head>
<body>

<div class="admin-container">
  <!-- Sidebar -->
  <div class="sidebar">
    <h2>Admin</h2>
    <a href="dashboard.php"><i class="fas fa-gauge"></i> Dashboard</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="topbar">
      <h1>Inventory</h1>
      <a href="dashboard.php"><i class="fas fa-arrow-left"></i> Go Back</a>
    </div>

    <div class="form-box">
      <h2>Add New Item</h2>
      <?php if (isset($msg)) echo "<div class='success-msg'>$msg</div>"; ?>
      <form method="POST" enctype="multipart/form-data">
        <input type="text" name="item_name" placeholder="Item Name" required>
        <input type="text" name="item_type" placeholder="Item Type" required>
        <input type="file" name="image" accept="image/*">
        <input type="number" name="quantity" placeholder="Quantity" required>
        <input type="number" step="0.01" name="unit_price" placeholder="Unit Price ($)" required>
        <select name="status">
          <option value="available">Available</option>
          <option value="unavailable">Unavailable</option>
        </select>
        <button type="submit" name="add_item">Add Item</button>
      </form>
    </div>

    <div class="table-box">
      <h2>Inventory List</h2>

      <?php if ($lowStockCount > 0): ?>
        <div class="low-stock-banner">
          ⚠️ <?= $lowStockCount ?> item(s) are low in stock (≤ 5)!
        </div>
      <?php endif; ?>

      <div class="filters">
        <form method="GET">
          <select name="type">
            <option value="">All Types</option>
            <?php
            $types = mysqli_query($conn, "SELECT DISTINCT item_type FROM inventory");
            while ($t = mysqli_fetch_assoc($types)) {
              $selected = $t['item_type'] == $typeFilter ? 'selected' : '';
              echo "<option value='{$t['item_type']}' $selected>{$t['item_type']}</option>";
            }
            ?>
          </select>
          <select name="status">
            <option value="">All Status</option>
            <option value="available" <?= $statusFilter == 'available' ? 'selected' : '' ?>>Available</option>
            <option value="unavailable" <?= $statusFilter == 'unavailable' ? 'selected' : '' ?>>Unavailable</option>
          </select>
          <button type="submit">Filter</button>
        </form>
      </div>

      <table>
        <thead>
          <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Type</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php while ($item = mysqli_fetch_assoc($items)):
          $isLow = $item['quantity'] <= 5 ? 'low-stock' : '';
          $imgPath = $item['image'] ? "../uploads/inventory/" . $item['image'] : "../assets/images/no-image.png";
        ?>
          <form method="POST">
            <input type="hidden" name="item_id" value="<?= $item['item_id'] ?>">
            <tr class="<?= $isLow ?>">
              <td><img src="<?= $imgPath ?>" class="item-thumb"></td>
              <td><input name="edit_name" value="<?= htmlspecialchars($item['item_name']) ?>" required></td>
              <td><input name="edit_type" value="<?= htmlspecialchars($item['item_type']) ?>"></td>
              <td><input type="number" name="edit_quantity" value="<?= $item['quantity'] ?>" required></td>
              <td>$ <input type="number" step="0.01" name="edit_price" value="<?= $item['unit_price'] ?>" required></td>
              <td>
                <select name="edit_status">
                  <option value="available" <?= $item['status'] == 'available' ? 'selected' : '' ?>>Available</option>
                  <option value="unavailable" <?= $item['status'] == 'unavailable' ? 'selected' : '' ?>>Unavailable</option>
                </select>
              </td>
              <td>
                <button type="submit" name="update_item"><i class="fas fa-save"></i></button>
                <a href="inventory.php?delete=<?= $item['item_id'] ?>" onclick="return confirm('Delete this item?')"><i class="fas fa-trash"></i></a>
              </td>
            </tr>
          </form>
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
