<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header("Location: ../auth/login.php"); 
    exit();
}

include '../includes/db_connect.php';

$staff_id = $_SESSION['user_id'];
$sql_staff = "SELECT name FROM users WHERE id = ?";
$stmt_staff = mysqli_prepare($conn, $sql_staff);
mysqli_stmt_bind_param($stmt_staff, "i", $staff_id);
mysqli_stmt_execute($stmt_staff);
$result_staff = mysqli_stmt_get_result($stmt_staff);
$staff = mysqli_fetch_assoc($result_staff) ?: ['name' => 'Staff'];
mysqli_stmt_close($stmt_staff);

// Fetch stats for staff dashboard
$appointments_count  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM appointments WHERE status != 'cancelled'"))['c'] ?? 0;
$medical_records_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM medical_records"))['c'] ?? 0;
$bill_payments_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM bill_payments WHERE status != 'paid'"))['c'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Portal — Care Compass Hospital</title>
    <link rel="stylesheet" href="../assets/css/admin_styles.css">
</head>
<body>

<?php
$portal_home  = 'portal.php';
$portal_label = 'Staff Portal';
$base_path    = '../';
include '../includes/header.php';
?>

<div class="internal-body">
    <div class="admin-dashboard-wrap">

        <!-- Welcome Banner -->
        <div class="admin-welcome-banner">
            <div>
                <h1>Welcome, <?php echo htmlspecialchars($staff['name']); ?>!</h1>
                <p>Manage hospital operations efficiently. Here's your daily overview.</p>
            </div>
            <div class="welcome-icon">🩺</div>
        </div>

        <!-- Stats Row -->
        <div class="staff-stats-row">
            <div class="staff-stat-card">
                <div class="stat-number"><?php echo $appointments_count; ?></div>
                <div class="stat-label">Active Appointments</div>
            </div>
            <div class="staff-stat-card green">
                <div class="stat-number" style="color:#28a745;"><?php echo $medical_records_count; ?></div>
                <div class="stat-label">Medical Records</div>
            </div>
            <div class="staff-stat-card orange">
                <div class="stat-number" style="color:#fd7e14;"><?php echo $bill_payments_count; ?></div>
                <div class="stat-label">Pending / Overdue Bills</div>
            </div>
        </div>

        <!-- Staff Module Grid -->
        <div class="internal-page-title">Staff Actions</div>
        <div class="admin-modules-grid">
            <a href="../admin/manage_appointments.php" class="admin-module-card">
                <div class="module-icon icon-orange">📅</div>
                <div class="module-label">Manage Appointments</div>
            </a>
            <a href="view_medical_records.php" class="admin-module-card">
                <div class="module-icon icon-teal">📋</div>
                <div class="module-label">View Medical Records</div>
            </a>
            <a href="../admin/manage_bill_payments.php" class="admin-module-card">
                <div class="module-icon icon-green">💳</div>
                <div class="module-label">Manage Bill Payments</div>
            </a>
        </div>

    </div>
</div>

<footer class="internal-footer">
    &copy; 2025 Care Compass Hospitals &nbsp;|&nbsp; <a href="portal.php">Staff Portal</a>
</footer>

</body>
</html>

