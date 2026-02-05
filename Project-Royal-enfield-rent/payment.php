<?php
session_start();

// If booking session is not set but POST is available, set session
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bike'])) {
    $_SESSION['booking'] = $_POST;
    $booking = $_SESSION['booking'];
} elseif (isset($_SESSION['booking'])) {
    $booking = $_SESSION['booking'];
} else {
    header("Location: motorcycles.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Payment | Royal Enfield</title>
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
}
h2 {
    text-align: center;
    color: #d4af37;
}
.logo {
    text-align: center;
    margin-bottom: 20px;
}
.logo img {
    max-width: 180px;
}
.details {
    background: #2b2b2b;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    font-size: 14px;
}
.details p {
    margin: 8px 0;
}
.details span {
    font-weight: bold;
    color: #fff;
}
.payment-methods {
    margin-top: 20px;
}
.payment-methods label {
    display: block;
    position: relative;
    padding-left: 35px;
    margin-bottom: 15px;
    cursor: pointer;
    font-size: 16px;
    color: #ccc;
    user-select: none;
}
.payment-methods input[type="radio"] {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}
.payment-methods .checkmark {
    position: absolute;
    top: 1px;
    left: 0;
    height: 20px;
    width: 20px;
    background-color: #444;
    border-radius: 50%;
    transition: 0.3s ease;
}
.payment-methods label:hover input ~ .checkmark {
    background-color: #666;
}
.payment-methods label input:checked ~ .checkmark {
    background-color: red;
    box-shadow: 0 0 0 2px #fff inset;
}
.checkmark:after {
    content: "";
    position: absolute;
    display: none;
}
.payment-methods label input:checked ~ .checkmark:after {
    display: block;
}
.payment-methods label .checkmark:after {
    top: 5px;
    left: 5px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: white;
}
button {
    margin-top: 20px;
    width: 100%;
    padding: 12px;
    font-size: 16px;
    font-weight: bold;
    background: #d4af37;
    color: #000;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.3s ease;
}
button:hover {
    background: #fff;
}
.back-link {
    text-align: center;
    margin-top: 20px;
}
.back-link a {
    color: #d4af37;
    text-decoration: none;
    font-weight: bold;
}
  </style>
</head>
<body>

<div class="container">
    <div class="logo">
        <img src="assets/images/logo.svg" alt="Royal Enfield">
    </div>

    <h2>Payment Options</h2>

    <div class="details">
        <p>Bike: <span><?= htmlspecialchars($booking['bike']) ?></span></p>
        <p>Pickup: <span><?= htmlspecialchars($booking['pickup_date']) ?></span></p>
        <p>Return: <span><?= htmlspecialchars($booking['return_date']) ?></span></p>
        <p>Days: <span><?= $booking['total_days'] ?></span></p>
        <p>Total Cost: ₹<span><?= $booking['total_cost'] ?></span></p>
        <hr>
        <p>Mobile: <span><?= htmlspecialchars($booking['mobile']) ?></span></p>
    </div>

    <form action="finalize-booking.php" method="post">
        <!-- Hidden inputs to pass all booking data -->
        <input type="hidden" name="bike_id" value="<?= htmlspecialchars($booking['bike_id']) ?>">
        <input type="hidden" name="bike" value="<?= htmlspecialchars($booking['bike']) ?>">
        <input type="hidden" name="pickup_date" value="<?= htmlspecialchars($booking['pickup_date']) ?>">
        <input type="hidden" name="return_date" value="<?= htmlspecialchars($booking['return_date']) ?>">
        <input type="hidden" name="total_days" value="<?= htmlspecialchars($booking['total_days']) ?>">
        <input type="hidden" name="total_cost" value="<?= htmlspecialchars($booking['total_cost']) ?>">
    
        <input type="hidden" name="mobile" value="<?= htmlspecialchars($booking['mobile']) ?>">
        <input type="hidden" name="aadhaar" value="<?= htmlspecialchars($booking['aadhaar']) ?>">
        <input type="hidden" name="pickup_location" value="<?= htmlspecialchars($booking['pickup_location']) ?>">
        <input type="hidden" name="fuel_given" value="<?= htmlspecialchars($booking['fuel_given']) ?>">
        <input type="hidden" name="pre_payment_amount" value="<?= htmlspecialchars($booking['pre_payment_amount']) ?>">

        <div class="payment-methods">
            <label>
                <input type="radio" name="payment_method" value="UPI" required>
                <span class="checkmark"></span>
                UPI
            </label>
            <label>
                <input type="radio" name="payment_method" value="Credit/Debit Card">
                <span class="checkmark"></span>
                Credit/Debit Card
            </label>
            <label>
                <input type="radio" name="payment_method" value="Net Banking">
                <span class="checkmark"></span>
                Net Banking
            </label>
            <label>
                <input type="radio" name="payment_method" value="Pay on Pickup">
                <span class="checkmark"></span>
                Pay on Pickup
            </label>
        </div>

        <button type="submit">Complete Payment</button>
    </form>

    <div class="back-link">
        <a href="rent.php?bike_id=<?= $booking['bike_id'] ?>"><i class="fas fa-arrow-left"></i> Back to Booking</a>
    </div>
</div>

</body>
</html>
