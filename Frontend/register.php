<?php
include 'db.php';

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = $_POST['password'];

    // === VALIDASI EMAIL ===
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Format email tidak valid! Harus mengandung @');</script>";
    }
    // === VALIDASI PASSWORD ===
    elseif (strlen($password) < 8) {
        echo "<script>alert('Password minimal 8 karakter!');</script>";
    }
    elseif (!preg_match('/[a-z]/', $password)) {
        echo "<script>alert('Password harus mengandung huruf kecil!');</script>";
    }
    else {
        // Cek apakah email sudah terdaftar
        $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
        if (mysqli_num_rows($check) > 0) {
            echo "<script>alert('Email sudah terdaftar, silakan login atau gunakan email lain!');</script>";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
            
            if (mysqli_query($conn, $query)) {
                echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location='login.php';</script>";
                exit;
            } else {
                echo "<script>alert('Registrasi gagal!');</script>";
            }
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register | Cleanify</title>
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
<body class="bg-light">
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-4">
        <div class="card  p-4">
		  <img src="assets/logo.png" alt="Cleanify Logo" width="100" class="mb-1" style="display: block; margin: 0 auto;">
          <h3 class="text-center mb-5">Sign up for Cleanify</h3>
          <form method="POST">
			<div class="d-grid gap-2">
              <button type="button" class="btn btn-google w-100">
                <img src="https://cdn-icons-png.flaticon.com/512/281/281764.png" width="20" class="me-2">
                Continue with Google
              </button>
              <button type="button" class="btn btn-apple w-100 mb-2">
                <img src="https://cdn-icons-png.flaticon.com/512/179/179309.png" width="25" class="me-2">
                Continue with Apple
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
				Username may only contain alphanumeric characters or single hyphens, and cannot begin or and with a hyphen.
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
            <p class="text-center mt-3">Already have an account? <a href="login.php">Sign In</a></p>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
