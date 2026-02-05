<?php
session_start();


include 'includes/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage-bikes.php");
    exit;
}
$id = intval($_GET['id']);

// Fetch bike for confirmation
$stmt = $conn->prepare("SELECT * FROM bikes WHERE bike_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$bike = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$bike) {
    header("Location: manage-bikes.php");
    exit;
}

if (isset($_POST['confirm_delete'])) {
    $stmt = $conn->prepare("DELETE FROM bikes WHERE bike_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: manage-bikes.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Delete Bike</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
body {
    font-family: 'Inter', sans-serif;
    background: #0a0a0a;
    color: #fff;
    margin: 0;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

/* Header */
header {
    background: rgba(20,20,20,0.85);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    padding: 16px 32px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    box-shadow: 0 4px 30px rgba(0,0,0,0.6);
    position: sticky;
    top: 0;
    z-index: 1000;
    border-radius: 50px;
}
header h1 {
    font-size: 1.5rem;
    font-weight: 600;
}
header a {
    padding: 10px 16px;
    background: linear-gradient(135deg, #4da3ff, #1976d2);
    color: #fff;
    text-decoration: none;
    border-radius: 20px;
    font-weight: 500;
    transition: all 0.3s ease;
}
header a:hover {
    background: linear-gradient(135deg, #1976d2, #004ba0);
}

/* Container Card */
.container {
    max-width: 500px;
    margin: 50px auto;
    padding: 30px;
    background: rgba(30,30,30,0.85);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.1);
    text-align: center;
    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
}
h2 {
    font-size: 1.3rem;
    margin-bottom: 20px;
}
img.preview {
    width: 180px;
    height: 120px;
    object-fit: cover;
    border-radius: 12px;
    margin-bottom: 15px;
    border: 1px solid rgba(255,255,255,0.2);
}
p {
    font-size: 1.1rem;
    margin-bottom: 25px;
}

/* Buttons */
form {
    display: flex;
    justify-content: center;
    gap: 15px;
    flex-wrap: wrap;
}
button, .cancel-link {
    padding: 10px 18px;
    border-radius: 15px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    border: none;
    transition: all 0.3s ease;
}
button.delete {
    background: linear-gradient(135deg, #dc3545, #b71c1c);
    color: #fff;
}
button.delete:hover {
    transform: translateY(-2px) scale(1.05);
}
.cancel-link {
    display: inline-block;
    background: linear-gradient(135deg, #6c757d, #495057);
    color: #fff;
    text-decoration: none;
}
.cancel-link:hover {
    transform: translateY(-2px) scale(1.05);
}
</style>
</head>
<body>
<header>
    <h1>Delete Bike</h1>
    <a href="manage-bikes.php"><i class="fas fa-arrow-left"></i> Back</a>
</header>

<div class="container">
    <h2>Are you sure you want to delete this bike?</h2>
    <img src="../<?= htmlspecialchars($bike['image']) ?>" class="preview" alt="Bike Image">
    <p><strong><?= htmlspecialchars($bike['name']) ?></strong></p>
    <form method="POST">
        <button type="submit" name="confirm_delete" class="delete"><i class="fas fa-trash"></i> Yes, Delete</button>
        <a href="manage-bikes.php" class="cancel-link"><i class="fas fa-times"></i> Cancel</a>
    </form>
</div>
</body>
</html>
