<?php
session_start();
include("../backend/db.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];
$query = mysqli_query($conn, "SELECT * FROM admins WHERE admin_id = '$admin_id'");
$admin = mysqli_fetch_assoc($query);

// Success message flag
$msg = isset($_GET['updated']) && $_GET['updated'] === 'true' ? "Profile updated successfully!" : "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Profile</title>
  <link rel="stylesheet" href="../css/admin-dashboard.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    .profile-card {
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.05);
      max-width: 600px;
      margin: auto;
    }
    .profile-img {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #ddd;
    }
    .form-group {
      margin-bottom: 18px;
    }
    .form-group label {
      font-weight: 600;
      display: block;
      margin-bottom: 6px;
    }
    .form-group input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    .success-msg {
      background-color: #e0f7e9;
      color: #007b33;
      padding: 10px 15px;
      margin-bottom: 20px;
      border-left: 5px solid #32a852;
      border-radius: 5px;
      font-weight: 600;
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
      <h1>Admin Profile</h1>
      <a href="dashboard.php" class="profile"><i class="fas fa-arrow-left"></i> Go Back</a>
    </div>

    <div class="profile-card">
      <?php if ($msg): ?>
        <div class="success-msg"><?php echo $msg; ?></div>
      <?php endif; ?>

      <form action="../backend/admin/update_admin.php" method="POST" enctype="multipart/form-data">
        <div style="text-align: center;">
          <img src="../uploads/admin_profiles/<?php echo $admin['profile_img'] ?? 'default.png'; ?>" class="profile-img" id="previewImg"><br><br>
          <input type="file" name="profile_img" accept="image/*" onchange="previewImage(event)">
        </div>

        <div class="form-group">
          <label>Full Name</label>
          <input type="text" name="full_name" value="<?php echo $admin['full_name']; ?>" required>
        </div>

        <div class="form-group">
          <label>Email</label>
          <input type="email" value="<?php echo $admin['email']; ?>" readonly>
        </div>

        <div class="form-group">
          <label>Role</label>
          <input type="text" value="<?php echo $admin['is_primary'] == 1 ? 'Primary Admin' : 'Admin'; ?>" readonly>
        </div>

        <div class="form-group">
          <label>New Password (Leave blank to keep current)</label>
          <input type="password" name="password" placeholder="********">
        </div>

        <button type="submit" name="update_admin">Update Profile</button>
      </form>
    </div>
  </div>
</div>

<script>
function previewImage(event) {
  const reader = new FileReader();
  reader.onload = function(){
    const output = document.getElementById('previewImg');
    output.src = reader.result;
  };
  reader.readAsDataURL(event.target.files[0]);
}
</script>

</body>
<footer style="text-align: center; padding: 15px 10px; margin-top: 40px; font-size: 14px; color: #777;">
  &copy; <?php echo date("Y"); ?> Car Repair & Service Management System. All rights reserved.
</footer>

</html>
