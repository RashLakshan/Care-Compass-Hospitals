<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

if ($_SESSION['role'] === 'admin') {
    header("Location: admin/portal.php");
    exit();
} elseif ($_SESSION['role'] === 'staff') {
    header("Location: staff/portal.php");
    exit();
} else {
    header("Location: patient/portal.php");
    exit();
}
?>
