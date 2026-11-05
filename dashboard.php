<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] === 'admin') {
    header("Location: admin_portal.php");
    exit();
} elseif ($_SESSION['role'] === 'staff') {
    header("Location: staff_portal.php");
    exit();
} else {
    header("Location: patient_portal.php");
    exit();
}
?>
