<?php
session_start();
include '../config/connection.php';
include 'partials/header.php';
include 'partials/navbar.php';


$nama = $_SESSION['nama_penumpang'] ?? 'Penumpang'; ?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Selamat Datang - Sistem Tiket</title>
  <style>
    :root {
      --primary-blue: #1a73e8;
      --primary-blue-light: #4285f4;
      --primary-blue-dark: #0d47a1;
      --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      --gradient-secondary: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
      --gradient-welcome: linear-gradient(135deg, #1a73e8 0%, #4285f4 50%, #00bcd4 100%);
    }

    .welcome-section {
      min-height: 85vh;
      background: var(--gradient-welcome);
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      color: white;
      position: relative;
      overflow: hidden;
      padding: 2rem;
    }

    .welcome-section::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><polygon fill="rgba(255,255,255,0.05)" points="0,1000 1000,0 1000,1000"/></svg>');
      background-size: cover;
    }

    .welcome-content {
      position: relative;
      z-index: 2;
      max-width: 800px;
      animation: fadeInUp 1s ease-out;
    }

    .welcome-section h1 {
      font-size: 3.5rem;
      font-weight: 800;
      margin-bottom: 1.5rem;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
      background: linear-gradient(45deg, #fff, #e3f2fd);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .welcome-section p {
      font-size: 1.3rem;
      margin-bottom: 2.5rem;
      opacity: 0.9;
      line-height: 1.6;
      font-weight: 300;
    }

    .btn-main {
      background: linear-gradient(45deg, #ff6b6b, #ffa726);
      color: white;
      padding: 1rem 2.5rem;
      border-radius: 50px;
      text-decoration: none;
      font-weight: 600;
      font-size: 1.1rem;
      border: none;
      box-shadow: 0 8px 25px rgba(255, 107, 107, 0.3);
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      position: relative;
      overflow: hidden;
    }

    .btn-main::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
      transition: left 0.5s;
    }

    .btn-main:hover {
      transform: translateY(-3px);
      box-shadow: 0 15px 35px rgba(255, 107, 107, 0.4);
      color: white;
    }

    .btn-main:hover::before {
      left: 100%;
    }

    .features-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 2rem;
      margin-top: 4rem;
      padding: 0 2rem;
    }

    .feature-card {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      padding: 2rem;
      text-align: center;
      border: 1px solid rgba(255, 255, 255, 0.2);
      transition: all 0.3s ease;
    }

    .feature-card:hover {
      transform: translateY(-10px);
      background: rgba(255, 255, 255, 0.15);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
    }

    .feature-icon {
      width: 80px;
      height: 80px;
      background: rgba(255, 255, 255, 0.2);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1.5rem;
      font-size: 2rem;
      color: white;
    }

    .feature-card h3 {
      font-size: 1.3rem;
      margin-bottom: 1rem;
      font-weight: 600;
    }

    .feature-card p {
      font-size: 0.95rem;
      opacity: 0.8;
      margin: 0;
    }

    .floating-elements {
      position: absolute;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      pointer-events: none;
    }

    .floating-element {
      position: absolute;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      animation: float 6s ease-in-out infinite;
    }

    .floating-element:nth-child(1) {
      width: 80px;
      height: 80px;
      top: 20%;
      left: 10%;
      animation-delay: 0s;
    }

    .floating-element:nth-child(2) {
      width: 60px;
      height: 60px;
      top: 60%;
      left: 80%;
      animation-delay: 2s;
    }

    .floating-element:nth-child(3) {
      width: 100px;
      height: 100px;
      top: 80%;
      left: 20%;
      animation-delay: 4s;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(50px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes float {

      0%,
      100% {
        transform: translateY(0) rotate(0deg);
      }

      50% {
        transform: translateY(-20px) rotate(180deg);
      }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .welcome-section h1 {
        font-size: 2.5rem;
      }

      .welcome-section p {
        font-size: 1.1rem;
      }

      .features-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
        margin-top: 3rem;
      }

      .btn-main {
        padding: 0.875rem 2rem;
        font-size: 1rem;
      }
    }

    @media (max-width: 480px) {
      .welcome-section {
        padding: 1rem;
        min-height: 80vh;
      }

      .welcome-section h1 {
        font-size: 2rem;
      }

      .welcome-section p {
        font-size: 1rem;
      }

      .feature-card {
        padding: 1.5rem;
      }
    }

    /* Loading Spinner */
    #spinner {
      z-index: 9999;
    }

    .spinner-grow {
      animation: spinner-grow 1s ease-in-out infinite;
    }

    @keyframes spinner-grow {
      0% {
        transform: scale(0);
      }

      50% {
        opacity: 1;
        transform: none;
      }
    }
  </style>
  
</head>

<body>



  <!-- Welcome Section -->
  <div class="welcome-section">
    <!-- Floating Background Elements -->
    <div class="floating-elements">
      <div class="floating-element"></div>
      <div class="floating-element"></div>
      <div class="floating-element"></div>
    </div>

    <div class="welcome-content">
      <h1>Selamat Datang, Di Aplikasi Tiket Ajaib Kami 👋</h1>
      <p>Nikmati kemudahan memesan tiket perjalananmu secara online dengan pengalaman yang cepat, aman, dan terpercaya.</p>
      <a href="pemesanan.php" class="btn btn-main">
        <i class="fas fa-ticket-alt me-2"></i> Pesan Tiket Sekarang
      </a>

      <!-- Features Grid -->
      <div class="features-grid">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-bolt"></i>
          </div>
          <h3>Proses Cepat</h3>
          <p>Pemesanan tiket hanya dalam hitungan menit</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-shield-alt"></i>
          </div>
          <h3>Aman & Terpercaya</h3>
          <p>Transaksi aman dengan sistem terenkripsi</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-headset"></i>
          </div>
          <h3>24/7 Support</h3>
          <p>Tim support siap membantu kapan saja</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-credit-card"></i>
          </div>
          <h3>Pembayaran Mudah</h3>
          <p>Berbagai metode pembayaran tersedia</p>
        </div>
      </div>
    </div>
  </div>

  <?php include 'partials/footer.php';
  include 'partials/script.php';
  ?>

  <script>
    // Hide spinner when page is loaded
    window.addEventListener('load', function() {
      const spinner = document.getElementById('spinner');
      spinner.style.display = 'none';
    });

    // Add scroll animation for feature cards
    document.addEventListener('DOMContentLoaded', function() {
      const featureCards = document.querySelectorAll('.feature-card');

      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.style.animation = 'fadeInUp 0.6s ease-out forwards';
            observer.unobserve(entry.target);
          }
        });
      }, {
        threshold: 0.1
      });

      featureCards.forEach(card => {
        card.style.opacity = '0';
        observer.observe(card);
      });
    });
  </script>
</body>

</html>