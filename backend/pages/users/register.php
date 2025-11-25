<?php
session_start();
include __DIR__ . "/../../../config/connection.php"; // koneksi dari file config

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username     = trim($_POST['username']);
    $password     = trim($_POST['password']);
    $nama_petugas = trim($_POST['nama_petugas']);
    $id_level     = intval($_POST['id_level']);

    if (empty($username) || empty($password) || empty($nama_petugas)) {
        echo "<script>alert('Semua field wajib diisi!');</script>";
    } else {
        // cek apakah username sudah ada
        $check = $conn->prepare("SELECT id_petugas FROM petugas WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            echo "<script>alert('Username sudah dipakai, silakan pilih username lain!');</script>";
        } else {
            // hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // insert data baru
            $sql = "INSERT INTO petugas (username, password, nama_petugas, id_level) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $username, $hashedPassword, $nama_petugas, $id_level);

            if ($stmt->execute()) {
                echo "<script>
                    alert('Registrasi berhasil! Silakan login.');
                    window.location.href='login.php';
                </script>";
                exit();
            } else {
                echo "<script>alert('Terjadi kesalahan saat registrasi.');</script>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Register Petugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --light: #f8f9fa;
            --dark: #212529;
            --gradient: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
        }

        body {

            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .register-container {
            width: 100%;
            max-width: 500px;
            margin: 20px;
        }

        .register-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .register-card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            background: var(--gradient);
            color: white;
            text-align: center;
            padding: 30px 20px;
            position: relative;
        }

        .card-header h2 {
            margin: 0;
            font-weight: 700;
            font-size: 1.8rem;
        }

        .card-header p {
            margin: 10px 0 0;
            opacity: 0.9;
        }

        .card-body {
            padding: 30px;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #e1e5ee;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #495057;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border: 1px solid #e1e5ee;
            border-right: none;
            border-radius: 10px 0 0 10px;
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 10px 10px 0;
        }

        .btn-register {
            background: var(--gradient);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.4);
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
        }

        .login-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .icon-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .user-icon {
            background: rgba(255, 255, 255, 0.2);
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .form-floating {
            margin-bottom: 1.5rem;
        }

        .floating-label {
            color: #6c757d;
        }
    </style>
</head>

<body>
    <div class="register-container">
        <div class="register-card">
            <div class="card-header">
                <div class="icon-container">
                    <div class="user-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                </div>
                <h2>Registrasi </h2>
                <p>Buat akun baru untuk mengakses sistem</p>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required>
                        <label for="username" class="floating-label">
                            <i class="fas fa-user me-2"></i>Username
                        </label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                        <label for="password" class="floating-label">
                            <i class="fas fa-lock me-2"></i>Password
                        </label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="nama_petugas" name="nama_petugas" placeholder="Masukkan nama lengkap" required>
                        <label for="nama_petugas" class="floating-label">
                            <i class="fas fa-id-card me-2"></i>Nama Petugas
                        </label>
                    </div>

                    <!-- <div class="mb-4">
                        <label for="id_level" class="form-label">
                            <i class="fas fa-user-tag me-2"></i>Level
                        </label>
                        <select name="id_level" class="form-select" id="id_level" required>
                            <option value="1">Administrator</option>
                            <option value="2">Petugas</option>
                        </select>
                    </div> -->

                    <button type="submit" class="btn btn-primary btn-register w-100">
                        <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                    </button>
                </form>

                <div class="login-link">
                    Sudah punya akun? <a href="login.php">Login disini</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>