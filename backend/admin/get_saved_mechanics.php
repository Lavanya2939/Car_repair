<?php
include("../db.php");

if (isset($_GET['customer_id'])) {
  $cid = intval($_GET['customer_id']);

  $query = "SELECT mechanic_id, mechanic_name FROM saved_mechanics WHERE customer_id = $cid";
  $result = mysqli_query($conn, $query);

  $mechanics = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $mechanics[] = $row;
  }

  header('Content-Type: application/json');
  echo json_encode($mechanics);
}
?>
