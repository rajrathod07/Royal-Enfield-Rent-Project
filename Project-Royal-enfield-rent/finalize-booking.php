<?php
session_start();
include 'includes/db.php';

// ✅ Check session and request method
if (!isset($_SESSION['booking']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: motorcycles.php");
    exit;
}

$booking = $_SESSION['booking'];
$payment_method = $_POST['payment_method'] ?? 'Unknown';

// ✅ Set booking and payment status
$status = ($payment_method === "Pay on Pickup") ? "Pending" : "Booked";
$payment_status = ($payment_method === "Pay on Pickup") ? "Unpaid" : "Paid";

// ✅ Get user_id from session
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header("Location: user/auth.php?redirect=dashboard&status=$status");
    exit;
}


// ✅ Prepare insert query (now including payment_status)
$stmt = $conn->prepare("
    INSERT INTO rentals 
    (user_id, bike_id, mobile, aadhaar, pickup_date, return_date, total_days, total_cost, 
     payment_method, pickup_location, fuel_given, pre_payment_amount, status, payment_status) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

// ✅ Bind parameters
$stmt->bind_param(
    "iissssddssdsss",
    $user_id,
    $booking['bike_id'],
    $booking['mobile'],
    $booking['aadhaar'],
    $booking['pickup_date'],
    $booking['return_date'],
    $booking['total_days'],
    $booking['total_cost'],
    $payment_method,
    $booking['pickup_location'],
    $booking['fuel_given'],
    $booking['pre_payment_amount'],
    $status,
    $payment_status
);

// ✅ Execute and update availability
if ($stmt->execute()) {
    // Make bike unavailable
    $bike_id = $booking['bike_id'];
    $conn->query("UPDATE bikes SET availability = 0 WHERE bike_id = $bike_id");

    unset($_SESSION['booking']); // Optional cleanup
    header("Location: user/dashboard.php?status=$status");
    exit;
} else {
    echo "Booking failed: " . $stmt->error;
}
?>
