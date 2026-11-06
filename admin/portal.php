<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php"); 
    exit();
}

include '../includes/db_connect.php';

$user_id = $_SESSION['user_id'];
$sql = "SELECT name, username FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($admin = mysqli_fetch_assoc($result)) {
    $admin_name = $admin['name'];
    $admin_username = $admin['username'];
} else {
    echo "<script>alert('Admin not found. Please log in again.'); window.location.href = '../auth/login.php';</script>";
    exit();
}
mysqli_stmt_close($stmt);

// Fetch quick stats
$stats = [];
$stats['users']        = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM users"))['c'] ?? 0;
$stats['doctors']      = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM doctors"))['c'] ?? 0;
$stats['appointments'] = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM appointments WHERE status != 'cancelled'"))['c'] ?? 0;
$stats['bills']        = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM bill_payments WHERE status = 'pending'"))['c'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal — Care Compass Hospital</title>
    <link rel="stylesheet" href="../assets/css/admin_styles.css">
</head>
<body>

<?php
$portal_home  = 'portal.php';
$portal_label = 'Admin Panel';
$base_path    = '../';
include '../includes/header.php';
?>

<div class="internal-body">
    <div class="admin-dashboard-wrap">

        <!-- Welcome Banner -->
        <div class="admin-welcome-banner">
            <div>
                <h1>Welcome back, <?php echo htmlspecialchars($admin_name); ?>!</h1>
                <p>Manage hospital operations, users, and services securely from your control center.</p>
            </div>
            <div class="welcome-icon">🏥</div>
        </div>

        <!-- Quick Stats -->
        <div class="staff-stats-row" style="margin-bottom:32px;">
            <div class="staff-stat-card">
                <div class="stat-number"><?php echo $stats['users']; ?></div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="staff-stat-card green">
                <div class="stat-number" style="color:#28a745;"><?php echo $stats['doctors']; ?></div>
                <div class="stat-label">Active Doctors</div>
            </div>
            <div class="staff-stat-card orange">
                <div class="stat-number" style="color:#fd7e14;"><?php echo $stats['appointments']; ?></div>
                <div class="stat-label">Active Appointments</div>
            </div>
        </div>

        <!-- Module Grid -->
        <div class="internal-page-title">Management Modules</div>
        <div class="admin-modules-grid">
            <a href="manage_users.php" class="admin-module-card">
                <div class="module-icon icon-blue">👥</div>
                <div class="module-label">Manage Users</div>
            </a>
            <a href="manage_doctors.php" class="admin-module-card">
                <div class="module-icon icon-green">🩺</div>
                <div class="module-label">Manage Doctors</div>
            </a>
            <a href="manage_services.php" class="admin-module-card">
                <div class="module-icon icon-purple">🏷️</div>
                <div class="module-label">Manage Services</div>
            </a>
            <a href="manage_appointments.php" class="admin-module-card">
                <div class="module-icon icon-orange">📅</div>
                <div class="module-label">Manage Appointments</div>
            </a>
            <a href="manage_medical_records.php" class="admin-module-card">
                <div class="module-icon icon-teal">📋</div>
                <div class="module-label">Medical Records</div>
            </a>
            <a href="manage_bill_payments.php" class="admin-module-card">
                <div class="module-icon icon-orange">💳</div>
                <div class="module-label">Bill Payments</div>
            </a>
            <a href="manage_feedback.php" class="admin-module-card">
                <div class="module-icon icon-purple">💬</div>
                <div class="module-label">Feedback</div>
            </a>
        </div>

    </div>
</div>

<footer class="internal-footer">
    &copy; 2025 Care Compass Hospitals &nbsp;|&nbsp; <a href="portal.php">Admin Panel</a>
</footer>

</body>
</html>
