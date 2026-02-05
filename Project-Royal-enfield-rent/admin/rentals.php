<?php
session_start();

include 'includes/db.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$rentals = $conn->query("
    SELECT r.*, u.name AS user_name, b.name AS bike_name
    FROM rentals r
    JOIN users u ON r.user_id = u.user_id
    JOIN bikes b ON r.bike_id = b.bike_id
    ORDER BY r.pickup_date DESC
");

if (!$rentals) {
    die("Query failed: " . $conn->error);
}

$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Rental Status - Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
body {
    font-family: 'Inter', sans-serif;
   background: linear-gradient(to right, #0a0a0c, #121214);
    color: #fff;
    margin: 0;
}

/* Glassmorphic Header */
header {
    background: rgb(20 20 20 / 85%);
    border-radius: 159px;
    backdrop-filter: blur(7px);
    -webkit-backdrop-filter: blur(12px);
    color: #fff;
    padding: 16px 37px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.6);
    position: sticky;
    top: 3px;
    z-index: 1000;
}

header .left {
    display: flex;
    align-items: center;
    gap: 15px;
}

header img {
    height: 42px;
}

header h1 {
    font-size: 1.5rem;
    font-weight: 600;
    letter-spacing: 0.8px;
    position: relative;
}

/* Back Button Styling */
header .back-btn {
    padding: 15px 17px;
    background: linear-gradient(135deg, #4da3ff, #1976d2);
    border-radius: 27px;
    color: #fff;
    font-size: 0.9rem;
    font-weight: 500;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: all 0.3s ease;
}
header .back-btn:hover {
    background: linear-gradient(135deg, #1976d2, #004ba0);
}

/* Container */
.container {
    padding: 25px;
    max-width: 1300px;
    margin: auto;
}

.welcome {
    margin-bottom: 20px;
    font-size: 1.1rem;
    letter-spacing: 0.5px;
}

.cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
    margin-top: 25px;
}

/* Card Styling */
.card {
    background: #111;
    border-radius: 17px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    border: 1px solid rgba(255,255,255,0.05);
    transition: transform 0.3s ease;
}
.card:hover {
    transform: translateY(-10px) scale(1.03);
}
.card-content {
    padding: 16px;
    flex-grow: 1;
}
.card-content h3 {
    margin: 0 0 8px;
    font-size: 1.2rem;
    font-weight: 600;
}
.card-content p {
    font-size: 0.9rem;
    color: #aaa;
    margin: 4px 0;
}

/* Badges */
.badge {
    padding: 4px 10px;
    border-radius: 17px;
    color: #fff;
    font-size: 0.8rem;
    font-weight: 500;
    display: inline-block;
    margin-right: 5px;
}
.badge.paid { background: #28a745; }
.badge.unpaid { background: #dc3545; }
.badge.cancelled { background: #6c757d; }
.badge.pending { background: #ffc107; color: #000; }
.badge.collected { background: #17a2b8; }
.badge.not-collected { background: #6c757d; }

/* Card Actions */
.actions {
    display: flex;
    justify-content: flex-end;
    padding: 12px 15px;
    background: #151515;
    border-top: 1px solid rgba(255,255,255,0.05);
}
.actions a {
    color: #fff;
    font-size: 0.85rem;
    text-decoration: none;
    padding: 8px 17px;
    border-radius: 15px;
    transition: all 0.3s ease;
}
.actions a:hover {
    transform: translateY(-2px) scale(1.05);
}
</style>
</head>
<body>
<header>
    <div class="left">
        <img src="../assets/images/logo.svg" alt="Logo">
        <h1>Rental Status</h1>
    </div>
    <a href="dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
</header>

<div class="container">
    <div class="welcome">Welcome back, <?= htmlspecialchars($username) ?> 📋</div>

    <?php if ($rentals->num_rows > 0): ?>
        <div class="cards">
        <?php while ($r = $rentals->fetch_assoc()):
            $status = strtolower($r['status']);
            $pickup = strtolower($r['pickup_status']);
            $payment = strtolower($r['payment_status']);
        ?>
            <div class="card">
                <div class="card-content">
                    <h3><?= htmlspecialchars($r['user_name']) ?> — <?= htmlspecialchars($r['bike_name']) ?></h3>
                    <p><strong>Pickup:</strong> <?= $r['pickup_date'] ?> | <strong>Return:</strong> <?= $r['return_date'] ?></p>
                    <p>
                        <span class="badge <?= $status === 'cancelled' ? 'cancelled' : 'pending' ?>"><?= ucfirst($r['status']) ?></span>
                        <span class="badge <?= $pickup === 'collected' ? 'collected' : 'not-collected' ?>"><?= ucfirst($r['pickup_status']) ?></span>
                        <span class="badge <?= $payment === 'paid' ? 'paid' : 'unpaid' ?>"><?= ucfirst($r['payment_status'] ?? 'Unpaid') ?></span>
                    </p>
                </div>
            </div>
        <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p style="color:#aaa;">No rental records found.</p>
    <?php endif; ?>
</div>
</body>
</html>
