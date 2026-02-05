<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$rental_id = intval($_GET['id']);

$conn->query("
    UPDATE rentals 
    SET cancellation_requested = 1, cancellation_status = 'Pending' 
    WHERE rental_id = $rental_id 
    AND email = (SELECT email FROM users WHERE user_id = $user_id)
");

header("Location: dashboard.php");
exit;
