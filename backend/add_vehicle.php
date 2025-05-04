<?php
include("db.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_SESSION['customer_id'])) {
        header("Location: ../customer-login.php");
        exit;
    }

    $customerId = $_SESSION['customer_id'];
    $make = trim($_POST['make']);
    $model = trim($_POST['model']);
    $year = intval($_POST['year']);
    $body = trim($_POST['body']);
    $plate = strtoupper(trim($_POST['number_plate']));

    // Validate inputs
    if (empty($make) || empty($model) || empty($year) || empty($body) || empty($plate)) {
        echo "<script>alert('All fields are required.'); window.history.back();</script>";
        exit;
    }

    // Handle image upload
    if (!isset($_FILES['vehicle_image']) || $_FILES['vehicle_image']['error'] !== UPLOAD_ERR_OK) {
        echo "<script>alert('Image upload failed. Please select a valid image.'); window.history.back();</script>";
        exit;
    }

    $imgName = basename($_FILES['vehicle_image']['name']);
    $imgTmp = $_FILES['vehicle_image']['tmp_name'];
    $imgExt = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));
    $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($imgExt, $allowedExts)) {
        echo "<script>alert('Invalid image format. Allowed: jpg, jpeg, png, gif'); window.history.back();</script>";
        exit;
    }

    $uploadDir = "../uploads/vehicles/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $imgPath = $uploadDir . time() . "_" . preg_replace("/[^a-zA-Z0-9_\.-]/", "_", $imgName);

    if (!move_uploaded_file($imgTmp, $imgPath)) {
        echo "<script>alert('Failed to save uploaded image.'); window.history.back();</script>";
        exit;
    }

    // Prevent duplicate plate
    $checkQuery = "SELECT * FROM vehicles WHERE number_plate = '$plate'";
    $checkResult = mysqli_query($conn, $checkQuery);
    if (mysqli_num_rows($checkResult) > 0) {
        echo "<script>alert('This number plate is already registered. Please use a different one.'); window.history.back();</script>";
        exit;
    }

    // Insert vehicle into database
    $insertQuery = "INSERT INTO vehicles (customer_id, make, model, year, body_style, number_plate, image_path)
                    VALUES ('$customerId', '$make', '$model', '$year', '$body', '$plate', '$imgPath')";
    
    if (mysqli_query($conn, $insertQuery)) {
        header("Location: ../customer/vehicles.php?added=1");
        exit;
    } else {
        echo "<script>alert('Database error: " . mysqli_error($conn) . "'); window.history.back();</script>";
    }
}
?>
