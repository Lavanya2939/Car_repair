<?php
$host = "localhost";
$user = "root";
$pass = ""; // or your MySQL password
$dbname = "car_repair_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
