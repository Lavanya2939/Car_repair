<?php
session_start();
include("../db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['request_id']) && isset($_POST['status'])) {
  $request_id = intval($_POST['request_id']);
  $status = $_POST['status'];

  $valid = ['Approved', 'Rejected'];
  if (in_array($status, $valid)) {
    $update = "UPDATE mechanic_requests SET status = '$status' WHERE request_id = $request_id";
    mysqli_query($conn, $update);
  }
}

header("Location: ../../admin/mechanic_requests.php");
exit();
?>
