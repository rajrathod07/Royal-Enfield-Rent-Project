<?php
session_start();
include 'includes/db.php';

// Handle comment submission
if (isset($_POST['submit_comment'])) {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $comment = mysqli_real_escape_string($conn, $_POST['comment']);

        $sql = "INSERT INTO feedback (user_id, comment) VALUES ('$user_id', '$comment')";
        if(mysqli_query($conn, $sql)) {
            // Redirect to rides.php feedback section
            header("Location: rides.php#feedbackSection");
            exit();
        }
    } else {
        header("Location: user/auth.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Royal Enfield | Motorcycles</title>
    <link rel="stylesheet" href="css/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
  .bike-card {
    background: #0e0e0e;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    transition: transform 0.3s ease;
  }

  .bike-card:hover {
    transform: translateY(-5px);
  }

  .bike-info {
    padding: 20px;
  }

  .bike-info h3 {
    margin: 0 0 12px;
    font-size: 1.4rem;
    color: #fff;
    border-bottom: 1px solid #333;
    padding-bottom: 8px;
  }

  .bike-details {
    font-size: 0.95rem;
    color: #ccc;
  }

  .bike-details ul {
    padding-left: 0;
    list-style: none;
  }

  .bike-details li {
    margin: 8px 0;
    display: flex;
    align-items: center;
  }

  .bike-details i {
    color: #d4af37;
    margin-right: 10px;
    width: 18px;
    text-align: center;
  }

  .bike-info-buttons {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 18px;
    padding-top: 12px;
    border-top: 1px solid #333;
  }

  .price-per-day {
    color: #fff;
    font-size: 1rem;
    font-weight: 600;
  }

  .rent-btn {
    padding: 10px 20px;
    font-size: 0.9rem;
    font-weight: bold;
    border-radius: 4px;
    text-decoration: none;
    transition: all 0.3s ease;
    background: #d4af37;
    color: #000;
    border: none;
  }

  .rent-btn:hover {
    background: #fff;
    color: #000;
  }

  .rent-btn.disabled {
    background: #555;
    color: #ccc;
    pointer-events: none;
  }
</style>
</head>
<body>
    <!-- Header -->
    <header class="main-header">
        <div class="logo">
            <a href="index.php"> 
                <img src="assets/images/logo.svg" alt="Royal Enfield" />
            </a>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="rides.php">Rides</a></li>
                <li><a href="user/booking-check.php">Bookings</a></li>
                <li><a href="about.html">About Us</a></li>
            </ul>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero-section" style="height: 400px;">
        <div class="hero-overlay"></div>
        <div class="hero-text" data-aos="fade-up">
            <h1>Our <span class="gold">Motorcycles</span></h1>
            <p>Explore iconic machines built to inspire your journey.</p>
        </div>
        <div class="hero-slider">
            <img src="assets/images/banner4.jpg" class="hero-slide active">
        </div>
    </section>

    <!-- Bike Models Grid -->
    <section class="popular-bikes">
        <h2 data-aos="fade-right">Choose Your Ride</h2>
        <div class="bike-grid">
        <?php
        include 'includes/db.php';
        $bikes = $conn->query("SELECT * FROM bikes ORDER BY bike_id ASC");
        while ($bike = $bikes->fetch_assoc()):
        ?>
            <div class="bike-card" data-aos="fade-up">
                <img src="<?php echo $bike['image']; ?>" alt="<?php echo $bike['name']; ?>">
                <div class="bike-info">
                    <h3><?php echo $bike['name']; ?></h3>
                    <div class="bike-details">
                        <ul>
                            <li><i class="fas fa-gas-pump"></i> Engine: <?php echo $bike['engine']; ?></li>
                            <li><i class="fas fa-road"></i> Mileage: <?php echo $bike['mileage']; ?></li>
                            <li><i class="fas fa-cogs"></i> Gearbox: <?php echo $bike['gearbox']; ?></li>
                        </ul>
                    </div>
                    <div class="bike-info-buttons">
                        <span class="price-per-day">₹<?php echo $bike['price_per_day']; ?> / day</span>
                        
                        <?php if ($bike['availability'] == 1): ?>
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <a href="rent.php?bike_id=<?php echo $bike['bike_id']; ?>" class="rent-btn">Rent Now</a>
                            <?php else: ?>
                                <a href="user/auth.php?redirect=rent.php?bike_id=<?php echo $bike['bike_id']; ?>" class="rent-btn">Rent Now</a>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="#" class="rent-btn disabled">Not Available</a>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        <?php endwhile; ?>
        </div>
    </section>


<!-- Footer Section -->
<footer class="main-footer">
  <div class="footer-content">
    
    <!-- Logo & About -->
    <div class="footer-about">
      <img src="assets/images/logo.svg" alt="Royal Enfield" class="footer-logo">
      <p>Ride with style, heritage, and adventure. Join the Royal Enfield community today.</p>
      <div class="social-icons">
        <a href="#"><img src="assets/icons/facebook.png" alt="Facebook"></a>
        <a href="#"><img src="assets/icons/instagram.png" alt="Instagram"></a>
        <a href="#"><img src="assets/icons/youtube.png" alt="YouTube"></a>
      </div>
    </div>

    <!-- Contact -->
    <div class="footer-contact">
      <h4>Contact Us</h4>
      <p>Email: support@royalenfield.com</p>
      <p>Phone: +91 9876543210</p>
      <p>Address: 123 Rider Street, Porbandar, India</p>
    </div>

    <!-- Feedback -->
<div class="footer-comment-box">
  <h4>Share Your Experience</h4>
  <?php if (isset($_SESSION['user_id'])) { ?>
    <form method="POST" class="comment-form">
      <textarea class="comment-input" name="comment" placeholder="Write your feedback..." required></textarea>
      <button class="comment-submit" type="submit" name="submit_comment">Send</button>
    </form>
  <?php } else { ?>
    <p style="color:red;">You must <a href="user/auth.php">login</a> to leave a comment.</p>
  <?php } ?>
</div>

  </div>

  <div class="footer-bottom">
    <p>&copy; 2025 Royal Enfield Rent. All Rights Reserved.</p>
  </div>
        <div class="footer-feedback-shortcut">
  <a href="rides.php#feedbackSection" class="hero-comment-btn">View Feedback</a>
</div>
</footer>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>AOS.init();</script>
</body>
</html>
