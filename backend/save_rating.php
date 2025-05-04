<?php
include("db.php");
session_start();

$customerId = $_SESSION['customer_id'];
$serviceId = $_POST['service_id'];
$mechanicId = $_POST['mechanic_id'];
$rating = $_POST['rating'];
$feedback = mysqli_real_escape_string($conn, $_POST['feedback']);

if ($rating >= 1 && $rating <= 5) {
    $insert = "INSERT INTO service_ratings (service_id, customer_id, mechanic_id, rating, feedback) 
               VALUES ('$serviceId', '$customerId', '$mechanicId', '$rating', '$feedback')";
    mysqli_query($conn, $insert);
    echo "<script>alert('Thanks for your rating!'); window.location.href='../customer/rate_service.php';</script>";
} else {
    echo "<script>alert('Please select a valid rating.'); window.history.back();</script>";
}
?>
