<?php
session_start();
include("../db.php");

if (isset($_POST['update_status'])) {
  $serviceId = $_POST['service_id'];

  // Update service status to completed
  $query = "UPDATE services SET status = 'completed' WHERE service_id = $serviceId";
  if (mysqli_query($conn, $query)) {
    header("Location: ../../mechanic/assigned_services.php?success=1");
    exit();
  } else {
    echo "Error updating status.";
  }
} else {
  echo "Invalid access.";
}
