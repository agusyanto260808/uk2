<footer id="footer" class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-6 text-center text-md-start">
                <div class="copyright">
                    © 2025 <strong>Sistem Pembayaran SPP</strong>
                </div>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <div class="credits">
                    Di Buat <a href="https://bootstrapmade.com/" target="_blank">Agus Styanto</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Scroll Top -->
<a href="#" id="scroll-top" class="scroll-top">
    <i class="fas fa-chevron-up"></i>
</a>



<style>
    :root {
        --primary-blue: #1a73e8;
        --secondary-blue: #4285f4;
        --light-blue: #e8f0fe;
        --dark-blue: #0d47a1;
        --accent-blue: #2962ff;
        --gradient-blue: linear-gradient(135deg, var(--secondary-blue), var(--primary-blue));
    }

    .footer {
        position: fixed;
        background: var(--gradient-blue);
        color: white;
        padding: 20px 0;
        margin-top: 0px;
        box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.1);
        position: relative;
        bottom: 0;
        width: 100%;
    }

    .footer .copyright {
        font-size: 15px;
        margin-bottom: 10px;
    }

    .footer .credits {
        font-size: 14px;
    }

    .footer .credits a {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .footer .credits a:hover {
        color: white;
        text-decoration: underline;
    }

    /* Scroll Top Button */
    .scroll-top {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 45px;
        height: 45px;
        background: var(--gradient-blue);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        opacity: 0;
        visibility: hidden;
        transition: all 0.4s ease;
        z-index: 999;
    }

    .scroll-top.active {
        opacity: 1;
        visibility: visible;
        bottom: 20px;
    }

    .scroll-top:hover {
        background: var(--primary-blue);
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    .spinner-border {
        width: 3rem;
        height: 3rem;
        border-width: 0.2em;
    }

    @media (max-width: 768px) {
        .footer {
            text-align: center;
            padding: 15px 0;
        }

        .footer .copyright,
        .footer .credits {
            text-align: center;
            margin-bottom: 5px;
        }

        .scroll-top {
            width: 40px;
            height: 40px;
            bottom: 15px;
            right: 15px;
        }
    }
</style>

<script>
    // Scroll Top Button
    window.addEventListener('scroll', function() {
        const scrollTop = document.getElementById('scroll-top');
        if (window.scrollY > 300) {
            scrollTop.classList.add('active');
        } else {
            scrollTop.classList.remove('active');
        }
    });

    // Smooth scroll to top
    document.getElementById('scroll-top').addEventListener('click', function(e) {
        e.preventDefault();
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Preloader
    window.addEventListener('load', function() {
        const preloader = document.getElementById('preloader');
        setTimeout(function() {
            preloader.classList.add('loaded');
        }, 1000);
    });
</script>