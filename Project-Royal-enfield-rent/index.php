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
  <title>Royal Enfield | Rent Home</title>
  <link rel="stylesheet" href="css/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />
</head>

<body>

  <header class="main-header">
    <div class="logo"><img src="assets/images/logo.svg" alt="Royal Enfield"></div>
    <nav>
      <ul>
        <li><a href="motorcycles.php">Motorcycles</a></li>
        <li><a href="rides.php">Rides</a></li>
        <li><a href="user/booking-check.php">Bookings</a></li>
        <li><a href="about.html">About Us</a></li>
      </ul>
    </nav>
  </header>

  <section class="hero-section">
    <div class="hero-overlay"></div>
    <div class="hero-text" data-aos="fade-up">
      <h1>Book Your <span class="gold">Royal Enfield</span> Ride</h1>
      <p>Explore the legacy. Ride the legend.</p>
      <a href="motorcycles.php" class="hero-btn">Explore Bikes</a>
    </div>
    <div class="hero-slider">
      <img src="assets/images/banner1.jpg" class="hero-slide active">
      <img src="assets/images/banner2.jpg" class="hero-slide">
      <img src="assets/images/banner3.jpg" class="hero-slide">
      <img src="assets/images/banner4.jpg" class="hero-slide">
    </div>
  </section>

  <section class="popular-bikes">
    <h2 data-aos="fade-right">Popular Bikes</h2>
    <div class="bike-grid">
      <div class="bike-card" data-aos="fade-up">
        <img src="assets/images/bikes/classic350.jpg" alt="Classic 350">
        <div class="bike-info">
          <h3>Classic 350</h3>
          <a href="motorcycles.php" class="explore-btn">Explore</a>
        </div>
      </div>
      <div class="bike-card" data-aos="fade-up" data-aos-delay="100">
        <img src="assets/images/bikes/hunter350.jpg" alt="Hunter 350">
        <div class="bike-info">
          <h3>Hunter 350</h3>
          <a href="motorcycles.php" class="explore-btn">Explore</a>
        </div>
      </div>
      <div class="bike-card" data-aos="fade-up" data-aos-delay="200">
        <img src="assets/images/bikes/interceptor1.jpg" alt="Interceptor 650">
        <div class="bike-info">
          <h3>Interceptor 650</h3>
          <a href="motorcycles.php" class="explore-btn">Explore</a>
        </div>
      </div>
    </div>
  </section>

  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script src="js/script.js"></script>


  <section class="rides-video" data-aos="fade-up">
    <div class="ride-grid">
      <div class="ride-left">
        <h2>Ride Stories</h2>
        <div class="video-container">
          <iframe  width="560" height="315" src="https://www.youtube.com/embed/RweMCoBW-KM?si=RYDZVPQRigWzbsPe"
            title="YouTube video player" frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        </div>
      </div>
      <div class="ride-right">
        <p class="ride-description">
          <span class="gold-text">Explore inspiring journeys</span> from Royal Enfield riders across the world.<br><br>
          Every ride is a story, <span class="highlight">every mile a memory</span>.<br><br>
          <span class="ride-quote">Feel the thrill. Live the ride. Own the road.</span><br><br>
          From Himalayan peaks to coastal highways, our riders conquer terrains with <span
            class="highlight">courage</span> and <span class="highlight">style</span>.<br><br>
          <span class="gold-text">This isn't just a motorcycle — it's a legacy on two wheels.</span><br><br>
          <strong class="ride-tagline">Be bold. Be limitless. Ride Royal.</strong>
        </p>
      </div>

    </div>
  </section>

  <!-- Testimonials Section -->
  <section class="testimonials" data-aos="fade-up">
    <h2>What Riders Say</h2>
    <div class="testimonial-grid">
      <div class="testimonial">
        <p>"Riding the Interceptor 650 was the best decision I ever made."</p>
        <h4>- Arjun Mehta</h4>
      </div>
      <div class="testimonial">
        <p>"Royal Enfield brings heritage and modern design together beautifully."</p>
        <h4>- Sarah Thomas</h4>
      </div>
    </div>
  </section>

  <!-- Gallery Section -->
  <section class="bike-gallery" data-aos="fade-up">
    <h2>Bike Gallery</h2>
    <div class="gallery-grid">
      <img src="assets/images/bikes/classic350.jpg" alt="Classic 350">
      <img src="assets/images/bikes/hunter350.jpg" alt="Hunter 350">
      <img src="assets/images/bikes/interceptor.jpg" alt="Interceptor 650">
      <img src="assets/images/bikes/scarm411.jpg" alt="Scram 411">
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
</body>

</html>
