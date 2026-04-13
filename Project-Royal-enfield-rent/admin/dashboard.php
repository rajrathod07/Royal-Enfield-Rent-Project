<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

include 'includes/db.php';

// Safe query function
function safe_query($conn, $query) {
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Query failed [$query]: " . mysqli_error($conn));
    }
    return $result;
}

// Fetch counts and totals
$bikes_result    = safe_query($conn, "SELECT COUNT(*) AS count FROM bikes");
$bikes           = mysqli_fetch_assoc($bikes_result)['count'];

$rentals_result  = safe_query($conn, "SELECT COUNT(*) AS count FROM rentals WHERE is_deleted = 0");
$rentals         = mysqli_fetch_assoc($rentals_result)['count'];

$users_result    = safe_query($conn, "SELECT COUNT(*) AS count FROM users");
$users           = mysqli_fetch_assoc($users_result)['count'];

// Total earnings from completed (non-deleted) rentals
$earnings_result = safe_query($conn, "SELECT SUM(total_cost) AS total FROM rentals WHERE is_deleted = 0 AND payment_status = 'Paid'");
$earnings_row    = mysqli_fetch_assoc($earnings_result);
$payments        = $earnings_row['total'] ?? 0;

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>

<header>
  <img src="../assets/images/logo.svg" alt="Royal Enfield Logo" />
  <h1>Admin Dashboard</h1>
</header>

<div class="container">
  <div class="welcome">Welcome back, <?= htmlspecialchars($username) ?> 🙃</div>

  <div class="cards">
    <div class="card">
      <i class="fas fa-motorcycle icon"></i>
      <h3>Total Bikes</h3>
      <p><?= $bikes ?></p>
    </div>
    <div class="card">
      <i class="fas fa-clipboard-list icon"></i>
      <h3>Total Rentals</h3>
      <p><?= $rentals ?></p>
    </div>
    <div class="card">
      <i class="fas fa-users icon"></i>
      <h3>Total Users</h3>
      <p><?= $users ?></p>
    </div>
    <div class="card">
      <i class="fas fa-wallet icon"></i>
      <h3>Total Earnings</h3>
      <p>₹<?= number_format($payments) ?></p>
    </div>
  </div>

  <h3 style="margin-bottom: 20px;">Quick Shortcuts</h3>
  <div class="shortcuts">
    <a href="manage-bikes.php" class="shortcut"><i class="fas fa-cogs"></i>Manage Bikes</a>
    <a href="rentals.php" class="shortcut"><i class="fas fa-list"></i>View Rentals</a>
    <a href="users.php" class="shortcut"><i class="fas fa-user"></i>Users</a>
    <a href="admin_feedback.php" class="shortcut"><i class="fas fa-comment"></i>View Reviews</a>
    <a href="logout.php" class="shortcut"><i class="fas fa-sign-out-alt"></i>Logout</a>
  </div>
</div>
    
</body>
</html>
