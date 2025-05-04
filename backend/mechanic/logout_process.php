<?php
session_start();

// Correct relative path to db_connection.php
include '../../backend/db.php';

// Validate session existence
if (isset($_SESSION['mechanic_id']) && isset($_SESSION['login_record_id'])) {

    $logout_time = date('Y-m-d H:i:s');
    $login_record_id = $_SESSION['login_record_id'];

    // Update logout time in database
    $stmt = $conn->prepare("UPDATE mechanic_login_logout_details SET logout_time = ? WHERE id = ?");
    $stmt->bind_param("si", $logout_time, $login_record_id);
    $stmt->execute();
    $stmt->close();
}

// Destroy session securely
$_SESSION = array();
session_destroy();

// Close database connection
$conn->close();

// Redirect back to frontend login page
header('Location: ../../mechanic/login.php');
exit();
?>
