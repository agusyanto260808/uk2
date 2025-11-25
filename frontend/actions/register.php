<?php
session_start();
include '../partials/header.php';
include __DIR__ . '../../../config/connection.php'; // path benar

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username   = trim($_POST['username']);
  $password   = trim($_POST['password']);
  $nama       = trim($_POST['nama_penumpang']);
  $alamat     = trim($_POST['alamat_penumpang']);
  $tgl_lahir  = $_POST['tanggal_lahir'];
  $jk         = $_POST['jenis_kelamin'];
  $telepon    = $_POST['telefone'];

  if ($username && $password && $nama) {
    // cek username sudah ada atau belum
    $cek = $conn->prepare("SELECT * FROM penumpang WHERE username=?");
    $cek->bind_param("s", $username);
    $cek->execute();
    $result = $cek->get_result();

    if ($result->num_rows > 0) {
      echo "<script>alert('Username sudah dipakai!');</script>";
    } else {
      $hashed = password_hash($password, PASSWORD_DEFAULT);
      $sql = "INSERT INTO penumpang (username, password, nama_penumpang, alamat_penumpang, tanggal_lahir, jenis_kelamin, telefone) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("sssssss", $username, $hashed, $nama, $alamat, $tgl_lahir, $jk, $telepon);

      if ($stmt->execute()) {
        echo "<script>alert('Registrasi berhasil! Silakan login.');window.location='login.php';</script>";
      } else {
        echo "<script>alert('Gagal registrasi!');</script>";
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Akun - Tiket Ajaib</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary-blue: #4361ee;
      --primary-light: #4895ef;
      --primary-dark: #3f37c9;
      --gradient-primary: linear-gradient(135deg, #4361ee 0%, #3f37c9 100%);
      --gradient-secondary: linear-gradient(135deg, #4cc9f0 0%, #4895ef 100%);
      --gradient-success: linear-gradient(135deg, #28a745, #20c997);
      --gradient-bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    body {
      background: var(--gradient-bg);
      min-height: 100vh;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      position: relative;
      overflow-x: hidden;
    }

    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background:
        radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
        radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.2) 0%, transparent 50%);
      pointer-events: none;
      z-index: -1;
    }

    .register-container {
      max-width: 500px;
      width: 100%;
    }

    .register-card {
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.98));
      border-radius: 25px;
      box-shadow:
        0 25px 60px rgba(0, 0, 0, 0.15),
        0 10px 30px rgba(67, 97, 238, 0.2),
        inset 0 1px 0 rgba(255, 255, 255, 0.6);
      overflow: hidden;
      border: 1px solid rgba(255, 255, 255, 0.4);
      backdrop-filter: blur(20px);
      position: relative;
    }

    .register-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 8px;
      background: var(--gradient-success);
      box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);
    }

    .register-header {
      background: var(--gradient-success);
      color: white;
      padding: 2.5rem 2rem;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .register-header::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
      background-size: 20px 20px;
      transform: rotate(30deg);
      animation: float 20s linear infinite;
    }

    .register-icon {
      width: 80px;
      height: 80px;
      background: rgba(255, 255, 255, 0.2);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1.5rem;
      font-size: 2rem;
      border: 3px solid rgba(255, 255, 255, 0.3);
    }

    .register-body {
      padding: 2.5rem 2rem;
    }

    .form-group {
      margin-bottom: 1.5rem;
      position: relative;
    }

    .form-label {
      color: var(--primary-dark);
      font-weight: 600;
      margin-bottom: 0.5rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .form-control {
      border: 2px solid #e9ecef;
      border-radius: 12px;
      padding: 0.875rem 1rem 0.875rem 3rem;
      font-size: 1rem;
      transition: all 0.3s ease;
      background: white;
    }

    .form-control:focus {
      border-color: var(--primary-blue);
      box-shadow: 0 0 0 0.3rem rgba(67, 97, 238, 0.15);
      transform: translateY(-2px);
    }

    .form-select {
      border: 2px solid #e9ecef;
      border-radius: 12px;
      padding: 0.875rem 1rem;
      font-size: 1rem;
      transition: all 0.3s ease;
      background: white;
    }

    .form-select:focus {
      border-color: var(--primary-blue);
      box-shadow: 0 0 0 0.3rem rgba(67, 97, 238, 0.15);
      transform: translateY(-2px);
    }

    .input-icon {
      position: absolute;
      left: 1rem;
      top: 2.5rem;
      color: var(--primary-blue);
      font-size: 1.1rem;
      z-index: 2;
    }

    .btn-register {
      background: var(--gradient-success);
      border: none;
      border-radius: 15px;
      padding: 1rem 2rem;
      font-weight: 700;
      font-size: 1.1rem;
      transition: all 0.3s ease;
      color: white;
      box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
      width: 100%;
      position: relative;
      overflow: hidden;
    }

    .btn-register::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
      transition: left 0.5s;
    }

    .btn-register:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 35px rgba(40, 167, 69, 0.4);
      color: white;
    }

    .btn-register:hover::before {
      left: 100%;
    }

    .login-link {
      text-align: center;
      margin-top: 2rem;
      padding-top: 1.5rem;
      border-top: 1px solid #e9ecef;
    }

    .login-link a {
      color: var(--primary-blue);
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }

    .login-link a:hover {
      color: var(--primary-dark);
      transform: translateX(5px);
    }

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
    }

    .password-toggle {
      position: absolute;
      right: 1rem;
      top: 2.5rem;
      background: none;
      border: none;
      color: #6c757d;
      cursor: pointer;
      z-index: 2;
    }

    .password-toggle:hover {
      color: var(--primary-blue);
    }

    @keyframes float {
      0% {
        transform: translateX(0) rotate(30deg);
      }

      50% {
        transform: translateX(-10px) rotate(30deg);
      }

      100% {
        transform: translateX(0) rotate(30deg);
      }
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .fade-in-up {
      animation: fadeInUp 0.6s ease-out;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .register-container {
        max-width: 100%;
      }

      .form-row {
        grid-template-columns: 1fr;
      }

      .register-header {
        padding: 2rem 1.5rem;
      }

      .register-body {
        padding: 2rem 1.5rem;
      }

      .register-icon {
        width: 70px;
        height: 70px;
        font-size: 1.8rem;
      }
    }

    @media (max-width: 480px) {
      .register-header {
        padding: 1.5rem 1rem;
      }

      .register-body {
        padding: 1.5rem 1rem;
      }
    }
  </style>
</head>

<body>
  <div class="register-container">
    <div class="register-card fade-in-up">
      <!-- Header -->
      <div class="register-header">
        <div class="register-icon">
          <i class="fas fa-user-plus"></i>
        </div>
        <h2 class="fw-bold mb-2">Daftar Akun Baru</h2>
        <p class="mb-0 opacity-90">Bergabunglah dengan Tiket Ajaib</p>
      </div>

      <!-- Body -->
      <div class="register-body">
        <form method="post">
          <!-- Username & Password Row -->
          <div class="form-row">
            <div class="form-group">
              <label class="form-label">
                <i class="fas fa-user"></i>Username
              </label>
              <div class="position-relative">
                <i class="fas fa-user input-icon"></i>
                <input type="text" name="username" class="form-control" placeholder="Username" required>
              </div>
            </div>

            <div class="form-group">
              <label class="form-label">
                <i class="fas fa-lock"></i>Password
              </label>
              <div class="position-relative">
                <i class="fas fa-lock input-icon"></i>
                <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                <button type="button" class="password-toggle" onclick="togglePassword()">
                  <i class="fas fa-eye" id="toggleIcon"></i>
                </button>
              </div>
            </div>
          </div>

          <!-- Nama & Alamat -->
          <div class="form-group">
            <label class="form-label">
              <i class="fas fa-id-card"></i>Nama Lengkap
            </label>
            <div class="position-relative">
              <i class="fas fa-id-card input-icon"></i>
              <input type="text" name="nama_penumpang" class="form-control" placeholder="Nama lengkap Anda" required>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">
              <i class="fas fa-map-marker-alt"></i>Alamat
            </label>
            <div class="position-relative">
              <i class="fas fa-map-marker-alt input-icon"></i>
              <input type="text" name="alamat_penumpang" class="form-control" placeholder="Alamat lengkap">
            </div>
          </div>

          <!-- Tanggal Lahir & Jenis Kelamin -->
          <div class="form-row">
            <div class="form-group">
              <label class="form-label">
                <i class="fas fa-calendar-alt"></i>Tanggal Lahir
              </label>
              <div class="position-relative">
                <i class="fas fa-calendar-alt input-icon"></i>
                <input type="date" name="tanggal_lahir" class="form-control">
              </div>
            </div>

            <div class="form-group">
              <label class="form-label">
                <i class="fas fa-venus-mars"></i>Jenis Kelamin
              </label>
              <select name="jenis_kelamin" class="form-select">
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
              </select>
            </div>
          </div>

          <!-- Telepon -->
          <div class="form-group">
            <label class="form-label">
              <i class="fas fa-phone"></i>Nomor Telepon
            </label>
            <div class="position-relative">
              <i class="fas fa-phone input-icon"></i>
              <input type="text" name="telefone" class="form-control" placeholder="Nomor telepon aktif">
            </div>
          </div>

          <!-- Submit Button -->
          <button type="submit" class="btn-register">
            <i class="fas fa-user-plus me-2"></i>Daftar Akun
          </button>
        </form>

        <!-- Login Link -->
        <div class="login-link">
          <p class="mb-0 text-muted">
            Sudah punya akun?
            <a href="login.php">
              <i class="fas fa-sign-in-alt me-1"></i>Login disini
            </a>
          </p>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Toggle password visibility
    function togglePassword() {
      const passwordInput = document.getElementById('password');
      const toggleIcon = document.getElementById('toggleIcon');

      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
      } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
      }
    }

    // Set today as max date for birth date
    document.addEventListener('DOMContentLoaded', function() {
      const today = new Date().toISOString().split('T')[0];
      const birthDateInput = document.querySelector('input[name="tanggal_lahir"]');
      birthDateInput.max = today;

      // Add focus effects
      const inputs = document.querySelectorAll('.form-control, .form-select');

      inputs.forEach(input => {
        input.addEventListener('focus', function() {
          this.style.transform = 'translateY(-2px)';
        });

        input.addEventListener('blur', function() {
          this.style.transform = 'translateY(0)';
        });
      });
    });

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
      const password = document.querySelector('input[name="password"]').value;
      if (password.length < 6) {
        e.preventDefault();
        alert('Password harus minimal 6 karakter!');
        return false;
      }
    });
  </script>
</body>

</html>