<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth.php?redirect=dashboard.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle profile image upload
if (isset($_POST['save_photo']) && isset($_FILES['profile_photo'])) {
    $file = $_FILES['profile_photo'];

    if ($file['error'] === 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        if (in_array($file['type'], $allowed_types)) {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $new_filename = 'user_' . $user_id . '.' . $ext;
            $upload_dir = __DIR__ . '/../assets/users/';
            $upload_path = $upload_dir . $new_filename;

            if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                $conn->query("UPDATE users SET profile_img='$new_filename' WHERE user_id=$user_id");
                $user['profile_img'] = $new_filename;
                $success_msg = "Profile photo updated!";
            } else {
                $error_msg = "Failed to upload. Check folder permissions.";
            }
        } else {
            $error_msg = "Invalid file type. Only JPG, PNG, GIF, or WEBP allowed.";
        }
    } else {
        $error_msg = "Upload error: " . $file['error'];
    }
}

// Get user info
$userQuery = $conn->query("SELECT * FROM users WHERE user_id = $user_id");
$user = $userQuery->fetch_assoc();

// Fetch bookings
$bookingQuery = $conn->query("
    SELECT r.*, b.name AS bike_name 
    FROM rentals r 
    JOIN bikes b ON r.bike_id = b.bike_id 
    WHERE r.user_id = $user_id
    ORDER BY r.pickup_date DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>User Dashboard - Royal Enfield</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
    body { 
        font-family: 'Barlow', sans-serif; 
        background: #0e0e0e; 
        color: #fff; 
        margin:0; 
        padding:30px; 
    }

    .container { 
        max-width:1100px; 
        margin:auto; 
        background:#1a1a1a; 
        padding:30px; 
        border-radius:12px; 
        box-shadow:0 0 20px rgba(255,215,0,0.05);
    }

    .header { 
        display:flex; 
        justify-content:space-between; 
        align-items:center;
    }

    .logo { width:180px; }

    .logout-btn { 
        background:#ff3b3b; 
        color:#fff; 
        padding:10px 20px; 
        border-radius:6px; 
        font-weight:bold; 
        text-decoration:none; 
        display:flex; 
        align-items:center; 
        gap:6px;
    }

    .logout-btn:hover { background:#e60000; }

    h2,h3 { color:#d4af37; }
    hr { border-color:#333; }

    /* Profile Photo */
    .profile-photo-wrapper { 
        position:relative; 
        display:inline-block; 
        margin-bottom:15px;
    }

    .profile-photo { 
        width:80px; 
        height:80px; 
        border-radius:50%; 
        object-fit:cover; 
        cursor:pointer; 
        border:2px solid #d4af37; 
        transition:0.3s;
    }

    .profile-photo:hover { opacity:0.8; }

    #saveBtn { 
        margin-left:10px; 
        background:#d4af37; 
        color:#111; 
        border:none; 
        padding:6px 12px; 
        border-radius:5px; 
        cursor:pointer; 
        font-weight:bold; 
        display:none; 
    }

    /* Booking Cards */
    .booking-card { 
        background:#111; 
        border:1px solid #333; 
        border-radius:10px; 
        padding:20px; 
        margin-bottom:30px; 
        box-shadow:0 0 10px rgba(255,255,255,0.03);
    }

    .booking-card .top { 
        font-size:18px; 
        font-weight:bold; 
        color:#fff; 
        margin-bottom:15px; 
        display:flex; 
        align-items:center; 
        gap:8px;
    }

    .info-grid { 
        display:grid; 
        grid-template-columns:repeat(auto-fit, minmax(170px, 1fr)); 
        gap:15px; 
        font-size:14px;
    }

    .label { color:#999; font-size:13px; display:flex; align-items:center; gap:5px; }
    .badge { padding:5px 10px; border-radius:20px; font-size:13px; font-weight:bold; display:inline-block; }
    .badge.paid { background:#28a745; color:#fff; }
    .badge.unpaid { background:#dc3545; color:#fff; }
    .badge.cancelled { background:#888; }
    .badge.pending { background:#ffc107; color:#000; }

    .progress-bar { height:16px; background:#333; border-radius:20px; overflow:hidden; margin-top:15px; }
    .progress-fill { height:100%; width:0; color:#fff; text-align:center; font-size:12px; line-height:16px; border-radius:20px; white-space:nowrap; transition:width 1.2s ease; }

    .cancel-btn { display:inline-block; background:#ff3b3b; color:#fff; padding:6px 12px; border-radius:6px; font-weight:bold; font-size:13px; text-decoration:none; margin-top:10px; }
    .cancel-btn:hover { background:#e60000; }

    .back-btn { display:inline-block; margin-top:30px; background:#d4af37; color:#000; padding:10px 20px; border-radius:6px; font-weight:bold; text-decoration:none; }
    .back-btn:hover { background:#fff; }

    @media(max-width:768px){ 
        .header{flex-direction:column; align-items:flex-start; gap:10px;} 
        .logo{width:130px;} 
        .info-grid{grid-template-columns:1fr 1fr; gap:12px; font-size:13px;} 
        .cancel-btn,.back-btn{width:90%; text-align:center;} 
    }

    @media(max-width:480px){ 
        body{padding:15px;} 
        .container{padding:20px;} 
        h2{font-size:18px;} 
        h3{font-size:16px;} 
        .info-grid{grid-template-columns:1fr; gap:10px; font-size:12px;} 
        .booking-card{padding:15px;} 
        .progress-fill{font-size:11px;} 
    }
</style>
</head>
<body>

<div class="container">

    <!-- Header -->
    <div class="header">
        <img src="logo.svg" class="logo" alt="Royal Enfield Logo">
        <a href="logout.php" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>

    <!-- Profile Photo + Welcome -->
    <div class="profile-photo-wrapper">
        <form method="POST" enctype="multipart/form-data" id="photoForm">
            <label for="profile_photo">
                <img class="profile-photo"
                src="../assets/users/<?php echo !empty($user['profile_img']) ? htmlspecialchars($user['profile_img']) : 'default.png'; ?>" 
                alt="User Photo">
            </label>
            <input type="file" name="profile_photo" id="profile_photo" accept="image/*" style="display:none;">
            <button type="submit" name="save_photo" id="saveBtn">Save</button>
        </form>
    </div>

    <h2>Welcome, <?= htmlspecialchars($user['name']); ?></h2>
    <hr>

    <!-- Success/Error Messages -->
    <?php
    if (!empty($success_msg)) echo "<p style='color:green;'>$success_msg</p>";
    if (!empty($error_msg)) echo "<p style='color:red;'>$error_msg</p>";
    ?>

    <!-- Bookings Section -->
    <h3>Your Bookings</h3>

    <?php if ($bookingQuery->num_rows > 0): ?>
        <?php while ($row = $bookingQuery->fetch_assoc()): ?>
            <?php
            $status = $row['status'];
            $progressWidth = '0%';
            $gradient = '#555';

            switch ($status) {
                case 'Pending': $progressWidth='20%'; $gradient='linear-gradient(to right, #ff4d4d, #cc0000)'; break;
                case 'Booked': $progressWidth='35%'; $gradient='linear-gradient(to right, #ff2929ff, #eb8e03ff)'; break;
                case 'In Transit': $progressWidth='65%'; $gradient='linear-gradient(to right, #7087fdff, #113cfcff)'; break;
                case 'Returned': $progressWidth='100%'; $gradient='linear-gradient(to right, #28a745, #218838)'; break;
                case 'Cancelled': $progressWidth='100%'; $gradient='linear-gradient(to right, #ff4d4d, #cc0000)'; break;
            }
            ?>
            <div class="booking-card">
                <div class="top"><i class="fas fa-motorcycle"></i> <?= htmlspecialchars($row['bike_name']); ?></div>
                
                <div class="info-grid">
                    <div><span class="label"><i class="fas fa-calendar-alt"></i> Pickup</span><br><?= htmlspecialchars($row['pickup_date']); ?></div>
                    <div><span class="label"><i class="fas fa-flag-checkered"></i> Return</span><br><?= htmlspecialchars($row['return_date']); ?></div>
                    <div><span class="label"><i class="fas fa-clock"></i> Days</span><br><?= htmlspecialchars($row['total_days']); ?></div>
                    <div><span class="label"><i class="fas fa-gas-pump"></i> Fuel</span><br><?= htmlspecialchars($row['fuel_given']); ?> L</div>
                    <div><span class="label"><i class="fas fa-map-marker-alt"></i> Location</span><br><?= htmlspecialchars($row['pickup_location']); ?></div>
                    <div><span class="label"><i class="fas fa-rupee-sign"></i> Total</span><br>₹<?= htmlspecialchars($row['total_cost']); ?></div>
                    <div><span class="label"><i class="fas fa-money-bill-wave"></i> Pre-Payment</span><br>₹<?= htmlspecialchars($row['pre_payment_amount']); ?></div>
                    <div>
                        <span class="label"><i class="fas fa-info-circle"></i> Status</span><br>
                        <span class="badge <?= $row['status'] === 'Cancelled' ? 'cancelled' : ($row['status'] === 'Returned' ? 'paid' : 'pending') ?>">
                            <?= htmlspecialchars($row['status']); ?>
                        </span>
                        <?php if ($row['cancellation_requested']): ?>
                            <br><small>Cancel Request: <?= htmlspecialchars($row['cancellation_status'] ?? 'Pending'); ?></small>
                        <?php endif; ?>
                    </div>
                    <div>
                        <span class="label"><i class="fas fa-credit-card"></i> Payment</span><br>
                        <span class="badge <?= $row['payment_status'] === 'Paid' ? 'paid' : 'unpaid' ?>">
                            <?= htmlspecialchars($row['payment_status'] ?? 'Unpaid'); ?>
                        </span>
                    </div>
                    <div>
                        <?php if ($row['status'] === 'Booked' && !$row['cancellation_requested']): ?>
                            <a class="cancel-btn" href="cancel-booking.php?id=<?= $row['rental_id']; ?>" onclick="return confirm('Request cancellation for this booking?')">Cancel</a>
                        <?php else: ?>
                            <span style="color:#888; font-size: 13px;">No Action</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="progress-bar">
                    <div class="progress-fill" style="background: <?= $gradient ?>;" data-width="<?= $progressWidth ?>">
                        <?= htmlspecialchars($status) ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>You have not made any bookings yet.</p>
    <?php endif; ?>

    <a href="../motorcycles.php" class="back-btn">← Back to Bikes</a>
</div>

<script>
window.addEventListener('DOMContentLoaded', () => {
    // Animate progress bars
    document.querySelectorAll('.progress-fill').forEach(el => {
        const targetWidth = el.getAttribute('data-width');
        setTimeout(() => { el.style.width = targetWidth; }, 100);
    });

    // Show save button when file selected
    document.getElementById('profile_photo').addEventListener('change', function(){
        document.getElementById('saveBtn').style.display = 'inline-block';
    });
});
</script>
</body>
</html>
