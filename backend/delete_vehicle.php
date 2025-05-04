<?php
include("session.php");
include("db.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['vehicle_id'])) {
    $vehicleId = mysqli_real_escape_string($conn, $_POST['vehicle_id']);

    // Optional: remove image file (if needed)
    $imgRes = mysqli_query($conn, "SELECT image_path FROM vehicles WHERE vehicle_id = '$vehicleId'");
    if ($row = mysqli_fetch_assoc($imgRes)) {
        $imagePath = $row['image_path'];
        if (file_exists("../" . $imagePath)) {
            unlink("../" . $imagePath);
        }
    }

    mysqli_query($conn, "DELETE FROM vehicles WHERE vehicle_id = '$vehicleId'");
}

header("Location: ../customer/vehicles.php");
exit();
?>
