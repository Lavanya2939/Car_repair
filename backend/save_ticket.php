<?php
include("db.php");
session_start();

$customerId = $_SESSION['customer_id'];
$subject = mysqli_real_escape_string($conn, $_POST['subject']);
$message = mysqli_real_escape_string($conn, $_POST['message']);

if (!empty($subject) && !empty($message)) {
    $query = "INSERT INTO support_tickets (customer_id, subject, message) 
              VALUES ('$customerId', '$subject', '$message')";
    mysqli_query($conn, $query);
    echo "<script>alert('Support ticket submitted successfully!'); window.location.href='../customer/support_tickets.php';</script>";
} else {
    echo "<script>alert('Please fill out all fields.'); window.history.back();</script>";
}
?>
