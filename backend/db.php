<?php
$host = "34.48.113.161";
$user = "carrepair";
$pass = "x=,F5@%b}G:Ugfs2"; // or your MySQL password
$dbname = "car_repair_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
