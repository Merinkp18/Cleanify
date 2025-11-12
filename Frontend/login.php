<?php
session_start();
include '../Backend/db.php';

if (isset($_POST['login'])) {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    $row = mysqli_fetch_assoc($result);

    if ($row && password_verify($password, $row['password'])) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        header("Location: home.php");
        exit;
    } else {
        echo "<script>alert('Email atau password salah!');</script>";
    }
}
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
	align-items: center;     /* bikin teks or sejajar vertikal */
	text-align: center;
	color: #6c757d;          /* warna teks abu-abu */
	margin: 15px 0;          /* jarak atas bawah */
	}

	.divider::before,
	.divider::after {
	content: "";
	flex: 1;                 /* garis kiri-kanan panjang otomatis */
	border-bottom: 1px solid #ddd;  /* garis abu-abu muda */
	}

	.divider:not(:empty)::before {
	margin-right: .75em;     /* jarak kanan teks */
	}

	.divider:not(:empty)::after {
	margin-left: .75em;      /* jarak kiri teks */
	}
  </style>
</head>
<body>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-4">
        <div class="card  p-4">
		  <img src="assets/logo.png" alt="Cleanify Logo" width="100" class="mb-1" style="display: block; margin: 0 auto;">
          <h3 class="text-center mb-4">Sign In to Cleanify</h3>
          <form method="POST">
            <div class="mb-3">
              <label>Username or email address</label>
              <input type="email" name="email" class="form-control" required>
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
