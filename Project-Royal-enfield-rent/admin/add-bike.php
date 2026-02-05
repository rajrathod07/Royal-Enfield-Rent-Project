<?php
session_start();
include 'includes/db.php';

$upload_dir = "../assets/images/bikes/";
$error_msg = ""; // Store errors here

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['add_bike'])) {
    $name         = trim($_POST['name']);
    $engine       = trim($_POST['engine']);
    $mileage      = trim($_POST['mileage']);
    $gearbox      = trim($_POST['gearbox']);
    $price        = floatval($_POST['price_per_day']);
    $availability = isset($_POST['availability']) ? 1 : 0;
    $image_path   = null;

    // Price validation
    if ($price <= 0) {
        $error_msg = "Price must be a positive value.";
    }

    // Image upload validation
    if (empty($error_msg) && !empty($_FILES['image']['name'])) {
        $filename = basename($_FILES['image']['name']); 
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png'];

        if (!in_array($ext, $allowed_ext)) {
            $error_msg = "Invalid file type. Only JPG, JPEG, PNG are allowed.";
        } else {
            $new_filename = "bike_" . time() . "." . $ext;
            $target_path = $upload_dir . $new_filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                $image_path = "assets/images/bikes/" . $new_filename;
            } else {
                $error_msg = "Failed to upload image. Check folder permissions.";
            }
        }
    }

    // Insert to DB if no errors
    if (empty($error_msg)) {
        $stmt = $conn->prepare("INSERT INTO bikes (name, engine, mileage, gearbox, price_per_day, image, availability) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssdsi", $name, $engine, $mileage, $gearbox, $price, $image_path, $availability);
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
<title>Add Bike</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
body { font-family: 'Inter', sans-serif; background: #0a0a0a; color: #fff; margin: 0; min-height: 100vh; }
header { background: rgba(20,20,20,0.85); backdrop-filter: blur(10px); padding:16px 32px; display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid rgba(255,255,255,0.1); border-radius:50px; box-shadow:0 4px 30px rgba(0,0,0,0.6);}
header h1{font-size:1.5rem;font-weight:600;}
header a{padding:10px 18px;background:linear-gradient(135deg,#4da3ff,#1976d2);color:#fff;text-decoration:none;border-radius:20px;font-weight:500;transition:all 0.3s ease;}
header a:hover{background:linear-gradient(135deg,#1976d2,#004ba0);transform:translateY(-2px) scale(1.05);}

.container{max-width:600px;margin:50px auto;padding:30px;background:rgba(30,30,30,0.85);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border-radius:20px;border:1px solid rgba(255,255,255,0.1);box-shadow:0 10px 30px rgba(0,0,0,0.5);}
h2{font-size:1.3rem;font-weight:600;margin-bottom:25px;text-align:center;}
input,select{width:100%;padding:12px;margin-bottom:18px;border-radius:12px;border:1px solid rgba(255,255,255,0.1);background:rgba(20,20,20,0.6);color:#fff;font-size:1rem;}
input:focus,select:focus{border-color:#ff944d;outline:none;}
.custom-checkbox{display:flex;align-items:center;gap:10px;margin-bottom:20px;cursor:pointer;}
.custom-checkbox input[type="checkbox"]{appearance:none;width:22px;height:22px;border:2px solid #ff944d;border-radius:6px;background:rgba(20,20,20,0.6);position:relative;transition:all 0.2s ease;}
.custom-checkbox input[type="checkbox"]:checked{background:linear-gradient(200deg,#fa3d18,#ffa060);border-color:transparent;}
.custom-checkbox input[type="checkbox"]:checked::after{content:'✔';position:absolute;top:2px;left:4px;font-size:14px;color:#fff;}
button{width:100%;padding:12px;font-size:1rem;font-weight:600;border:none;border-radius:12px;background:linear-gradient(200deg,#fa3d18,#ffa060);color:#fff;cursor:pointer;transition:all 0.3s ease;}
button:hover{transform:translateY(-3px) scale(1.03);}
.error-msg {color:#ff6b6b; background:#2a2a2a; padding:10px; border-radius:8px; text-align:center; margin-bottom:15px;}
</style>
</head>
<body>
<header>
<h1>Add Bike</h1>
<a href="manage-bikes.php"><i class="fas fa-arrow-left"></i> Back to Bikes</a>
</header>

<div class="container">
<h2>Enter Bike Details</h2>

<?php if (!empty($error_msg)): ?>
    <p class="error-msg"><?= htmlspecialchars($error_msg) ?></p>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="name" placeholder="Bike Name" required>
    <input type="text" name="engine" placeholder="Engine">
    <input type="text" name="mileage" placeholder="Mileage">
    <input type="text" name="gearbox" placeholder="Gearbox">
    <input type="number" name="price_per_day" placeholder="Price per day" min="0" required>
    <input type="file" name="image" accept=".jpg,.jpeg,.png">
    <label class="custom-checkbox">
        <input type="checkbox" name="availability" checked>
        Available
    </label>
    <button type="submit" name="add_bike"><i class="fas fa-plus"></i> Add Bike</button>
</form>
</div>
</body>
</html>
