<?php
session_start();
include("../backend/db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM customers WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $customer = mysqli_fetch_assoc($result);

        if (password_verify($password, $customer['password'])) {
            $_SESSION['customer_id'] = $customer['customer_id'];
            $_SESSION['customer_name'] = $customer['first_name'] . " " . $customer['last_name'];
            $_SESSION['email'] = $customer['email'];

            $login_time = date('Y-m-d H:i:s');
            $customer_id = $_SESSION['customer_id'];
            $login_query = "INSERT INTO customer_login_logout_details (customer_id, login_time) VALUES ('$customer_id', '$login_time')";
            mysqli_query($conn, $login_query);

            header('Location: ../customer/dashboard.php');
            exit();
        } else {
            header('Location: ../customer-login.php?error=wrongpass');
            exit();
        }
    } else {
        header('Location: ../customer-login.php?error=notfound');
        exit();
    }
}
?>
