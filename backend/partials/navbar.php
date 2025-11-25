<div class="main-panel">
    <div class="main-header shadow-lg">
        <div class="main-header-logo">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="dark">
                <!-- <a href="index.html" class="logo">
                    <img
                        src="../../tp-ad/assets/img/kaiadmin/spp11.png"
                        alt="navbar brand"
                        class="navbar-brand"
                        height="20" />
                </a> -->
                <div class="nav-toggle">
                    <button class="btn btn-toggle toggle-sidebar">
                        <i class="gg-menu-right"></i>
                    </button>
                    <button class="btn btn-toggle sidenav-toggler">
                        <i class="gg-menu-left"></i>
                    </button>
                </div>
                <button class="topbar-toggler more">
                    <i class="gg-more-vertical-alt"></i>
                </button>
            </div>
            <!-- End Logo Header -->
        </div>
        <!-- Navbar Header -->
        <nav
            class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
            <div class="container-fluid ">
                <nav
                    class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
                    <!-- <div class="input-group">
                        <div class="input-group-prepend">
                            <button type="submit" class="btn btn-search pe-1">
                                <i class="fa fa-search search-icon"></i>
                            </button>
                        </div>
                        <input
                            type="text"
                            placeholder="Search ..."
                            class="form-control" />
                    </div> -->
                </nav>
                <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                    <li class="nav-item topbar-user dropdown hidden-caret">
                        <a
                            class="dropdown-toggle profile-pic"
                            data-bs-toggle="dropdown"
                            href="#"
                            aria-expanded="false">
                            <div class="avatar-sm">
                                <img
                                    src="../../tp-ad/assets/img/person.svg"
                                    alt="..."
                                    class="avatar-img rounded-circle" />
                            </div>
                            <span class="profile-username">
                                <span class="op-7">Hi,</span>

                                <small class="text-muted">
                                    <?php echo htmlspecialchars($_SESSION['level'] ?? ''); ?>
                                </small>
                            </span>

                        </a>
                        <ul class="dropdown-menu dropdown-user animated fadeIn">
                            <div class="dropdown-user-scroll scrollbar-outer">
                                <li>
                                    <div class="user-box">
                                        <div class="avatar-lg">
                                            <img
                                                src="../../tp-ad/assets/img/person.svg"
                                                alt="image profile"
                                                class="avatar-img rounded" />
                                        </div>
                                        <div class="u-text">
                                            <h4><?php echo htmlspecialchars($_SESSION['nama_petugas'] ?? 'Guest'); ?></h4>
                                            <p class="text-muted"><?php echo htmlspecialchars($_SESSION['level'] ?? ''); ?></p>

                                            <a
                                                href="../../actions/auth/logout.php"
                                                class="btn btn-xs btn-secondary btn-sm">Logout</a>
                                        </div>
                                    </div>
                                </li>
                            </div>
                        </ul>
                    </li>
                </ul>


            </div>
        </nav>
        <!-- End Navbar -->
    </div>
    <style>
        /* Navbar fix agar menempel di atas dan sejajar dengan sidebar */
        .main-header {
            position: fixed;
            top: 0;
            left: 260xpx;
            /* lebar sidebar */
            right: 0;
            z-index: 1030;
            background-color: #fff;
            transition: all 0.3s ease;
        }

        /* Pastikan konten tidak ketutup navbar */
        .main-panel {
            padding-top: 80px;
            /* tinggi navbar */
            margin-left: 260px;
            /* sejajar dengan sidebar */
            transition: all 0.3s ease;
        }

        /* Kalau sidebar bisa collapse, tambahkan class aktif (misal .sidebar-collapsed) */
        .sidebar-collapsed .main-header {
            left: 80px;
            /* sesuaikan lebar sidebar saat collapse */
        }

        .sidebar-collapsed .main-panel {
            margin-left: 80px;
        }

        /* Biar rapi */
        body {
            background-color: #f8faff;
        }
    </style>

    <style>
        .navbar .avatar-img {
            width: 40px;
            /* atur sesuai selera */
            height: 40px;
            object-fit: cover;
            /* biar gambar nggak gepeng */
        }

        .navbar .avatar-sm {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .navbar .navbar-header {
            box-shadow: #000000ff;
        }
    </style>
    <style>
        /* Navbar fix agar menempel di atas dan sejajar dengan sidebar */
        .main-header {
            position: fixed;
            top: 0;
            left: 262x;
            /* lebar sidebar */
            right: 0;
            z-index: 1030;
            background-color: #f1f3f4;
            /* abu-abu pudar */
            box-shadow: none !important;
            /* hilangkan bayangan */
            border-bottom: 1px solid #e0e0e0;
            /* garis lembut di bawah */
            transition: all 0.3s ease;
        }

        /* Pastikan konten tidak ketutup navbar */
        .main-panel {
            padding-top: 80px;
            /* tinggi navbar */
            margin-left: 260px;
            /* sejajar dengan sidebar */
            transition: all 0.3s ease;
            /* background-color: #f8faff; */
        }

        /* Kalau sidebar bisa collapse, tambahkan class aktif (misal .sidebar-collapsed) */
        .sidebar-collapsed .main-header {
            left: 80px;
            /* sesuaikan lebar sidebar saat collapse */
        }

        .sidebar-collapsed .main-panel {
            margin-left: 80px;
        }

        /* Avatar dan profil user di navbar */
        .navbar .avatar-img {
            width: 40px;
            height: 40px;
            object-fit: cover;
        }

        .navbar .avatar-sm {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Ubah warna background navbar transparan jadi abu pudar */
        /* .navbar.navbar-header {
            background-color: #f1f3f4 !important; */
        /* abu pudar */
        /* box-shadow: none !important;
        } */

        /* Hilangkan efek transparan */
        .navbar-header-transparent {
            background: #f1f3f4 !important;
        }

        /* Teks user biar kontras */
        .profile-username span,
        .profile-username small {
            color: #333 !important;
        }
    </style>