<?php
include("db.php");
session_start();

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM service_ratings WHERE rating_id = '$id'");
echo "<script>alert('Rating deleted.'); window.location.href='../customer/rate_service.php';</script>";
?>
