<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}
include("../backend/db.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Mechanics</title>
  <link rel="stylesheet" href="../css/admin-dashboard.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    input, select {
      padding: 10px;
      width: 100%;
      margin: 5px 0;
    }
    button {
      padding: 10px;
      font-weight: bold;
      background: #007bff;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }
    table th, table td {
      padding: 10px;
      text-align: center;
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
      <h1>Manage Mechanics</h1>
      <a href="dashboard.php" class="profile"><i class="fas fa-arrow-left"></i> Go Back</a>
    </div>

    <div class="card-grid">

      <!-- ADD NEW MECHANIC FORM -->
      <form action="../backend/admin/add_mechanic.php" method="POST" class="card" style="grid-column: span 2;">
        <h3>Add New Mechanic</h3>
        <input type="text" name="first_name" placeholder="First Name" required>
        <input type="text" name="last_name" placeholder="Last Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="phone" placeholder="Phone" required>
        <input type="text" name="specialty" placeholder="Specialty" required>
        <input type="number" step="0.1" min="0" max="5" name="rating" placeholder="Initial Rating (0–5)" required>
        <button type="submit">Add Mechanic</button>
      </form>

      <!-- MECHANICS TABLE -->
      <div class="card" style="grid-column: span 2; overflow-x: auto;">
        <h3>Existing Mechanics</h3>
        <div style="overflow-x: auto;">
          <table style="width: 100%; border-collapse: collapse;">
            <thead>
              <tr style="background: #f8f8f8;">
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Specialty</th>
                <th>Rating</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $result = mysqli_query($conn, "SELECT * FROM mechanics");
              while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                  <td>{$row['mechanic_id']}</td>
                  <td>{$row['first_name']} {$row['last_name']}</td>
                  <td>{$row['email']}</td>
                  <td>{$row['phone']}</td>
                  <td>{$row['specialty']}</td>
                  <td>" . number_format($row['rating'], 1) . "</td>
                  <td>{$row['status']}</td>
                  <td><a href='../backend/admin/delete_mechanic.php?id={$row['mechanic_id']}' onclick=\"return confirm('Delete this mechanic?')\">Delete</a></td>
                </tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</div>

</body>
<footer style="text-align: center; padding: 15px 10px; margin-top: 40px; font-size: 14px; color: #777;">
  &copy; <?php echo date("Y"); ?> Car Repair & Service Management System. All rights reserved.
</footer>

</html>
