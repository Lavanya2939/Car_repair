<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
  header("Location: ../../admin/admin-login.php"); // adjust path as needed
  exit();
}
?>
