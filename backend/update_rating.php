<?php
include("db.php");
session_start();

$ratingId = $_POST['rating_id'];
$newRating = $_POST['rating'];
$feedback = mysqli_real_escape_string($conn, $_POST['feedback']);

if ($newRating >= 1 && $newRating <= 5) {
    mysqli_query($conn, "UPDATE service_ratings SET rating = '$newRating', feedback = '$feedback' WHERE rating_id = '$ratingId'");
    echo "<script>alert('Rating updated!'); window.location.href='../customer/rate_service.php';</script>";
} else {
    echo "<script>alert('Invalid rating.'); window.history.back();</script>";
}
?>
