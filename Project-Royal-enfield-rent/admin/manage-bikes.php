<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

include '../includes/db.php';

$upload_dir = "../assets/images/bikes/";
$bikes = mysqli_query($conn, "SELECT * FROM bikes ORDER BY bike_id DESC");
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Bikes</title>
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

.btn {
    padding: 10px 18px;
    background: linear-gradient(200deg, #fa3d18, #ffa060);
    color: #fff;
    border: none;
    border-radius: 24px;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.3s ease;
}
.btn:hover {
    transform: translateY(-3px) scale(1.03);
}

/* Cards Grid */
.cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
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
.card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    transition: transform 0.4s ease;
}
.card:hover img {
    transform: scale(1.06);
}
.card-content {
    padding: 16px;
    flex-grow: 1;
}
.card-content h3 {
    margin: 0 0 8px;
    font-size: 1.15rem;
    font-weight: 600;
    letter-spacing: 0.4px;
}
.details {
    font-size: 0.85rem;
    color: #aaa;
    margin-bottom: 10px;
    line-height: 1.4;
}
.price {
    font-size: 1rem;
    font-weight: bold;
    margin-bottom: 10px;
    background: linear-gradient(90deg, #ff4c29, #ff944d);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.badge {
    padding: 4px 10px;
    border-radius: 17px;
    color: #fff;
    font-size: 0.8rem;
}
.badge.available { background: #368b1f; }
.badge.unavailable { background: #af1f2e; }

/* Card Actions */
.actions {
    display: flex;
    justify-content: space-between;
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
.actions a.edit {
    background: linear-gradient(135deg, #1282fa, #003b74);
}
.actions a.delete {
    background: linear-gradient(135deg, #f32424, #8d0202);
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
        <h1>Manage Bikes</h1>
    </div>
   
    <a href="dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
</header>

<div class="container">
    <div class="welcome">Welcome back, <?= htmlspecialchars($username) ?> 🚴‍♂️</div>
    <a href="add-bike.php" class="btn"><i class="fas fa-plus"></i> Add New Bike</a>

    <div class="cards">
        <?php while ($row = mysqli_fetch_assoc($bikes)): ?>
        <div class="card">
            <img src="../<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
            <div class="card-content">
                <h3><?= htmlspecialchars($row['name']) ?></h3>
                <div class="details">
                    Engine: <?= htmlspecialchars($row['engine']) ?><br>
                    Mileage: <?= htmlspecialchars($row['mileage']) ?><br>
                    Gearbox: <?= htmlspecialchars($row['gearbox']) ?>
                </div>
                <div class="price">₹<?= htmlspecialchars($row['price_per_day']) ?> / day</div>
                <?php if ((int)$row['availability'] === 1): ?>
                    <span class="badge available">Available</span>
                <?php else: ?>
                    <span class="badge unavailable">Unavailable</span>
                <?php endif; ?>
            </div>
            <div class="actions">
                <a href="edit-bike.php?id=<?= (int)$row['bike_id'] ?>" class="edit"><i class="fas fa-edit"></i> Edit</a>
                <a href="delete-bike.php?id=<?= (int)$row['bike_id'] ?>" class="delete"><i class="fas fa-trash"></i> Delete</a>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html>
