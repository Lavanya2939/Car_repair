<?php
session_start();

if (!isset($_POST['login'])) {
  header("Location: ../../mechanic/login.php");
  exit();
}

include '../../backend/db.php';

$username = $_POST['username'];
$password = $_POST['password'];

// Fetch mechanic details by username
$stmt = $conn->prepare("SELECT mechanic_id, password FROM mechanics WHERE email = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
  $row = $result->fetch_assoc();

  if (password_verify($password, $row['password'])) {
    $_SESSION['mechanic_id'] = $row['mechanic_id'];

    // Insert login details
    $stmt = $conn->prepare("INSERT INTO mechanic_login_logout_details (mechanic_id, login_time) VALUES (?, NOW())");
    $stmt->bind_param("i", $_SESSION['mechanic_id']);
    $stmt->execute();

    $_SESSION['login_record_id'] = $conn->insert_id;

    header("Location: ../../mechanic/dashboard.php");
    exit();
  } else {
    header("Location: ../../mechanic/login.php?error=Invalid Password");
    exit();
  }
} else {
  header("Location: ../../mechanic/login.php?error=User not found");
  exit();
}

$stmt->close();
$conn->close();
?>
