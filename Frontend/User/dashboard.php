<?php
require __DIR__ . '/../../Backend/db.php';


$sql = "SELECT id, name, short_description, price, duration_minutes 
        FROM services 
        WHERE status = 'active'
        ORDER BY price ASC";

$result = $conn->query($sql);
$services = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>

<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// status login user
$isLoggedIn = isset($_SESSION['user_id'], $_SESSION['role']) && $_SESSION['role'] === 'user';

// sesuaikan kalau folder project beda dari /cleanify
$loginUrl = '/cleanify/Frontend/Akun/login.php';
// =====================
// REVIEWS (AMAN)
// =====================
$reviews = [];

try {
    // kalau tabel reviews ada
    $stmt = $conn->prepare("
      SELECT r.rating, r.comment, c.name AS customer_name
      FROM reviews r
      JOIN customers c ON c.id = r.customer_id
      ORDER BY r.id DESC
      LIMIT 6
    ");
    $stmt->execute();
    $res = $stmt->get_result();
    $reviews = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

} catch (mysqli_sql_exception $e) {
    // fallback: kalau tabel reviews belum ada → ambil customer aja
    $stmt = $conn->prepare("
      SELECT name AS customer_name
      FROM customers
      ORDER BY id DESC
      LIMIT 6
    ");
    $stmt->execute();
    $res = $stmt->get_result();
    $rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

    // bikin format mirip review biar UI kamu tetap tampil
    foreach ($rows as $r) {
        $reviews[] = [
            'customer_name' => $r['customer_name'] ?? 'Customer',
            'rating' => 5,
            'comment' => 'Pelayanannya cepat, rapi, dan hasilnya memuaskan.'
        ];
    }
}
// ====== AMBIL DATA REVIEWS (DB) ======
$reviews = [];
try {
    $stmt = $conn->prepare("
        SELECT 
            r.rating,
            r.review,
            r.created_at,
            c.name AS customer_name
        FROM reviews r
        JOIN customers c ON c.id = r.customer_id
        ORDER BY r.id DESC
        LIMIT 9
    ");
    $stmt->execute();
    $res = $stmt->get_result();
    $reviews = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
} catch (Throwable $e) {
    $reviews = [];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cleanify</title>
    <link rel="stylesheet" href="../style/style.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
      /* dropdown profile */
      .profile-wrap{position:relative; display:inline-block;}
      .profile-menu{
        position:absolute; right:0; top:110%;
        background:#fff; border:1px solid #eaeaea; border-radius:12px;
        min-width:180px; padding:8px; display:none;
        box-shadow:0 10px 20px rgba(0,0,0,.12);
        z-index:9999;
      }
      .profile-menu a{
        display:block; padding:10px 12px; border-radius:10px;
        text-decoration:none; color:#111;
      }
      .profile-menu a:hover{background:#f5f6fa;}
      .profile-menu.show{display:block;}

      /* FIX: teks section bawah biar gak ikut putih dari style.css */
      #services, #about, #reviews { color:#111; background:#fff; }
      #about { background:#f7f7f7; }
      #services h1, #about h2, #reviews h2 { margin:0 0 10px; }

            

      /* ===== FOOTER ===== */
      .cleanify-footer{
        background:#0b0b0b;
        color:#fff;
        padding:60px 40px 25px;
        margin-top:40px;
      }
      .cleanify-footer .footer-inner{
        max-width:1100px;
        margin:0 auto;
        display:grid;
        grid-template-columns: 1.2fr 0.8fr 1fr;
        gap:40px;
      }
      .cleanify-footer .footer-brand{
        font-size:32px;
        margin:0 0 10px;
        letter-spacing:1px;
      }
      .cleanify-footer p{
        margin:8px 0;
        color:#d7d7d7;
        line-height:1.5;
      }
      .cleanify-footer h4{
        margin:0 0 14px;
        font-size:18px;
      }
      .cleanify-footer .footer-col a{
        display:block;
        text-decoration:none;
        color:#d7d7d7;
        margin:10px 0;
      }
      .cleanify-footer .footer-col a:hover{
        color:#ffffff;
      }
      .cleanify-footer .footer-social{
        display:flex;
        gap:10px;
        margin-top:14px;
      }
      .cleanify-footer .footer-social a{
        width:42px;
        height:42px;
        border-radius:10px;
        display:flex;
        align-items:center;
        justify-content:center;
        background:#1a1a1a;
        color:#fff;
        text-decoration:none;
        font-weight:700;
      }
      .cleanify-footer .footer-newsletter{
        display:flex;
        gap:10px;
        margin-top:12px;
      }
      .cleanify-footer .footer-newsletter input{
        flex:1;
        padding:12px 14px;
        border-radius:10px;
        border:1px solid #333;
        background:#111;
        color:#fff;
        outline:none;
      }
      .cleanify-footer .footer-newsletter button{
        padding:12px 16px;
        border-radius:10px;
        border:none;
        cursor:pointer;
        background:#f2c14f;
        font-weight:700;
      }
      .cleanify-footer .footer-bottom{
        text-align:center;
        margin-top:40px;
        padding-top:18px;
        border-top:1px solid rgba(255,255,255,0.12);
        color:#d7d7d7;
      }
      @media (max-width: 900px){
        .cleanify-footer .footer-inner{ grid-template-columns: 1fr; }
      }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="nav-left">
            <img src="assets/logo2.png" class="logo">
            
        </div>

        <ul class="nav-links">
            <li><a href="#services">Services</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#reviews">Reviews</a></li>
        </ul>

        <?php if (!$isLoggedIn): ?>
            <a href="<?= htmlspecialchars($loginUrl) ?>" class="btn-login">Log In</a>
        <?php else: ?>
            <div class="profile-wrap">
                <a href="#" class="btn-login" id="profileBtn">Profile</a>
                <div class="profile-menu" id="profileMenu">
                    <a href="/cleanify/Frontend/User/profile.php">Edit Profile</a>
                    <a href="/cleanify/Backend/logout.php">Logout</a>
                </div>
            </div>
        <?php endif; ?>
    </nav>

    <!-- HERO SECTION -->
    <section class="hero" id="home">
        <div class="hero-overlay"></div>

        <div class="hero-content">
            <div class="badge">#1st Rated Cleaning Service in the City</div>

            <h1 class="hero-title">Experience the Joy<br>of a Spotless Home</h1>

            <p class="hero-text">
                Professional, reliable, and eco-friendly cleaning services<br>
                tailored to your lifestyle. Reclaim your weekends and let us<br>
                handle the mess.
            </p>

            <div class="hero-buttons">
                <a href="/cleanify/Frontend/User/booking.php" class="btn-primary" data-lock="1">Book Now</a>
                <a href="#services" class="btn-secondary" data-lock="1">View Services</a>
            </div>
        </div>
    </section>

    <!-- SECTION SERVICES -->
 <section id="services">
  <div class="services-container">

    <h1 class="services-title">
      Cleanify Cleaning Services
    </h1>

    <h2 class="services-subtitle">
      From cleaning rooms, sofas, to cleaning the AC, it's all available at Cleanify.
    </h2>

    <div class="services-grid">

      <div class="service-card">
        <img src="../User/assets/service/regular.png" alt="Regular Cleaning">
        <div class="service-content">
          <h3>Regular Cleaning</h3>
          <p>
            Layanan pembersihan rutin untuk menjaga rumah tetap bersih dan nyaman setiap hari.
          </p>
        </div>
      </div>

      <div class="service-card">
        <img src="../User/assets/service/deep.png" alt="Deep Cleaning">
        <div class="service-content">
          <h3>Deep Cleaning</h3>
          <p>
            Pembersihan menyeluruh hingga ke sudut tersembunyi untuk hasil maksimal.
          </p>
        </div>
      </div>

      <div class="service-card">
        <img src="../User/assets/service/premium.png" alt="Premium Cleaning">
        <div class="service-content">
          <h3>Premium Cleaning</h3>
          <p>
            Layanan eksklusif dengan standar tertinggi untuk rumah dan kantor Anda.
          </p>
        </div>
      </div>

    </div>
  </div>
</section>

 

    <!-- SECTION REVIEWS (FIX: ditutup rapi, gak ada tag nyangkut) -->
<section id="reviews">
  <div class="reviews-container">

    <h2 class="reviews-title">Guaranteed and Trusted Quality</h2>

    <div class="reviews-grid">

      <div class="review-card">
        
        <div class="review-avatar">NS</div>

        <h3 class="review-name">N*** S*****</h3>
        <div class="review-stars">★★★★★</div>

        <p class="review-text">
          “Pelayanannya cepat, bersih, dan sangat profesional. Rumah jadi kinclong!”
        </p>
        <img src="../User/assets/service/regular.png" alt="Regular Cleaning">

        <span class="review-quote">“</span>
      </div>
      
      <div class="review-card">
        <div class="review-avatar">SL</div>

        <h3 class="review-name">S*** L******</h3>
        <div class="review-stars">★★★★★</div>

        <p class="review-text">
          “Admin ramah, petugas datang tepat waktu. Recommended banget.”
        </p>
        <img src="../User/assets/service/premium.png" alt="Regular Cleaning">
        <span class="review-quote">“</span>
      </div>

      <div class="review-card">
        <div class="review-avatar">BP</div>

        <h3 class="review-name">B*** P****</h3>
        <div class="review-stars">★★★★☆</div>

        <p class="review-text">
          “Hasil bersih dan rapi, cuma agak lama sedikit. Overall puas.”
        </p>
        <img src="../User/assets/service/deep.png" alt="Regular Cleaning">
        <span class="review-quote">“</span>
      </div>

    </div>

  </div>
</section>


<section id="about" class="about-section">
  <div class="about-inner">
    <div class="about-image">
      <img src="../User/assets/service/premium.png" alt="Cleaner smiling and cleaning" />
    </div>

    <div class="about-text">
      <h2>About Us</h2>

      <p>
        At Cleanify, we believe a clean space is the foundation of comfort,
        productivity, and peace of mind. Founded with a passion for helping people
        live and work in healthier environments, Cleanify provides high-quality,
        reliable, and affordable cleaning services tailored to your needs.
      </p>

      <p>
        Our team consists of trained, trustworthy, and detail-oriented
        professionals who treat every home and workspace with care. Whether it's
        routine cleaning, deep cleaning, or special requests, we ensure each task
        is completed with precision and dedication.
      </p>

      <p class="about-note">
        At Cleanify, we don't just clean—we create spaces you'll love coming back to.
      </p>
    </div>
  </div>
</section>


<style>
  @media (max-width: 980px){
    #reviews > div > div{ grid-template-columns:repeat(2,1fr) !important; }
  }
  @media (max-width: 600px){
    #reviews > div > div{ grid-template-columns:1fr !important; }
  }
</style>




    <!-- FOOTER -->
    <footer class="cleanify-footer">
      <div class="footer-inner">

        <div class="footer-col">
          <h3 class="footer-brand">CLEANIFY</h3>
          <p>Professional cleaning services for your home & office.</p>
          <p><b>Phone:</b> +62 857-0311-61495</p>
          <p><b>Email:</b> cleanify@gmail.com</p>

          <div class="footer-social">
            <a href="#" aria-label="X">X</a>
            <a href="#" aria-label="Facebook">f</a>
            <a href="#" aria-label="Instagram">ig</a>
            <a href="#" aria-label="LinkedIn">in</a>
          </div>
        </div>

        <div class="footer-col">
          <h4>Useful Links</h4>
          <a href="#home">Home</a>
          <a href="#about">About</a>
          <a href="#services">Services</a>
          <a href="<?= htmlspecialchars($loginUrl) ?>">Login</a>
        </div>

        <div class="footer-col">
          <h4>Our Newsletter</h4>
          <p>Subscribe to our newsletter and receive the latest updates.</p>

          <form class="footer-newsletter" action="#" method="POST">
            <input type="email" name="email" placeholder="Enter your email" required>
            <button type="submit">Subscribe</button>
          </form>
        </div>

      </div>

      <div class="footer-bottom">
        © Copyright <b>CLEANIFY</b> All Rights Reserved
      </div>
    </footer>

    <script>
      const IS_LOGGED_IN = <?= $isLoggedIn ? 'true' : 'false' ?>;
      const LOGIN_URL = <?= json_encode($loginUrl) ?>;

      document.addEventListener('click', function(e){
        const a = e.target.closest('a');
        if (!a) return;

        const href = a.getAttribute('href') || '';

        // gate login hanya untuk yang punya data-lock
        if (a.hasAttribute('data-lock') && !IS_LOGGED_IN) {
          e.preventDefault();
          window.location.href = LOGIN_URL;
          return;
        }

        // smooth scroll untuk anchor
        if (href.startsWith('#')) {
          const el = document.querySelector(href);
          if (el) {
            e.preventDefault();
            el.scrollIntoView({behavior:'smooth', block:'start'});
          }
        }
      });

      // Dropdown profile
      const profileBtn = document.getElementById('profileBtn');
      const profileMenu = document.getElementById('profileMenu');
      if (profileBtn && profileMenu) {
        profileBtn.addEventListener('click', function(e){
          e.preventDefault();
          profileMenu.classList.toggle('show');
        });
        document.addEventListener('click', function(e){
          if (!e.target.closest('.profile-wrap')) profileMenu.classList.remove('show');
        });
      }
    </script>

</body>
</html>
