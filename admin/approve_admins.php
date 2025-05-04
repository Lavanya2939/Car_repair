<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include("../backend/admin/session.php");
include("../backend/db.php");

// Include PHPMailer
require '../backend/PHPMailer/src/Exception.php';
require '../backend/PHPMailer/src/PHPMailer.php';
require '../backend/PHPMailer/src/SMTP.php';

// Approve Admin Request
if (isset($_GET['approve'])) {
    $id = $_GET['approve'];
    $req = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM admin_requests WHERE request_id = '$id'"));

    if ($req) {
        $name = $req['full_name'];
        $email = $req['email'];
        $password = $req['password']; // already hashed

        // Insert into admins table
        mysqli_query($conn, "INSERT INTO admins (full_name, email, password, status) VALUES ('$name', '$email', '$password', 'approved')");
        // Delete request from admin_requests
        mysqli_query($conn, "DELETE FROM admin_requests WHERE request_id = '$id'");

        // Send email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'carrepair4356@gmail.com'; // your email
            $mail->Password = 'mfvrbmfteiavlwad';         // your app password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('carrepair4356@gmail.com', 'Car Repair System');
            $mail->addAddress($email, $name);
            $mail->isHTML(true);
            $mail->Subject = 'Admin Access Approved';
            $mail->Body = "
              <h3>Hi $name,</h3>
              <p>Your admin account for <strong>Car Repair System</strong> has been approved.</p>
              <p>You can now log in here: <a href='http://localhost/CarRepairSystem/admin/admin-login.php'>Admin Login</a></p>
              <br><p>Best regards,<br>Car Repair Team</p>
            ";

            $mail->send();
            echo "<script>alert('Admin approved and email sent!'); window.location.href='primary-options.php';</script>";
        } catch (Exception $e) {
            echo "<script>alert('Admin approved, but email failed: " . $mail->ErrorInfo . "'); window.location.href='approve_admins.php';</script>";
        }
    }
}

// Reject Admin Request
if (isset($_GET['reject'])) {
    $id = $_GET['reject'];
    mysqli_query($conn, "DELETE FROM admin_requests WHERE request_id = '$id'");
    echo "<script>alert('Admin request rejected.'); window.location.href='approve_admins.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Approve Admins</title>
  <link rel="stylesheet" href="../css/admin.css">
  <style>
    body {
      background: #f7f7f7;
      font-family: 'Segoe UI', sans-serif;
    }
    .container {
      width: 90%;
      max-width: 900px;
      margin: 40px auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      margin-bottom: 30px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      padding: 14px;
      border-bottom: 1px solid #ddd;
      text-align: left;
    }
    th {
      background-color: #111;
      color: white;
    }
    .btn {
      padding: 6px 14px;
      border: none;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
      text-decoration: none;
    }
    .approve {
      background-color: #2ecc71;
      color: white;
    }
    .reject {
      background-color: #e74c3c;
      color: white;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Pending Admin Requests</h2>

    <?php
    $requests = mysqli_query($conn, "SELECT * FROM admin_requests");

    if (mysqli_num_rows($requests) > 0) {
        echo "<table>
                <tr>
                  <th>#</th>
                  <th>Full Name</th>
                  <th>Email</th>
                  <th>Action</th>
                </tr>";
        $i = 1;
        while ($row = mysqli_fetch_assoc($requests)) {
            echo "<tr>
                    <td>$i</td>
                    <td>{$row['full_name']}</td>
                    <td>{$row['email']}</td>
                    <td>
                      <a href='approve_admins.php?approve={$row['request_id']}' class='btn approve'>Approve</a>
                      <a href='approve_admins.php?reject={$row['request_id']}' class='btn reject' onclick='return confirm(\"Reject this request?\")'>Reject</a>
                    </td>
                  </tr>";
            $i++;
        }
        echo "</table>";
    } else {
        echo "<div style='text-align:center; padding: 20px;'>
        <p>No pending requests.</p>
        <a href='primary-options.php' style='
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #111;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        '>⬅ Go Back</a>
      </div>";

    }
    ?>
  </div>
</body>
<footer style="text-align: center; padding: 15px 10px; margin-top: 40px; font-size: 14px; color: #777;">
  &copy; <?php echo date("Y"); ?> Car Repair & Service Management System. All rights reserved.
</footer>

</html>
