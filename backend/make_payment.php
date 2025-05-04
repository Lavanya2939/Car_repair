<?php
include("db.php");
session_start();

$customerId = $_SESSION['customer_id'];
$billingId = $_POST['billing_id'];
$amountPaid = $_POST['amount_paid'];

// Fetch total amount due for this billing ID
$query = "SELECT total_amount FROM billing WHERE billing_id = '$billingId' AND customer_id = '$customerId'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if ($row && $amountPaid <= $row['total_amount']) {
    // Insert payment record
    $insert_payment = "INSERT INTO payments (billing_id, amount_paid) VALUES ('$billingId', '$amountPaid')";
    mysqli_query($conn, $insert_payment);

    // Update billing status if fully paid
    $updated_status = ($amountPaid == $row['total_amount']) ? 'paid' : 'partial';
    mysqli_query($conn, "UPDATE billing SET status = '$updated_status' WHERE billing_id = '$billingId'");

    echo "<script>alert('Payment successfully processed!'); window.location.href='../customer/payments.php';</script>";
} else {
    echo "<script>alert('Amount paid is more than total amount due or invalid.'); window.history.back();</script>";
}
?>
