<?php
include("db.php");
include("session.php");

// PHPMailer files
require_once(__DIR__ . '/PHPMailer/src/PHPMailer.php');
require_once(__DIR__ . '/PHPMailer/src/SMTP.php');
require_once(__DIR__ . '/PHPMailer/src/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customerId = $_SESSION['customer_id'];
    $customerEmail = $_SESSION['customer_email'];
    $customerName = $_SESSION['customer_name'];

    $vehicleId = mysqli_real_escape_string($conn, $_POST['vehicle_id']);
    $mechanicId = mysqli_real_escape_string($conn, $_POST['mechanic_id']);
    $serviceDate = mysqli_real_escape_string($conn, $_POST['service_date']);
    $serviceTime = mysqli_real_escape_string($conn, $_POST['service_time']);
    $issue = mysqli_real_escape_string($conn, $_POST['issue']);

    // Fetch mechanic name for future email
    $mechanicResult = mysqli_query($conn, "SELECT first_name, last_name FROM mechanics WHERE mechanic_id = '$mechanicId'");
    $mechanicData = mysqli_fetch_assoc($mechanicResult);
    $mechanicName = $mechanicData ? $mechanicData['first_name'] . ' ' . $mechanicData['last_name'] : "Our Mechanic";

    // Insert appointment
    $query = "INSERT INTO appointments (customer_id, vehicle_id, mechanic_id, service_date, service_time, issue_description, status)
              VALUES ('$customerId', '$vehicleId', '$mechanicId', '$serviceDate', '$serviceTime', '$issue', 'pending')";

    if (mysqli_query($conn, $query)) {
        // Optionally notify customer
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'carrepair4356@gmail.com'; // Your system email
            $mail->Password = 'mfvrbmfteiavlwad';        // App-specific password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('carrepair4356@gmail.com', 'Car Repair System');
            $mail->addAddress($customerEmail, $customerName);

            $mail->isHTML(true);
            $mail->Subject = "Appointment Request Received";
            $mail->Body = "
                <h3>Hello $customerName,</h3>
                <p>Your appointment request has been received and is currently <strong>pending approval</strong>.</p>
                <p><strong>Details:</strong></p>
                <ul>
                    <li>Mechanic: $mechanicName</li>
                    <li>Date: $serviceDate</li>
                    <li>Time: $serviceTime</li>
                </ul>
                <p>Once approved, you'll receive another confirmation email.</p>
                <br>
                <p style='color:gray;'>– Car Repair Team</p>
            ";
            $mail->send();
        } catch (Exception $e) {
            error_log("Mail Error: " . $mail->ErrorInfo);
        }

        header("Location: ../customer/appointments.php?success=1");
        exit;
    } else {
        echo "<script>alert('Failed to book appointment: " . mysqli_error($conn) . "'); window.history.back();</script>";
    }
}
?>
