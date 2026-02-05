<?php
$host = 'localhost';
$user = 'root';
$pass = ''; // empty if using XAMPP
$db   = 're_rent_system'; // your DB name

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
