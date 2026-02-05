<?php
session_start();
include 'includes/db.php';
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}


// Handle booking operations
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'])) {
    $id = intval($_POST['booking_id']);

    // Get bike_id for this rental
    $bikeRow = $conn->query("SELECT bike_id FROM rentals WHERE rental_id=$id")->fetch_assoc();
    $bike_id = $bikeRow['bike_id'] ?? null;

    if (isset($_POST['approve_cancel'])) {
        $conn->query("UPDATE rentals SET cancellation_status='Approved', status='Cancelled' WHERE rental_id=$id");
        if ($bike_id) {
            $conn->query("UPDATE bikes SET availability=1 WHERE bike_id=$bike_id");
        }
    } elseif (isset($_POST['reject_cancel'])) {
        $conn->query("UPDATE rentals SET cancellation_status='Rejected' WHERE rental_id=$id");
    } elseif (isset($_POST['mark_paid'])) {
        $conn->query("UPDATE rentals SET payment_status='Paid', status='Booked' WHERE rental_id=$id");
        if ($bike_id) {
            $conn->query("UPDATE bikes SET availability=0 WHERE bike_id=$bike_id");
        }
    } elseif (isset($_POST['mark_unpaid'])) {
        $conn->query("UPDATE rentals SET payment_status='Unpaid', status='Pending' WHERE rental_id=$id");
    } elseif (isset($_POST['mark_collected'])) {
        $conn->query("UPDATE rentals SET pickup_status='Collected', status='In Transit' WHERE rental_id=$id");
        if ($bike_id) {
            $conn->query("UPDATE bikes SET availability=0 WHERE bike_id=$bike_id");
        }
    } elseif (isset($_POST['undo_collected'])) {
        $conn->query("UPDATE rentals SET pickup_status='Not Collected', status='Booked' WHERE rental_id=$id");
    } elseif (isset($_POST['mark_returned'])) {
        $conn->query("UPDATE rentals SET status='Returned' WHERE rental_id=$id");
        if ($bike_id) {
            $conn->query("UPDATE bikes SET availability=1 WHERE bike_id=$bike_id");
        }
    } elseif (isset($_POST['undo_returned'])) {
        $conn->query("UPDATE rentals SET status='In Transit' WHERE rental_id=$id");
    } elseif (isset($_POST['soft_delete'])) {
        $conn->query("UPDATE rentals SET is_deleted=1 WHERE rental_id=$id");
    } elseif (isset($_POST['restore_booking'])) {
        $conn->query("UPDATE rentals SET is_deleted=0 WHERE rental_id=$id");
    } elseif (isset($_POST['delete_permanently'])) {
        $conn->query("DELETE FROM rentals WHERE rental_id=$id");
        if ($bike_id) {
            $conn->query("UPDATE bikes SET availability=1 WHERE bike_id=$bike_id");
        }
    }

    header("Location: users.php?email=" . urlencode($_GET['email']));
    exit;
}

$users = $conn->query("SELECT * FROM users ORDER BY name");
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin - Users</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
    body {
        font-family: 'Inter', sans-serif;
        background: linear-gradient(to right, #0a0a0c, #121214);
        color: #fff;
        margin: 0;
    }

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
    }

    header .back-btn {
        padding: 15px 17px;
        background: linear-gradient(135deg, #4da3ff, #1976d2);
        border-radius: 27px;
        color: #fff;
        text-decoration: none;
        display: flex;
        gap: 6px;
        transition: 0.3s;
    }

    header .back-btn:hover {
        background: linear-gradient(135deg, #1976d2, #004ba0);
    }

    .container {
        padding: 25px;
        max-width: 1300px;
        margin: auto;
    }

    .welcome {
        margin-bottom: 20px;
        font-size: 1.1rem;
    }

    /* Cards */
    .cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
    }

    .card {
        background: #111;
        border-radius: 17px;
        padding: 16px;
        border: 1px solid rgba(255, 255, 255, 0.05);
        transition: 0.3s;
    }

    .card:hover {
        transform: translateY(-5px) scale(1.02);
    }

    .card h3 {
        margin: 0 0 8px;
        font-size: 1.2rem;
    }

    .card p {
        margin: 4px 0;
        font-size: 0.9rem;
        color: #aaa;
    }

    .btn {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        text-decoration: none;
        color: #fff;
        background: linear-gradient(200deg, #fa3d18, #ffa060);
        display: inline-block;
        margin-top: 8px;
        transition: 0.3s;
    }

    .btn:hover {
        transform: translateY(-2px) scale(1.03);
    }

    /* Badges */
    .badge {
        padding: 4px 10px;
        border-radius: 17px;
        font-size: 0.8rem;
        font-weight: 500;
        display: inline-block;
        margin-right: 5px;
    }

    .badge.paid {
        background: #28a745;
    }

    .badge.unpaid {
        background: #dc3545;
    }

    .badge.cancelled {
        background: #6c757d;
    }

    .badge.pending {
        background: #ffc107;
        color: #000;
    }

    .badge.collected {
        background: #17a2b8;
    }

    .badge.not-collected {
        background: #6c757d;
    }

    /* Booking Card */
    .booking-card {
        background: #151515;
        border-radius: 15px;
        padding: 12px;
        margin-top: 10px;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .booking-card p {
        margin: 4px 0;
    }

    .booking-actions {
        margin-top: 6px;
    }

    .booking-actions form button {
        padding: 6px 10px;
        margin: 2px;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        font-size: 12px;
        background: #444;
        color: #fff;
        transition: 0.2s;
    }

    .booking-actions form button:hover {
        background: #666;
    }
</style>
</head>
<body>

<header>
    <div class="left">
        <img src="../assets/images/logo.svg" alt="Logo">
        <h1>Users & Bookings</h1>
    </div>
    <a href="dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i> Dashboard</a>
</header>

<div class="container">
    <div class="welcome">Welcome back, <?= htmlspecialchars($username) ?> 👥</div>

    <div class="cards">
        <?php while ($u = $users->fetch_assoc()): 
            $user_id = $u['user_id'];
            $rental = $conn->query("SELECT mobile FROM rentals WHERE user_id=$user_id AND is_deleted=0 ORDER BY rental_id DESC LIMIT 1")->fetch_assoc();
        ?>
        <div class="card">
            <h3><?= htmlspecialchars($u['name']) ?></h3>
            <p><strong>Email:</strong> <?= htmlspecialchars($u['email']) ?></p>
            <p><strong>Mobile:</strong> <?= $rental['mobile'] ?? 'N/A' ?></p>
            <a href="?email=<?= urlencode($u['email']) ?>" class="btn"><i class="fas fa-eye"></i> View Bookings</a>

            <?php if (isset($_GET['email']) && $_GET['email'] === $u['email']):
                $bookings = $conn->query("SELECT r.*, b.name AS bike_name FROM rentals r JOIN bikes b ON r.bike_id=b.bike_id WHERE r.user_id=$user_id ORDER BY r.pickup_date DESC");
                while ($b = $bookings->fetch_assoc()):
            ?>
            <div class="booking-card">
                <p><strong>Bike:</strong> <?= htmlspecialchars($b['bike_name']) ?></p>
                <p><strong>Pickup:</strong> <?= $b['pickup_date'] ?> | <strong>Return:</strong> <?= $b['return_date'] ?></p>
                <p>
                    <strong>Status:</strong> <span class="badge <?= $b['status']==='Cancelled'?'cancelled':'pending' ?>"><?= $b['status'] ?></span>
                    <strong>Pickup:</strong> <span class="badge <?= $b['pickup_status']==='Collected'?'collected':'not-collected' ?>"><?= $b['pickup_status'] ?></span>
                    <strong>Payment:</strong> <span class="badge <?= $b['payment_status']==='Paid'?'paid':'unpaid' ?>"><?= $b['payment_status'] ?? 'Unpaid' ?></span>
                </p>
                <div class="booking-actions">
                    <form method="post" onsubmit="return confirm('Are you sure?');">
                        <input type="hidden" name="booking_id" value="<?= $b['rental_id'] ?>">

                        <!-- Payment actions -->
                        <?php if($b['payment_status']==='Unpaid'): ?>
                            <button name="mark_paid">💰 Paid</button>
                        <?php else: ?>
                            <button name="mark_unpaid">↩️ Unpaid</button>
                        <?php endif; ?>

                        <!-- Pickup actions -->
                        <?php if($b['pickup_status']==='Not Collected'): ?>
                            <button name="mark_collected">📦 Collected</button>
                        <?php else: ?>
                            <button name="undo_collected">↩️ Undo Collect</button>
                        <?php endif; ?>

                        <!-- Return actions -->
                        <?php if($b['status']==='In Transit'): ?>
                            <button name="mark_returned">✅ Return</button>
                        <?php elseif($b['status']==='Returned'): ?>
                            <button name="undo_returned">↩️ Undo Return</button>
                        <?php endif; ?>

                        <!-- Cancellation actions -->
                        <?php if($b['cancellation_requested'] && $b['cancellation_status']==='Pending'): ?>
                            <button name="approve_cancel">✅ Approve</button>
                            <button name="reject_cancel">❌ Reject</button>
                        <?php endif; ?>

                        <!-- Soft delete / restore -->
                        <?php if(!$b['is_deleted']): ?>
                            <button name="soft_delete">🗑️ Trash</button>
                        <?php else: ?>
                            <button name="restore_booking">♻️ Restore</button>
                            <button name="delete_permanently">❌ Delete</button>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
            <?php endwhile; endif; ?>
        </div>
        <?php endwhile; ?>
    </div>

</div>
</body>
</html>
