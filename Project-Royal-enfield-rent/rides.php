<?php
session_start(); // Start session at the very top
include 'includes/db.php';

if (isset($_POST['submit_comment']) && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    $sql_insert = "INSERT INTO feedback (user_id, comment, created_at) VALUES ('$user_id', '$comment', NOW())";
    if (mysqli_query($conn, $sql_insert)) {
        // Reload page and jump to feedback section
        header("Location: " . $_SERVER['PHP_SELF'] . "#feedbackSection");
        exit();
    } else {
        echo "<p style='color:red;'>Failed to submit comment.</p>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Royal Enfield | Rides</title>
  <link rel="stylesheet" href="css/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<style>
/* Hero Section */
.hero-section {
  background: url('assets/images/rides-banner.jpg') center/cover no-repeat;
  height: 60vh;
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
  position: relative;
}
.hero-section::after {
  content: '';
  position: absolute;
  inset: 0;
  background: rgba(0, 0, 0, 0.5);
}
.hero-section h1 {
  position: relative;
  font-size: 3rem;
  color: #fff;
  z-index: 1;
}

/* Rides Video Section */
.rides-video {
  padding: 60px 20px;
  text-align: center;
}
.rides-video h2 {
  font-size: 2.5rem;
  margin-bottom: 30px;
  border-left: 4px solid #d4af37;
  padding-left: 10px;
  text-align: left;
}
.video-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 30px;
  padding: 20px;
}
.video-item iframe {
  width: 100%;
  height: 220px;
  border-radius: 10px;
  border: none;
}

/* Bike Gallery Section */
.bike-gallery {
  padding: 60px 20px;
  background: #111;
  color: #fff;
  text-align: center;
}
.bike-gallery h2 {
  font-size: 2.5rem;
  margin-bottom: 30px;
  border-left: 4px solid #d4af37;
  padding-left: 10px;
  text-align: left;
}
.gallery-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 16px;
  padding: 0 10px;
}
.gallery-grid img {
  width: 100%;
  border-radius: 10px;
  transition: transform 0.3s ease;
}
.gallery-grid img:hover {
  transform: scale(1.05);
}

/* Feedback Section */
.feedback-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  justify-content: flex-start; /* align cards to left */
  padding: 0 10px;
}
.feedback-card {
  flex: 1 1 280px;
  max-width: 320px;
  background: #1a1a1a;
  border-radius: 10px;
  padding: 15px;
  color: #fff;
  text-align: left;
  box-shadow: 0 4px 8px rgba(0,0,0,0.3);
  transition: transform 0.3s ease;
}
.feedback-card:hover {
  transform: scale(1.03);
}
.user-info {
  display: flex;
  align-items: center;
  margin-bottom: 10px;
}
.user-info img {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  margin-right: 10px;
  object-fit: cover;
}
.user-info h4 {
  margin: 0;
  font-size: 1rem;
  color: #d4af37;
}
.comment-text {
  font-size: 0.95rem;
  line-height: 1.4;
}
.comment-date {
  font-size: 0.8rem;
  color: #aaa;
  margin-top: 8px;
  display: block;
}

/* Responsive */
@media (max-width: 768px) {
  .hero-section { height: 45vh; }
  .hero-section h1 { font-size: 2rem; }
  .rides-video h2, .bike-gallery h2 { font-size: 1.8rem; }
  .video-item iframe { height: 200px; }
  .feedback-grid { justify-content: center; }
  .footer-content-wrapper { flex-direction: column; }
  .footer-text { justify-content: flex-start; margin-top: 20px; }
}
@media (max-width: 480px) {
  .hero-section { height: 35vh; }
  .hero-section h1 { font-size: 1.4rem; }
  .rides-video h2, .bike-gallery h2 { font-size: 1.4rem; }
  .video-item iframe { height: 180px; }
  .footer-comment-box { max-width: 100%; }
}
</style>
</head>

<body>

<!-- Header -->
<header class="main-header">
  <div class="logo">
    <a href="index.php"><img src="assets/images/logo.svg" alt="Royal Enfield" /></a>
  </div>
  <nav>
    <ul>
      <li><a href="index.php">Home</a></li>
      <li><a href="motorcycles.php">Motorcycles</a></li>
      <li><a href="user/booking-check.php">Bookings</a></li>
      <li><a href="about.html">About Us</a></li>
    </ul>
  </nav>
</header>

<section class="hero-section">
  <div class="hero-slider">
    <img src="assets/images/banner3.jpg" class="slide active" alt="Banner 1">
  </div>
  <div class="hero-overlay"></div>
  <h1>Ride. Record. Relive.</h1>
</section>

<!-- Video Section -->
<section class="rides-video">
  <h2>Ride Stories & Adventures</h2>
  <div class="video-grid">
    <div class="video-item">
      <iframe src="https://www.youtube.com/embed/AG0rkpICoiw?si=ACoi5UDvb7J0QB5r" title="YouTube video player" frameborder="0" allowfullscreen></iframe>
    </div>
    <div class="video-item">
      <iframe src="https://www.youtube.com/embed/wTPPVvZd6qQ?si=S8ZPEejCY33vat5I" title="YouTube video player" frameborder="0" allowfullscreen></iframe>
    </div>
    <div class="video-item">
      <iframe src="https://www.youtube.com/embed/keVoECYNGg4?si=NkqRWDFAuImarO11" title="YouTube video player" frameborder="0" allowfullscreen></iframe>
    </div>
    <div class="video-item">
      <iframe src="https://www.youtube.com/embed/E36p4wuFE60?si=9CxsYtA8GqDsOOtp" title="YouTube video player" frameborder="0" allowfullscreen></iframe>
    </div>
  </div>
</section>

<!-- Gallery Section -->
<section class="bike-gallery">
  <h2>Royal Enfield Bike Gallery</h2>
  <div class="gallery-grid">
    <?php for ($i=1;$i<=15;$i++): ?>
      <img src="assets/images/bike-gallery/gallery<?= $i ?>.jpg" alt="Bike <?= $i ?>">
    <?php endfor; ?>
  </div>
</section>

<!-- Rider Feedback Section -->
<section class="bike-gallery" id="feedbackSection">
  <h2>Rider Feedback</h2>
  <div class="feedback-grid" id="feedbackContainer">
<?php
$pinned_ids = [1, 2];

$sql = "SELECT f.id, f.comment, f.created_at, u.name, u.profile_img
        FROM feedback f
        JOIN users u ON f.user_id = u.user_id
        ORDER BY f.created_at DESC";
$result = mysqli_query($conn, $sql);

$comments = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $comments[] = $row;
    }
}

$pinned_comments = [];
$normal_comments = [];
foreach ($comments as $c) {
    if (in_array($c['id'], $pinned_ids)) {
        $pinned_comments[] = $c;
    } else {
        $normal_comments[] = $c;
    }
}
$all_comments = array_merge($pinned_comments, $normal_comments);

$firstBatch = array_slice($all_comments, 0, 8);
foreach ($firstBatch as $row) { ?>
    <div class="feedback-card">
        <div class="user-info">
            <img src="assets/users/<?php echo !empty($row['profile_img']) ? $row['profile_img'] : 'default.png'; ?>" alt="User">
            <h4>
              <?php echo htmlspecialchars($row['name']); ?>
              <?php if (in_array($row['id'], $pinned_ids)) { ?>
                <span style="color:#d4af37; font-size:0.8rem; margin-left:5px;">📌 Pinned</span>
              <?php } ?>
            </h4>
        </div>
        <p class="comment-text"><?php echo htmlspecialchars($row['comment']); ?></p>
        <span class="comment-date"><?php echo date("M d, Y", strtotime($row['created_at'])); ?></span>
    </div>
<?php } ?>
</div>

<?php if (count($all_comments) > 8) { ?>
  <div style="text-align:center; margin-top:20px;">
    <button id="loadMoreBtn" style="padding:8px 15px; border:none; background:#d4af37; color:#111; border-radius:5px; cursor:pointer;">See More</button>
  </div>
<?php } ?>
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
  
</footer>

<script>
  const allComments = <?php echo json_encode($all_comments); ?>;
  const pinnedIds = <?php echo json_encode($pinned_ids); ?>;
  let currentIndex = 8;

  document.getElementById("loadMoreBtn")?.addEventListener("click", function() {
    const container = document.getElementById("feedbackContainer");
    const nextBatch = allComments.slice(currentIndex, currentIndex + 8);

    nextBatch.forEach(row => {
      const card = document.createElement("div");
      card.className = "feedback-card";
      card.innerHTML = `
        <div class="user-info">
          <img src="assets/users/${row.profile_img || 'default.png'}" alt="User">
          <h4>${row.name}${pinnedIds.includes(parseInt(row.id)) ? '<span style="color:#d4af37; font-size:0.8rem; margin-left:5px;">📌 Pinned</span>' : ''}</h4>
        </div>
        <p class="comment-text">${row.comment}</p>
        <span class="comment-date">${new Date(row.created_at).toLocaleDateString()}</span>
      `;
      container.appendChild(card);
    });

    currentIndex += 8;
    if (currentIndex >= allComments.length) {
      document.getElementById("loadMoreBtn").style.display = "none";
    }
  });
</script>

</body>
</html>
