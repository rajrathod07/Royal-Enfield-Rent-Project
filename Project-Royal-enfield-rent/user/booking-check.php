<?php
session_start();

// If not logged in, go to login and send redirect
if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php?redirect=" . urlencode($_GET['redirect']));
    exit;
}

// If logged in, go directly to dashboard
header("Location: dashboard.php");
exit;
?>
