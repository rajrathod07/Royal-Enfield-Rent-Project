<?php
include 'includes/db.php';

$bike_id = isset($_GET['bike_id']) ? intval($_GET['bike_id']) : 0;
$bike = null;

if ($bike_id > 0) {
    $result = $conn->query("SELECT * FROM bikes WHERE bike_id = $bike_id");
    if ($result->num_rows > 0) {
        $bike = $result->fetch_assoc();
    }
}

// Default values based on bike
$default_pickup_location = "Royal Enfield Garage, Porbandar";
$default_fuel_given = 2; // in liters
$default_pre_payment = $bike ? round($bike['price_per_day'] * 0.3) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Rent Bike | Royal Enfield</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    body {
        background: #0e0e0e;
        color: #fff;
        font-family: 'Barlow', sans-serif;
        margin: 0;
        padding: 0;
    }
    .container {
        max-width: 600px;
        background: #1a1a1a;
        margin: 50px auto;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 0 10px rgba(255, 215, 0, 0.1);
    }
    .logo {
        text-align: center;
        margin-bottom: 30px;
    }
    .logo img {
        max-width: 200px;
    }
    h2 {
        text-align: center;
        color: #d4af37;
    }
    label {
        display: block;
        margin-top: 15px;
        font-weight: 600;
    }
    input[type="text"],
    input[type="date"],
    input[type="number"],
    input[type="tel"] {
        width: 100%;
        padding: 12px;
        border-radius: 8px;
        border: none;
        margin-top: 8px;
        font-size: 15px;
    }
    input:focus {
        outline: 2px solid #d4af37;
    }
    button {
        background: #d4af37;
        color: #000;
        font-weight: bold;
        padding: 12px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        margin-top: 20px;
        transition: 0.3s ease;
        width: 100%;
    }
    button:hover {
        background: #fff;
    }
    .back-link {
        display: block;
        margin-top: 30px;
        text-align: center;
    }
    .back-link a {
        color: #d4af37;
        text-decoration: none;
        font-weight: bold;
    }
    .back-link a:hover {
        text-decoration: underline;
    }
    .summary {
        margin-top: 20px;
        background: #2a2a2a;
        padding: 15px;
        border-radius: 8px;
        color: #ccc;
        font-size: 14px;
    }
    .summary span {
        color: #fff;
        font-weight: bold;
    }
  </style>
</head>
<body>

<div class="container">
    <div class="logo">
        <img src="assets/images/logo.svg" alt="Royal Enfield">
    </div>

    <form method="post" action="payment.php" onsubmit="return validateForm()">
        <input type="hidden" name="bike_id" value="<?php echo $bike ? $bike['bike_id'] : ''; ?>">
        <input type="hidden" name="price_per_day" id="price_per_day" value="<?php echo $bike['price_per_day']; ?>">

        <label for="bike">Bike Model</label>
        <input type="text" id="bike" name="bike" value="<?php echo $bike ? $bike['name'] : 'Unknown'; ?>" readonly>

        <label for="mobile">Mobile Number</label>
        <input type="tel" name="mobile" id="mobile" required placeholder="10-digit Mobile" maxlength="10" pattern="[6-9]{1}[0-9]{9}" title="Enter a valid 10-digit mobile number starting with 6-9">

        <label for="aadhaar">Aadhaar Number</label>
        <input type="text" name="aadhaar" id="aadhaar" required placeholder="12-digit Aadhaar number" maxlength="12" pattern="\d{12}" title="Enter a valid 12-digit Aadhaar number">

        <label for="pickup">Pickup Date</label>
        <input type="date" name="pickup_date" id="pickup_date" required onchange="calculateDays()">

        <label for="return">Return Date</label>
        <input type="date" name="return_date" id="return_date" required onchange="calculateDays()">

        <label for="pickup_location">Pickup Location</label>
        <input type="text" name="pickup_location" id="pickup_location" value="<?php echo $default_pickup_location; ?>" readonly>

        <label for="fuel_given">Fuel Given (in Liters)</label>
        <input type="number" name="fuel_given" id="fuel_given" value="<?php echo $default_fuel_given; ?>" readonly>

        <label for="pre_payment_amount">Pre-Payment Amount (₹)</label>
        <input type="number" name="pre_payment_amount" id="pre_payment_amount" value="<?php echo $default_pre_payment; ?>" readonly>

        <p style="color: red; font-weight: bold;">
            ⚠️ Pre-payment is <u>non-refundable</u>. Please confirm your booking carefully.
        </p>

        <div class="summary">
            <p>Rental Days: <span id="days">0</span></p>
            <p>Total Cost: ₹<span id="total">0</span></p>
        </div>

        <input type="hidden" name="total_days" id="total_days">
        <input type="hidden" name="total_cost" id="total_cost">

        <button type="submit">Payment</button>
    </form>

    <div class="back-link">
        <a href="motorcycles.php"><i class="fas fa-arrow-left"></i> Go Back</a>
    </div>
</div>

<script>
function validateForm() {
    const aadhaar = document.getElementById("aadhaar").value.trim();
    const mobile = document.getElementById("mobile").value.trim();
    const pickup = new Date(document.getElementById("pickup_date").value);
    const returnDate = new Date(document.getElementById("return_date").value);
    const today = new Date();

    if (!/^\d{12}$/.test(aadhaar)) {
        alert("Please enter a valid 12-digit Aadhaar number.");
        return false;
    }

    if (!/^[6-9]{1}[0-9]{9}$/.test(mobile)) {
        alert("Please enter a valid 10-digit mobile number starting with 6-9.");
        return false;
    }

    if (pickup.toString() === "Invalid Date" || returnDate.toString() === "Invalid Date") {
        alert("Please select valid pickup and return dates.");
        return false;
    }

    if (pickup.getTime() === returnDate.getTime()) {
        alert("Pickup and Return dates cannot be the same.");
        return false;
    }

    if (pickup < today) {
        alert("Pickup date cannot be in the past.");
        return false;
    }

    if (returnDate < pickup) {
        alert("Return date must be after pickup date.");
        return false;
    }

    return true;
}

function calculateDays() {
    const pickupDate = new Date(document.getElementById("pickup_date").value);
    const returnDate = new Date(document.getElementById("return_date").value);
    const pricePerDay = parseInt(document.getElementById("price_per_day").value);

    if (pickupDate.toString() !== "Invalid Date" && returnDate.toString() !== "Invalid Date") {
        const diff = (returnDate - pickupDate) / (1000 * 3600 * 24);
        if (diff > 0) {
            document.getElementById("days").textContent = diff;
            document.getElementById("total").textContent = diff * pricePerDay;
            document.getElementById("total_days").value = diff;
            document.getElementById("total_cost").value = diff * pricePerDay;
        } else {
            document.getElementById("days").textContent = 0;
            document.getElementById("total").textContent = 0;
            document.getElementById("total_days").value = '';
            document.getElementById("total_cost").value = '';
        }
    }
}
</script>

</body>
</html>