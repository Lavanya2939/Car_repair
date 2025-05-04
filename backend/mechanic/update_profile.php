<?php
include("db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mechanicId = $_POST['mechanic_id'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $phone = $_POST['phone'];
    $specialty = $_POST['specialty'];
    $newPassword = $_POST['new_password'];

    // Handle Profile Image Update
    if ($_FILES['profile_image']['name']) {
        $profileImage = $_FILES['profile_image']['name'];
        $targetDir = "../uploads/mechanics/";
        $targetFile = $targetDir . basename($profileImage);
        move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetFile);
        $updateImageQuery = ", profile_image = '$profileImage'";
    }

    // Update password if provided
    if ($newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updatePasswordQuery = ", password = '$hashedPassword'";
    }

    // Update mechanic's data
    $updateQuery = "UPDATE mechanics 
                    SET first_name = '$firstName', 
                        last_name = '$lastName', 
                        phone = '$phone', 
                        specialty = '$specialty' 
                        $updateImageQuery
                        $updatePasswordQuery 
                    WHERE mechanic_id = '$mechanicId'";

    mysqli_query($conn, $updateQuery);

    // Redirect back to dashboard after the update
    header("Location: dashboard.php");
    exit();
}
?>
