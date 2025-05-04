<?php
include("db.php");
session_start();

$customerId = $_SESSION['customer_id'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$dob = $_POST['dob'];
$phone = $_POST['phone'];
$license = $_POST['license_number'];
$address = $_POST['address'];
$newPassword = $_POST['new_password'] ?? "";

$imgClause = "";
if (isset($_FILES['profile_image']) && $_FILES['profile_image']['size'] > 0) {
    $imgName = time() . "_" . basename($_FILES['profile_image']['name']);
    $targetPath = "../uploads/" . $imgName;

    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetPath)) {
        $imgClause = ", profile_image='$imgName'";
    }
}

$passClause = "";
if (!empty($newPassword)) {
    $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
    $passClause = ", password='$hashed'";
}

$update = "UPDATE customers SET 
            first_name='$first_name', 
            last_name='$last_name', 
            dob='$dob', 
            phone='$phone', 
            license_number='$license', 
            address='$address'
            $imgClause
            $passClause
           WHERE customer_id = '$customerId'";

if (mysqli_query($conn, $update)) {
    echo "<script>alert('Profile updated successfully!'); window.location.href='../customer/profile_settings.php';</script>";
} else {
    echo "<script>alert('Update failed: " . mysqli_error($conn) . "'); window.history.back();</script>";
}
?>
