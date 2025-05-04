<?php
include("../backend/session.php");
include("../backend/db.php");
$customerId = $_SESSION['customer_id'];
$customerName = $_SESSION['customer_name'];
$vehicles = mysqli_query($conn, "SELECT * FROM vehicles WHERE customer_id = '$customerId'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Vehicles</title>
  <link rel="stylesheet" href="../css/dashboard.css">
  <style>
    /* Styles from previous version retained + edit modal added */
    .main-content {
      padding: 30px; font-family: 'Segoe UI', sans-serif;
    }
    h2, h3 { margin-bottom: 20px; color: #1e1e2f; }
    form {
      background: #ffffff; padding: 25px; border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.08); margin-bottom: 40px;
      max-width: 720px;
    }
    form input, form select {
      padding: 12px; width: 100%; margin-bottom: 18px;
      border: 1px solid #ccc; border-radius: 8px; font-size: 15px;
    }
    form label {
      font-weight: bold; margin-top: 8px; display: block; color: #333;
    }
    form button {
      background-color: #1e1e2f; color: white; padding: 12px 28px;
      border: none; border-radius: 8px; font-weight: bold;
      font-size: 15px; cursor: pointer; transition: 0.3s ease;
    }
    .vehicle-grid {
      display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
      gap: 25px;
    }
    .vehicle-card {
      background: #fff; border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.08);
      overflow: hidden; position: relative; transition: transform 0.3s ease;
    }
    .vehicle-card:hover { transform: translateY(-5px); }
    .vehicle-card img {
      width: 100%; height: 180px; object-fit: cover;
    }
    .vehicle-card .content { padding: 15px; }
    .vehicle-card h4 { margin: 0 0 10px; color: #1e1e2f; font-size: 18px; }
    .vehicle-card p { margin: 4px 0; font-size: 14px; color: #555; }
    .btn-wrap { display: flex; justify-content: space-between; margin-top: 10px; }
    .delete-btn, .edit-btn {
      background: #444; color: #fff; padding: 6px 10px; border: none;
      border-radius: 6px; font-size: 13px; cursor: pointer;
    }
    .delete-btn:hover { background: crimson; }
    .edit-btn:hover { background: #007BFF; }

    /* Modal */
    .modal {
      display: none; position: fixed; z-index: 999; left: 0; top: 0;
      width: 100%; height: 100%; background-color: rgba(0,0,0,0.6);
    }
    .modal-content {
      background: #fff; padding: 20px; border-radius: 12px;
      max-width: 500px; margin: 60px auto; position: relative;
    }
    .close {
      position: absolute; top: 10px; right: 15px; font-size: 24px;
      cursor: pointer;
    }
  </style>
</head>
<body>

<div class="sidebar">
  <div class="logo"><h2>🚗 Car Repair</h2></div>
  <ul>
    <li><a href="dashboard.php">Dashboard</a></li>
    <li><a class="active" href="vehicles.php">Vehicles</a></li>
    <li><a href="appointments.php">Bookings</a></li>
    <li><a href="payments.php">Payments</a></li>
    <li><a href="support_tickets.php">Support</a></li>
    <li><a href="rate_service.php">Rate Service</a></li>
    <li><a href="profile_settings.php">Profile</a></li>
    <li><a href="logout.php">Logout</a></li>
  </ul>
</div>

<div class="main-content">
  <h2>Welcome, <?= htmlspecialchars($customerName) ?> – Add a New Vehicle</h2>

  <form action="../backend/add_vehicle.php" method="POST" enctype="multipart/form-data">
    <label>Make</label>
    <input type="text" name="make" required>
    <label>Model</label>
    <input type="text" name="model" required>
    <label>Year</label>
    <input type="number" name="year" required>
    <label>Body Style</label>
    <input type="text" name="body" required>
    <label>Number Plate</label>
    <input type="text" name="number_plate" required>
    <label>Upload Vehicle Image</label>
    <input type="file" name="vehicle_image" required>
    <button type="submit">Add Vehicle</button>
  </form>

  <h3>Your Vehicles</h3>
  <div class="vehicle-grid">
    <?php while($row = mysqli_fetch_assoc($vehicles)) { ?>
      <div class="vehicle-card">
        <img src="<?= $row['image_path'] ?>" alt="Vehicle">
        <div class="content">
          <h4><?= $row['make'] ?> <?= $row['model'] ?></h4>
          <p><strong>Year:</strong> <?= $row['year'] ?></p>
          <p><strong>Body:</strong> <?= $row['body_style'] ?></p>
          <p><strong>Plate:</strong> <?= $row['number_plate'] ?></p>
          <div class="btn-wrap">
            <form method="POST" action="../backend/delete_vehicle.php" onsubmit="return confirm('Are you sure?');">
              <input type="hidden" name="vehicle_id" value="<?= $row['vehicle_id'] ?>">
              <button class="delete-btn">Delete</button>
            </form>
            <button class="edit-btn" onclick="openEditModal(<?= htmlspecialchars(json_encode($row)) ?>)">Edit</button>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeEditModal()">&times;</span>
    <h3>Edit Vehicle</h3>
    <form method="POST" action="../backend/update_vehicle.php">
      <input type="hidden" name="vehicle_id" id="edit_vehicle_id">
      <label>Make</label>
      <input type="text" name="make" id="edit_make" required>
      <label>Model</label>
      <input type="text" name="model" id="edit_model" required>
      <label>Year</label>
      <input type="number" name="year" id="edit_year" required>
      <label>Body Style</label>
      <input type="text" name="body" id="edit_body" required>
      <label>Number Plate</label>
      <input type="text" name="number_plate" id="edit_number_plate" required>
      <button type="submit">Update Vehicle</button>
    </form>
  </div>
</div>

<script>
function openEditModal(data) {
  document.getElementById("edit_vehicle_id").value = data.vehicle_id;
  document.getElementById("edit_make").value = data.make;
  document.getElementById("edit_model").value = data.model;
  document.getElementById("edit_year").value = data.year;
  document.getElementById("edit_body").value = data.body_style;
  document.getElementById("edit_number_plate").value = data.number_plate;
  document.getElementById("editModal").style.display = "block";
}

function closeEditModal() {
  document.getElementById("editModal").style.display = "none";
}
</script>

</body>
<footer style="text-align: center; padding: 15px 10px; margin-top: 40px; font-size: 14px; color: #777;">
  &copy; <?php echo date("Y"); ?> Car Repair & Service Management System. All rights reserved.
</footer>

</html>
