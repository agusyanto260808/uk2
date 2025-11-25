<?php
include '../../../config/connection.php';
include '../../partials/header.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin - Tiket Ajaib</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary-blue: #4361ee;
      --primary-light: #4895ef;
      --primary-dark: #3f37c9;
      --gradient-primary: linear-gradient(135deg, #4361ee 0%, #3f37c9 100%);
      --gradient-secondary: linear-gradient(135deg, #4cc9f0 0%, #4895ef 100%);
      --gradient-admin: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      --gradient-bg: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
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
      overflow: hidden;
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

    .login-container {
      max-width: 420px;
      width: 100%;
    }

    .login-card {
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

    .login-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 8px;
      background: var(--gradient-admin);
      box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .login-header {
      background: var(--gradient-admin);
      color: white;
      padding: 2.5rem 2rem;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .login-header::before {
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

    .login-icon {
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

    .login-body {
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

    .input-icon {
      position: absolute;
      left: 1rem;
      top: 2.5rem;
      color: var(--primary-blue);
      font-size: 1.1rem;
      z-index: 2;
    }

    .btn-login {
      background: var(--gradient-admin);
      border: none;
      border-radius: 15px;
      padding: 1rem 2rem;
      font-weight: 700;
      font-size: 1.1rem;
      transition: all 0.3s ease;
      color: white;
      box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
      width: 100%;
      position: relative;
      overflow: hidden;
    }

    .btn-login::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
      transition: left 0.5s;
    }

    .btn-login:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
      color: white;
    }

    .btn-login:hover::before {
      left: 100%;
    }

    .register-link {
      text-align: center;
      margin-top: 2rem;
      padding-top: 1.5rem;
      border-top: 1px solid #e9ecef;
    }

    .register-link a {
      color: var(--primary-blue);
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }

    .register-link a:hover {
      color: var(--primary-dark);
      transform: translateX(5px);
    }

    .admin-badge {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      background: rgba(255, 255, 255, 0.2);
      padding: 0.5rem 1rem;
      border-radius: 20px;
      font-size: 0.9rem;
      margin-top: 1rem;
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
    @media (max-width: 480px) {
      .login-container {
        max-width: 100%;
      }

      .login-header {
        padding: 2rem 1.5rem;
      }

      .login-body {
        padding: 2rem 1.5rem;
      }

      .login-icon {
        width: 70px;
        height: 70px;
        font-size: 1.8rem;
      }
    }
  </style>
</head>

<body>
  <div class="login-container">
    <div class="login-card fade-in-up">
      <!-- Header -->
      <div class="login-header">
        <div class="login-icon">
          <i class="fas fa-user-shield"></i>
        </div>
        <h2 class="fw-bold mb-2">Login </h2>
        <!-- <p class="mb-0 opacity-90">Akses Panel Administrasi</p> -->
        <div class="admin-badge">
          <i class="fas fa-star"></i>
          <span>Petugas & Administrator</span>
        </div>
      </div>

      <!-- Body -->
      <div class="login-body">
        <form method="POST" action="../../actions/auth/login_proses.php">
          <!-- Username Field -->
          <div class="form-group">
            <label class="form-label">
              <i class="fas fa-user-tie"></i>
              Username
            </label>
            <div class="position-relative">

              <!-- <i class="fas fa-user-tie input-icon"></i> -->
              <input type="text" name="username" class="form-control" placeholder="Masukkan username " required>
            </div>
          </div>

          <!-- Password Field -->
          <div class="form-group">
            <label class="form-label">
              <i class="fas fa-key"></i>Password
            </label>
            <div class="position-relative">
              <!-- <i class="fas fa-key input-icon"></i> -->
              <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
              <button type="button" class="password-toggle" onclick="togglePassword()">
                <i class="fas fa-eye" id="toggleIcon"></i>
              </button>
            </div>
          </div>

          <!-- Submit Button -->
          <button type="submit" class="btn-login">
            <i class="fas fa-sign-in-alt me-2"></i>Masuk ke Dashboard
          </button>
        </form>

        <!-- Register Link -->
        <!-- <div class="register-link">
          <p class="mb-0 text-muted">
            Belum punya akun?
            <a href="register.php">
              <i class="fas fa-user-plus me-1"></i>Daftar disini
            </a>
          </p>
        </div> -->
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

    // Add focus effects
    document.addEventListener('DOMContentLoaded', function() {
      const inputs = document.querySelectorAll('.form-control');

      inputs.forEach(input => {
        input.addEventListener('focus', function() {
          this.parentElement.classList.add('focused');
        });

        input.addEventListener('blur', function() {
          this.parentElement.classList.remove('focused');
        });
      });

      // Add enter key support
      document.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
          const form = document.querySelector('form');
          form.dispatchEvent(new Event('submit'));
        }
      });
    });

    // Security notice
    console.log('%c⚠️ Area Terbatas - Admin Only', 'color: #dc3545; font-size: 16px; font-weight: bold;');
    console.log('%cHanya personel yang berwenang yang boleh mengakses halaman ini.', 'color: #6c757d;');
  </script>
</body>

</html>