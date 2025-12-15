<?php
require __DIR__ . '/../../Backend/db.php';

if (session_status() === PHP_SESSION_NONE) session_start();

// status login user
$isLoggedIn = isset($_SESSION['user_id'], $_SESSION['role']) && $_SESSION['role'] === 'user';

// sesuaikan kalau folder project beda dari /cleanify
$loginUrl = '/cleanify/Frontend/Akun/login.php';

// =====================
// SERVICES (DB)
// =====================
$sql = "SELECT id, name, short_description, price, duration_minutes
        FROM services
        WHERE status = 'active'
        ORDER BY price ASC";
$result = $conn->query($sql);
$services = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

// =====================
// USER DATA NAVBAR (WELCOME + AVATAR)
// =====================
$userName  = $_SESSION['user_name'] ?? '';
$userPhoto = $_SESSION['user_photo'] ?? '';

if ($isLoggedIn && $userName === '') {
    $uid = (int)($_SESSION['user_id'] ?? 0);

    // coba cari di customers dulu
    try {
        $st = $conn->prepare("SELECT name FROM customers WHERE id = ? LIMIT 1");
        $st->bind_param("i", $uid);
        $st->execute();
        $rs = $st->get_result();
        if ($rs && $rs->num_rows > 0) {
            $row = $rs->fetch_assoc();
            $userName = $row['name'] ?? '';
        }
    } catch (Throwable $e) {}

    // fallback: coba di users (kalau tabel users ada)
    if ($userName === '') {
        try {
            $st = $conn->prepare("SELECT name FROM users WHERE id = ? LIMIT 1");
            $st->bind_param("i", $uid);
            $st->execute();
            $rs = $st->get_result();
            if ($rs && $rs->num_rows > 0) {
                $row = $rs->fetch_assoc();
                $userName = $row['name'] ?? '';
            }
        } catch (Throwable $e) {}
    }

    if ($userName === '') $userName = 'User';
}

// =====================
// REVIEWS (DB) + FALLBACK
// =====================
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
    // fallback kalau tabel reviews belum ada
    $stmt = $conn->prepare("
        SELECT name AS customer_name
        FROM customers
        ORDER BY id DESC
        LIMIT 9
    ");
    $stmt->execute();
    $res = $stmt->get_result();
    $rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

    $fallbackTexts = [
        'Pelayanannya cepat dan hasilnya rapi banget.',
        'Cleaner-nya sopan, datang tepat waktu, recommended.',
        'Rumah jadi wangi dan bersih sampai detail kecil.',
        'Booking mudah, admin responsif, hasil memuaskan.',
        'Kerjanya teliti, sudut-sudut juga ikut bersih.',
        'Cocok buat yang sibuk, tinggal tunggu beres.'
    ];

    foreach ($rows as $i => $r) {
        $reviews[] = [
            'customer_name' => $r['customer_name'] ?? 'Customer',
            'rating' => 5,
            'review' => $fallbackTexts[$i % count($fallbackTexts)],
            'created_at' => null
        ];
    }
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

      /* welcome + avatar bulat */
      .profile-inline{display:flex;align-items:center;gap:10px;}
      .welcome-name{color:#fff;font-weight:600;font-size:14px;white-space:nowrap;}
      .profile-avatar{
        width:42px;height:42px;border-radius:50%;
        display:inline-flex;align-items:center;justify-content:center;
        overflow:hidden;border:2px solid rgba(255,255,255,.7);
        box-shadow:0 8px 18px rgba(0,0,0,.15);
        background: rgba(255,255,255,.15);
        cursor:pointer;
      }
      .profile-avatar img{width:100%;height:100%;object-fit:cover;display:block;}
      .profile-initial{color:#fff;font-weight:800;font-size:14px;}

      /* FIX: teks section bawah biar gak ikut putih dari style.css */
      #services, #about, #reviews { color:#111; background:#fff; }
      #about { background:#f7f7f7; }

      /* FOOTER */
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
      .cleanify-footer .footer-col a:hover{ color:#fff; }
      .cleanify-footer .footer-social{ display:flex; gap:10px; margin-top:14px; }
      .cleanify-footer .footer-social a{
        width:42px;height:42px;border-radius:10px;
        display:flex;align-items:center;justify-content:center;
        background:#1a1a1a;color:#fff;text-decoration:none;font-weight:700;
      }
      .cleanify-footer .footer-newsletter{ display:flex; gap:10px; margin-top:12px; }
      .cleanify-footer .footer-newsletter input{
        flex:1;padding:12px 14px;border-radius:10px;border:1px solid #333;
        background:#111;color:#fff;outline:none;
      }
      .cleanify-footer .footer-newsletter button{
        padding:12px 16px;border-radius:10px;border:none;cursor:pointer;
        background:#f2c14f;font-weight:700;
      }
      .cleanify-footer .footer-bottom{
        text-align:center;margin-top:40px;padding-top:18px;
        border-top:1px solid rgba(255,255,255,0.12);color:#d7d7d7;
      }
      @media (max-width: 900px){
        .cleanify-footer .footer-inner{ grid-template-columns: 1fr; }
      }

      /* responsive reviews grid */
      @media (max-width: 980px){
        #reviews .review-grid{ grid-template-columns:repeat(2,1fr) !important; }
      }
      @media (max-width: 600px){
        #reviews .review-grid{ grid-template-columns:1fr !important; }
      }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="nav-left">
            <img src="assets/logo3.png" class="logo">
            <span class="brand">CLEANIFY</span>
        </div>

        <ul class="nav-links">
            <li><a href="#services">Services</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#reviews">Reviews</a></li>
        </ul>

        <?php if (!$isLoggedIn): ?>
            <a href="<?= htmlspecialchars($loginUrl) ?>" class="btn-login">Log In</a>
        <?php else: ?>
            <?php
              $initials = strtoupper(mb_substr(trim($userName),0,1));
              $photoUrl = $userPhoto ? "/cleanify/" . ltrim($userPhoto, "/") : "";
            ?>
            <div class="profile-wrap">
              <div class="profile-inline">
                <span class="welcome-name">Welcome, <?= htmlspecialchars($userName) ?></span>

                <a href="#" id="profileBtn" class="profile-avatar" aria-label="Profile">
                  <?php if ($photoUrl): ?>
                    <img src="<?= htmlspecialchars($photoUrl) ?>" alt="Profile">
                  <?php else: ?>
                    <span class="profile-initial"><?= htmlspecialchars($initials) ?></span>
                  <?php endif; ?>
                </a>
              </div>

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
    <section id="services" style="padding:100px 40px; background:#f8f9fa;">
      <div style="max-width:1200px;margin:auto;">
        <h2 style="text-align:center;color:#111;margin-bottom:50px;font-size:40px;font-weight:800;">Layanan Kami</h2>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:30px;">
          <?php foreach ($services as $s): ?>
            <div style="background:#fff;border-radius:18px;padding:30px;box-shadow:0 15px 30px rgba(0,0,0,.08);">
              <h3 style="margin:0 0 10px;color:#111;font-size:22px;font-weight:800;">
                <?= htmlspecialchars($s['name']) ?>
              </h3>

              <p style="color:#666;min-height:48px;line-height:1.6;">
                <?= htmlspecialchars($s['short_description']) ?>
              </p>

              <div style="margin:18px 0;color:#111;font-weight:600;">
                ⏱ <?= (int)$s['duration_minutes'] ?> menit
              </div>

              <div style="font-size:20px;font-weight:900;color:#007bce;">
                Rp <?= number_format((float)$s['price'],0,',','.') ?>
              </div>

              <a
                href="/cleanify/Frontend/User/booking.php?service_id=<?= (int)$s['id'] ?>"
                class="btn-primary"
                data-lock="1"
                style="display:inline-block;margin-top:20px;"
              >
                Pesan Sekarang
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- SECTION ABOUT -->
    <section id="about" style="padding:100px 40px; background:#f3f6fb;">
      <div style="max-width:1100px;margin:auto;">

        <div style="text-align:center;margin-bottom:18px;">
          <div style="width:120px;height:120px;margin:0 auto 10px;border-radius:28px;background:#e8f1ff;display:flex;align-items:center;justify-content:center;font-size:46px;color:#007bce;font-weight:800;">✨</div>

          <p style="max-width:820px;margin:0 auto;color:#3b4a5a;font-size:18px;line-height:1.7;">
            Apakah Anda lelah melakukan pekerjaan rumah tangga? Apakah Anda merasa stres seperti tidak punya cukup waktu
            dalam sehari? Apakah rumah atau tempat kerja Anda terasa berantakan dan kotor? Jangan khawatir.
          </p>
        </div>

        <h2 style="text-align:center;color:#0f1f33;font-size:36px;margin:28px 0 22px;font-weight:900;">
          Cleanify memiliki solusi untuk Anda.
        </h2>

        <div style="max-width:860px;margin:0 auto;">
          <?php
            $aboutPoints = [
              "Kami akan membantu membersihkan ruang Anda — dapur, kamar tidur, kamar mandi, ruang kantor, dan banyak lainnya — persis seperti yang Anda inginkan!",
              "Pilih layanan pembersihan satu kali atau reguler — apa pun yang terbaik untuk Anda!",
              "Kami sangat peduli dengan kualitas pembersihan — jika karena alasan apa pun Anda tidak puas, Anda dapat menghubungi customer service kami pada jam operasional.",
              "Biarkan kami yang melakukan pembersihan untuk Anda sehingga Anda bebas menghabiskan waktu dengan keluarga tercinta!"
            ];
          ?>
          <?php foreach ($aboutPoints as $t): ?>
            <div style="display:flex;gap:14px;align-items:flex-start;margin:14px 0;">
              <div style="width:34px;height:34px;border-radius:50%;background:#e7f7ff;color:#007bce;display:flex;align-items:center;justify-content:center;font-weight:900;flex:0 0 34px;">✓</div>
              <p style="margin:0;color:#3b4a5a;line-height:1.8;"><?= htmlspecialchars($t) ?></p>
            </div>
          <?php endforeach; ?>
        </div>

      </div>
    </section>

    <!-- SECTION REVIEWS -->
    <section id="reviews" style="padding:90px 40px; background:#eef4f7;">
      <div style="max-width:1100px;margin:0 auto;">
        <h2 style="text-align:center;color:#243b53;font-size:42px;margin:0 0 40px;font-weight:900;">
          What Our Customers Say!
        </h2>

        <?php if (!empty($reviews)): ?>
          <div class="review-grid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;align-items:stretch;">
            <?php foreach ($reviews as $r): ?>
              <?php
                $name = trim((string)($r['customer_name'] ?? 'Customer'));
                $reviewText = trim((string)($r['review'] ?? ''));
                $rating = (int)($r['rating'] ?? 5);
                if ($rating < 1) $rating = 1;
                if ($rating > 5) $rating = 5;

                $parts = preg_split('/\s+/', $name);
                $first = $parts[0] ?? 'C';
                $last  = $parts[count($parts)-1] ?? '';
                $initials = strtoupper(mb_substr($first,0,1) . ($last ? mb_substr($last,0,1) : ''));

                $hash = crc32($name);
                $hue = $hash % 360;
                $avatarBg = "hsl($hue, 70%, 55%)";

                $stars = str_repeat("★", $rating) . str_repeat("☆", 5-$rating);
              ?>

              <div style="background:#fff;border-radius:18px;box-shadow:0 12px 30px rgba(0,0,0,.08);padding:28px;padding-top:64px;position:relative;overflow:visible;">
                <div style="width:78px;height:78px;border-radius:50%;background:#fff;position:absolute;left:26px;top:-34px;display:flex;align-items:center;justify-content:center;box-shadow:0 10px 25px rgba(0,0,0,.12);">
                  <div style="width:64px;height:64px;border-radius:50%;background:<?= htmlspecialchars($avatarBg) ?>;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:900;font-size:20px;">
                    <?= htmlspecialchars($initials) ?>
                  </div>
                </div>

                <div style="font-size:24px;font-weight:900;color:#1f2d3d;"><?= htmlspecialchars($name) ?></div>
                <div style="margin-top:6px;color:#2c3e50;letter-spacing:2px;"><?= htmlspecialchars($stars) ?></div>

                <p style="margin-top:18px;color:#334e68;font-size:18px;line-height:1.6;min-height:90px;">
                  “<?= htmlspecialchars($reviewText) ?>”
                </p>

                <div style="position:absolute;right:18px;bottom:10px;font-size:90px;line-height:1;color:rgba(36,59,83,.08);font-weight:900;">“</div>
              </div>

            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div style="text-align:center;color:#334e68;background:#fff;border-radius:16px;padding:30px;box-shadow:0 10px 25px rgba(0,0,0,.06);">
            Belum ada review.
          </div>
        <?php endif; ?>
      </div>
    </section>

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
