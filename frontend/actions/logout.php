<?php
session_start();
session_unset();
session_destroy();

// redirect ke halaman login
header("Location: ../sections/login.php");
exit();
