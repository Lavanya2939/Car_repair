<?php
include("db.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $vehicleId = $_POST['vehicle_id'];
  $make = $_POST['make'];
  $model = $_POST['model'];
  $year = $_POST['year'];
  $body = $_POST['body'];
  $plate = $_POST['number_plate'];

  $update = mysqli_query($conn, "UPDATE vehicles SET make='$make', model='$model', year='$year', body_style='$body', number_plate='$plate' WHERE vehicle_id='$vehicleId'");

  header("Location: ../customer/vehicles.php");
  exit();
}
?>
