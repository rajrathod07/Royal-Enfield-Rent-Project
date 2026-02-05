<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$rental_id = (int) $_GET['id'];
$user_id = $_SESSION['user_id'];

// ✅ Update rental record to request cancellation
$stmt = $conn->prepare("UPDATE rentals SET cancellation_requested = 1, cancellation_status = 'Pending' WHERE rental_id = ? AND user_id = ?");
$stmt->bind_param("ii", $rental_id, $user_id);

if ($stmt->execute()) {
    header("Location: dashboard.php?cancel_requested=1");
    exit;
} else {
    echo "Failed to request cancellation.";
}
?>
