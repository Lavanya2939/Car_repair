<?php
session_start();
include("../backend/db.php");

// Get the login log ID stored in session
if (isset($_SESSION['admin_log_id'])) {
    $logId = $_SESSION['admin_log_id'];
    // Update the logout time
    mysqli_query($conn, "UPDATE admin_login_logout_details SET logout_time = NOW() WHERE log_id = '$logId'");
    unset($_SESSION['admin_log_id']);
}

// Destroy session
session_unset();
session_destroy();

// Redirect to login
header("Location: admin-login.php");
exit();
?>
