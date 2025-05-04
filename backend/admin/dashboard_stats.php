<?php
include("../db.php");

$stats = [];

$stats['tickets'] = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM support_tickets WHERE status = 'open'"))['total'];
$stats['mechanics'] = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM mechanics"))['total'];
$stats['inventory'] = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM inventory"))['total'];
$stats['appointments'] = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM appointments"))['total'];
$stats['billing'] = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM billing"))['total'];
$stats['payments'] = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM payments"))['total'];

echo json_encode($stats);
?>
