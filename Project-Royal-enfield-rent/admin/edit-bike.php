<?php
session_start();
include 'includes/db.php';

$upload_dir = "../assets/images/bikes/";
$error_msg = ""; // Store error messages here

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage-bikes.php");
    exit;
}
$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM bikes WHERE bike_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$bike = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$bike) {
    header("Location: manage-bikes.php");
    exit;
}

if (isset($_POST['update_bike'])) {
    $name         = trim($_POST['name']);
    $engine       = trim($_POST['engine']);
    $mileage      = trim($_POST['mileage']);
    $gearbox      = trim($_POST['gearbox']);
    $price        = floatval($_POST['price_per_day']);
    $availability = isset($_POST['availability']) ? 1 : 0;
    $image_path   = $bike['image'];

    // Price validation
    if ($price <= 0) {
        $error_msg = "Price must be a positive value.";
    }

    // Image upload validation
    if (empty($error_msg) && !empty($_FILES['image']['name'])) {
        $filename = basename($_FILES['image']['name']);
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg','jpeg','png'];

        if (!in_array($ext, $allowed_ext)) {
            $error_msg = "Invalid file type. Only JPG, JPEG, PNG allowed.";
        } else {
            $new_filename = "bike_" . $id . "_" . time() . "." . $ext;
            $target_path = $upload_dir . $new_filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                $image_path = "assets/images/bikes/" . $new_filename;
            } else {
                $error_msg = "Failed to upload image. Check folder permissions.";
            }
        }
    }

    // Update DB if no errors
    if (empty($error_msg)) {
        $stmt = $conn->prepare("UPDATE bikes SET name=?, engine=?, mileage=?, gearbox=?, price_per_day=?, image=?, availability=? WHERE bike_id=?");
        $stmt->bind_param("ssssdssi", $name, $engine, $mileage, $gearbox, $price, $image_path, $availability, $id);
        $stmt->execute();
        $stmt->close();

        header("Location: manage-bikes.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Bike</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
body {
    font-family: 'Inter', sans-serif;
    background: #0a0a0a;
    color: #fff;
    margin: 0;
    min-height: 100vh;
}

/* Glassy Header */
header {
    background: rgba(20,20,20,0.85);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    padding: 16px 32px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    border-radius: 50px;
    box-shadow: 0 4px 30px rgba(0,0,0,0.6);
}
header h1 {
    font-size: 1.5rem;
    font-weight: 600;
}
header a {
    padding: 10px 18px;
    background: linear-gradient(135deg, #4da3ff, #1976d2);
    color: #fff;
    text-decoration: none;
    border-radius: 20px;
    font-weight: 500;
    transition: all 0.3s ease;
}
header a:hover {
    background: linear-gradient(135deg, #1976d2, #004ba0);
    transform: translateY(-2px) scale(1.05);
}

/* Container */
.container {
    max-width: 600px;
    margin: 50px auto;
    padding: 30px;
    background: rgba(30,30,30,0.85);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.1);
    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
}
h2 {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 25px;
    text-align: center;
}

/* Form Fields */
input, select {
    width: 100%;
    padding: 12px;
    margin-bottom: 18px;
    border-radius: 12px;
    border: 1px solid rgba(255,255,255,0.1);
    background: rgba(20,20,20,0.6);
    color: #fff;
    font-size: 1rem;
}
input:focus, select:focus {
    border-color: #ff944d;
    outline: none;
}

/* Modern Checkbox Styling */
.custom-checkbox {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    cursor: pointer;
}
.custom-checkbox input[type="checkbox"] {
    appearance: none;
    width: 22px;
    height: 22px;
    border: 2px solid #ff944d;
    border-radius: 6px;
    background: rgba(20,20,20,0.6);
    position: relative;
    transition: all 0.2s ease;
}
.custom-checkbox input[type="checkbox"]:checked {
    background: linear-gradient(200deg, #fa3d18, #ffa060);
    border-color: transparent;
}
.custom-checkbox input[type="checkbox"]:checked::after {
    content: '✔';
    position: absolute;
    top: 2px;
    left: 4px;
    font-size: 14px;
    color: #fff;
}

/* Submit Button */
button {
    width: 100%;
    padding: 12px;
    font-size: 1rem;
    font-weight: 600;
    border: none;
    border-radius: 12px;
    background: linear-gradient(200deg, #fa3d18, #ffa060);
    color: #fff;
    cursor: pointer;
    transition: all 0.3s ease;
}
button:hover {
    transform: translateY(-3px) scale(1.03);
}

/* Image Preview */
img.preview {
    display: block;
    margin: 0 auto 15px;
    border-radius: 12px;
    width: 200px;
    height: 120px;
    object-fit: cover;
    border: 1px solid rgba(255,255,255,0.1);
}
</style>
</head>
<body>
<header>
    <h1>Edit Bike</h1>
    <a href="manage-bikes.php"><i class="fas fa-arrow-left"></i> Back to Bikes</a>
</header>

<div class="container">
    <h2>Edit Bike Details</h2>

    <?php if (!empty($error_msg)): ?>
        <p style="color:#ff6b6b; background:#2a2a2a; padding:10px; border-radius:8px; text-align:center; margin-bottom:15px;">
            <?= htmlspecialchars($error_msg) ?>
        </p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="name" value="<?= htmlspecialchars($bike['name']) ?>" placeholder="Bike Name" required>
        <input type="text" name="engine" value="<?= htmlspecialchars($bike['engine']) ?>" placeholder="Engine">
        <input type="text" name="mileage" value="<?= htmlspecialchars($bike['mileage']) ?>" placeholder="Mileage">
        <input type="text" name="gearbox" value="<?= htmlspecialchars($bike['gearbox']) ?>" placeholder="Gearbox">
        <input type="number" name="price_per_day" value="<?= htmlspecialchars($bike['price_per_day']) ?>" placeholder="Price per day" min="0" required>

        <img src="../<?= htmlspecialchars($bike['image']) ?>" class="preview" alt="Current Image">
        <input type="file" name="image" accept="image/*">

        <label class="custom-checkbox">
            <input type="checkbox" name="availability" <?= $bike['availability'] ? 'checked' : '' ?>>
            Available
        </label>

        <button type="submit" name="update_bike"><i class="fas fa-edit"></i> Update Bike</button>
    </form>
</div>

</body>
</html>
