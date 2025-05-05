<?php
include("../backend/session.php");
include("../backend/db.php");

$customerId = $_SESSION['customer_id'];
$query = mysqli_query($conn, "SELECT * FROM customers WHERE customer_id = '$customerId'");

if (!$query || mysqli_num_rows($query) == 0) {
    die("<script>alert('User not found!'); window.location.href='logout.php';</script>");
}

$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Profile Settings</title>
  <link rel="stylesheet" href="../css/dashboard.css">
  <style>
    .profile-container {
      max-width: 800px;
      background: #fff;
      padding: 30px;
      margin: 40px auto;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .profile-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .profile-image {
      display: flex;
      align-items: center;
    }
    .profile-img {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #f1c40f;
    }
    .circle-initial {
      width: 100px;
      height: 100px;
      background-color: #f1c40f;
      border-radius: 50%;
      font-size: 40px;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
    }
    .edit-btn {
      background-color: #007bff;
      color: #fff;
      border: none;
      padding: 8px 16px;
      border-radius: 6px;
      cursor: pointer;
    }
    .edit-btn:hover {
      background-color: #0056b3;
    }
    .profile-form input,
    .profile-form textarea {
      width: 100%;
      padding: 10px;
      margin-top: 6px;
      margin-bottom: 16px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 15px;
    }
    .profile-form label {
      font-weight: bold;
      display: block;
      margin-top: 10px;
    }
    .submit-btn {
      background-color: #28a745;
      color: #fff;
      padding: 10px 18px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }
    .submit-btn:hover {
      background-color: #218838;
    }
    .disabled-field {
      background: #f5f5f5;
      color: #555;
    }
  </style>
</head>
<body>

<div class="sidebar">
  <div class="logo" style="text-align: center;">
    <?php if (!empty($data['profile_image']) && file_exists("../uploads/" . $data['profile_image'])): ?>
      <img id="preview" src="../uploads/<?= $data['profile_image'] ?>" class="profile-img">
    <?php else: ?>
      <div class="circle-initial"><?= strtoupper(substr($data['first_name'], 0, 1)) ?></div>
    <?php endif; ?>
    <h3><?= htmlspecialchars($data['first_name'] . ' ' . $data['last_name']) ?></h3>
  </div>
  <ul>
    <li><a href="dashboard.php">Dashboard</a></li>
    <li><a class="active" href="profile_settings.php">Profile</a></li>
    <li><a href="logout.php">Logout</a></li>
  </ul>
</div>

<div class="main-content">
  <div class="profile-container">
    <div class="profile-header">
      <h2>My Profile</h2>
      <button class="edit-btn" onclick="toggleEdit()">Edit Profile</button>
    </div>

    <form class="profile-form" method="POST" action="../backend/update_profile.php" enctype="multipart/form-data" id="profileForm">
      <label>Profile Image:</label>
      <input type="file" name="profile_image" accept="image/*" onchange="previewImage(event)" disabled>

      <label>First Name:</label>
      <input type="text" name="first_name" value="<?= htmlspecialchars($data['first_name']) ?>" disabled>

      <label>Last Name:</label>
      <input type="text" name="last_name" value="<?= htmlspecialchars($data['last_name']) ?>" disabled>

      <label>Email:</label>
      <input type="email" value="<?= htmlspecialchars($data['email']) ?>" disabled class="disabled-field">

      <label>Date of Birth:</label>
      <input type="date" name="dob" value="<?= $data['dob'] ?>" disabled>

      <label>Phone:</label>
      <input type="text" name="phone" value="<?= htmlspecialchars($data['phone']) ?>" disabled>

      <label>License Number:</label>
      <input type="text" name="license_number" value="<?= htmlspecialchars($data['license_number']) ?>" disabled>

      <label>Address:</label>
      <textarea name="address" rows="3" disabled><?= htmlspecialchars($data['address']) ?></textarea>

      <label>Change Password:</label>
      <input type="password" name="new_password" placeholder="Leave blank to keep old password" disabled>

      <button type="submit" class="submit-btn" disabled>Update</button>
    </form>
  </div>
</div>

<script>
function toggleEdit() {
  const form = document.getElementById("profileForm");
  const inputs = form.querySelectorAll("input, textarea, button");
  for (let el of inputs) {
    if (el.name !== "email") {
      el.disabled = !el.disabled;
    }
  }
}
function previewImage(event) {
  const reader = new FileReader();
  reader.onload = function () {
    const output = document.getElementById('preview');
    output.src = reader.result;
    output.className = "profile-img";
  };
  reader.readAsDataURL(event.target.files[0]);
}
</script>

</body>


</html>
