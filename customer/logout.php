<?php
// Include database connection
include("../backend/db.php");

// Start the session to access session variables
session_start();

// Get the customer_id from the session
$customer_id = $_SESSION['customer_id'];

// Check if the customer is logged in
if (!isset($customer_id)) {
    // Redirect to login page if not logged in
    header("Location: customer-login.php");
    exit();
}

// Get the current time for logout
$logout_time = date('Y-m-d H:i:s');

// Update the logout time for the logged-in customer
$query = "UPDATE customer_login_logout_details 
          SET logout_time = '$logout_time' 
          WHERE customer_id = '$customer_id' 
          AND logout_time IS NULL";

// Execute the query
if (mysqli_query($conn, $query)) {
    // Destroy the session to log the user out
    session_destroy();
    
    // Redirect to the login page after successful logout
    header("Location: ../customer-login.php");
    exit();
} else {
    // If there's an error updating the logout time
    echo "Error updating logout time: " . mysqli_error($conn);
}
?>
