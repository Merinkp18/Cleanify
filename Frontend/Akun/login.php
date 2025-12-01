<?php
session_start();
$login_error = $_SESSION['login_error'] ?? null;
unset($_SESSION['login_error']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | Cleanify</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .card {
      border-radius: 10px;
      border: none;
    }
    .btn-google {
      background-color: #fff;
      border: 1px solid #ddd;
      color: #555;
    }
    .btn-google:hover {
      background-color: #f1f1f1;
    }
    .btn-apple {
      background-color: #fff;
      border: 1px solid #ddd;
      color: #555;
    }
    .btn-apple:hover {
      background-color: #f1f1f1;   
    }
    .btn-apple img {
      filter: none; 
    }
    .divider {
      display: flex;
      align-items: center;
      text-align: center;
      color: #6c757d;
      margin: 15px 0;
    }
    .divider::before,
    .divider::after {
      content: "";
      flex: 1;
      border-bottom: 1px solid #ddd;
    }
    .divider:not(:empty)::before {
      margin-right: .75em;
    }
    .divider:not(:empty)::after {
      margin-left: .75em;
    }
  </style>
</head>
<body>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-4">
        <div class="card p-4">
          <img src="assets/logo3.png" alt="Cleanify Logo" width="100" class="mb-1" style="display: block; margin: 0 auto;">
          <h3 class="text-center mb-4">Sign In to Cleanify</h3>

          <?php if ($login_error): ?>
            <div class="alert alert-danger py-2">
              <?= htmlspecialchars($login_error, ENT_QUOTES, 'UTF-8'); ?>
            </div>
          <?php endif; ?>

          <!-- SATU form saja, langsung ke Backend -->
          <form method="POST" action="../../Backend/login_proccess.php">
            <div class="mb-3">
              <label>Username or email address</label>
              <!-- type=text biar username tanpa @ bisa -->
              <input type="text" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
              <label>Password</label>
              <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary w-100 mb-2">Sign In</button>

            <div class="divider mb-4">or</div>

            <div class="d-grid gap-2">
              <button type="button" class="btn btn-google w-100">
                <img src="https://cdn-icons-png.flaticon.com/512/281/281764.png" width="20" class="me-2">
                Continue with Google
              </button>
              <button type="button" class="btn btn-apple w-100">
                <img src="https://cdn-icons-png.flaticon.com/512/179/179309.png" width="25" class="me-2">
                Continue with Apple
              </button>
            </div>

            <p class="text-center mt-4 mb-0">
              New to Cleanify? <a href="register.php">Create an account</a>
            </p>
          </form>

        </div>
      </div>
    </div>
  </div>
</body>
</html>
