
<?php
session_start();

// Ambil error & success jika ada
$register_error   = $_SESSION['register_error'] ?? null;
$register_success = $_SESSION['register_success'] ?? null;

// Hapus setelah ditampilkan
unset($_SESSION['register_error'], $_SESSION['register_success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register | Cleanify</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .card { border-radius: 10px; border: none; }
    .btn-google {
      background-color: #fff; 
      border: 1px solid #ddd; 
      color: #555;
    }
    .btn-google:hover  { background-color: #f1f1f1; }
    .divider {
      display: flex;
      align-items: center;
      text-align: center;
      color: #6c757d;
      margin: 15px 0;
    }
    .divider::before, .divider::after {
      content: "";
      flex: 1;
      border-bottom: 1px solid #ddd;
    }
    .divider:not(:empty)::before { margin-right: .75em; }
    .divider:not(:empty)::after { margin-left: .75em; }
  </style>
</head>

<body class="bg-light">
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-4">
        <div class="card  p-4">

          <img src="assets/logo3.png" alt="Cleanify Logo" width="100" class="mb-1" style="display: block; margin: 0 auto;">
          <h3 class="text-center mb-4">Sign up for Cleanify</h3>

          <!-- TAMPILKAN ERROR JIKA ADA -->
          <?php if ($register_error): ?>
              <div class="alert alert-danger"><?= htmlspecialchars($register_error) ?></div>
          <?php endif; ?>

          <!-- TAMPILKAN SUCCESS JIKA ADA -->
          <?php if ($register_success): ?>
              <div class="alert alert-success"><?= htmlspecialchars($register_success) ?></div>
          <?php endif; ?>

          <!-- FORM KIRIM KE BACKEND REGISTER_PROCCESS -->
          <form method="POST" action="../../Backend/register_proccess.php">

            <div class="d-grid gap-2">
              <button type="button" class="btn btn-google w-100">
                <img src="https://cdn-icons-png.flaticon.com/512/281/281764.png" width="20" class="me-2">
                Continue with Google
              </button>
              
            </div>

            <div class="divider mb-2">or</div>

            <div class="mb-3">
              <label>Email*</label>
              <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
              <label>Username*</label>
              <input type="text" name="username" class="form-control" required>
              <div style="font-size: 13px; color: #6c757d;">
                Username may only contain alphanumeric characters or single hyphens, and cannot begin or end with a hyphen.
              </div>
            </div>

            <div class="mb-3">
              <label>Password*</label>
              <input type="password" name="password" class="form-control" required>
              <div style="font-size: 13px; color: #6c757d;">
                Password should be at least 15 characters or at least 8 characters including a number and a lowercase letter.
              </div>
            </div>

            <button type="submit" name="register" class="btn btn-primary w-100">Create account</button>

            <p class="text-center mt-3">
              Already have an account? <a href="login.php">Sign In</a>
            </p>
          </form>

        </div>
      </div>
    </div>
  </div>
</body>
</html>
