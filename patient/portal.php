<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php"); 
    exit();
}

// Redirect staff and admin to their respective portals
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: ../admin/portal.php");
        exit();
    } elseif ($_SESSION['role'] === 'staff') {
        header("Location: ../staff/portal.php");
        exit();
    }
}

// Database connection
include '../includes/db_connect.php';

// Fetch user details
$user_id = $_SESSION['user_id'];
$sql = "SELECT name, username, email, mobile FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($user = mysqli_fetch_assoc($result)) {
    $user_name     = $user['name'];
    $user_username = $user['username'];
    $user_email    = $user['email'];
    $user_mobile   = $user['mobile'];
} else {
    echo "<script>alert('User not found. Please log in again.'); window.location.href = '../auth/login.php';</script>";
    exit();
}
mysqli_stmt_close($stmt);

// Fetch patient-specific stats
$sql_app = "SELECT COUNT(*) AS c FROM appointments WHERE patient_id = ? AND status != 'cancelled'";
$stmt_app = mysqli_prepare($conn, $sql_app);
mysqli_stmt_bind_param($stmt_app, "i", $user_id);
mysqli_stmt_execute($stmt_app);
$appointments_count = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_app))['c'] ?? 0;
mysqli_stmt_close($stmt_app);

$sql_med = "SELECT COUNT(*) AS c FROM medical_records WHERE patient_id = ?";
$stmt_med = mysqli_prepare($conn, $sql_med);
mysqli_stmt_bind_param($stmt_med, "i", $user_id);
mysqli_stmt_execute($stmt_med);
$medical_records_count = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_med))['c'] ?? 0;
mysqli_stmt_close($stmt_med);

$sql_bills = "SELECT COUNT(*) AS c FROM bill_payments WHERE patient_id = ? AND status != 'paid'";
$stmt_bills = mysqli_prepare($conn, $sql_bills);
mysqli_stmt_bind_param($stmt_bills, "i", $user_id);
mysqli_stmt_execute($stmt_bills);
$pending_bills_count = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_bills))['c'] ?? 0;
mysqli_stmt_close($stmt_bills);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Portal — Care Compass Hospital</title>
    <link rel="stylesheet" href="../assets/css/admin_styles.css">
</head>
<body>

<?php
$portal_home  = 'portal.php';
$portal_label = 'Patient Portal';
$base_path    = '../';
include '../includes/header.php';
?>

<div class="internal-body">
    <div class="admin-dashboard-wrap">

        <!-- Welcome Banner -->
        <div class="admin-welcome-banner">
            <div>
                <h1>Welcome, <?php echo htmlspecialchars($user_name); ?>!</h1>
                <p>Manage your appointments, access your medical records, and handle payments securely.</p>
            </div>
            <div class="welcome-icon">🏥</div>
        </div>

        <!-- Quick Stats Row -->
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
                <div class="stat-number" style="color:#fd7e14;"><?php echo $pending_bills_count; ?></div>
                <div class="stat-label">Pending / Unpaid Bills</div>
            </div>
        </div>

        <!-- Module Grid -->
        <div class="internal-page-title">Patient Services</div>
        <div class="admin-modules-grid">
            <a href="appointments.php" class="admin-module-card">
                <div class="module-icon icon-blue">➕</div>
                <div class="module-label">Book Appointment</div>
            </a>
            <a href="view_appointments.php" class="admin-module-card">
                <div class="module-icon icon-orange">📅</div>
                <div class="module-label">My Appointments</div>
            </a>
            <a href="view_medical_records.php" class="admin-module-card">
                <div class="module-icon icon-teal">📋</div>
                <div class="module-label">Medical Records</div>
            </a>
            <a href="view_bill_details.php" class="admin-module-card">
                <div class="module-icon icon-green">💳</div>
                <div class="module-label">Bill Payments</div>
            </a>
        </div>

        <!-- Split Grid: Profile & Feedback -->
        <div class="dashboard-split-grid">
            <!-- Patient Profile Card -->
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <span class="card-header-icon">👤</span>
                    <h3>Personal Profile</h3>
                </div>
                <div class="profile-details-list">
                    <div class="profile-detail-item">
                        <span class="detail-label">Full Name</span>
                        <span class="detail-val"><?php echo htmlspecialchars($user_name); ?></span>
                    </div>
                    <div class="profile-detail-item">
                        <span class="detail-label">Username</span>
                        <span class="detail-val"><?php echo htmlspecialchars($user_username); ?></span>
                    </div>
                    <div class="profile-detail-item">
                        <span class="detail-label">Email Address</span>
                        <span class="detail-val"><?php echo htmlspecialchars($user_email); ?></span>
                    </div>
                    <div class="profile-detail-item">
                        <span class="detail-label">Mobile Number</span>
                        <span class="detail-val"><?php echo htmlspecialchars($user_mobile); ?></span>
                    </div>
                    <div class="profile-detail-item">
                        <span class="detail-label">Account Role</span>
                        <span class="detail-badge">Patient</span>
                    </div>
                </div>
            </div>

            <!-- Submit Feedback Card -->
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <span class="card-header-icon">💬</span>
                    <h3>Submit Feedback</h3>
                </div>
                <form method="POST" action="submit_feedback.php" class="modern-feedback-form">
                    <div class="form-group">
                        <label for="rating">Your Rating</label>
                        <select name="rating" id="rating" required class="form-input-modern">
                            <option value="5">★★★★★ (5 - Excellent)</option>
                            <option value="4">★★★★☆ (4 - Very Good)</option>
                            <option value="3">★★★☆☆ (3 - Good)</option>
                            <option value="2">★★☆☆☆ (2 - Fair)</option>
                            <option value="1">★☆☆☆☆ (1 - Poor)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="feedback_text">Your Experience &amp; Feedback</label>
                        <textarea name="feedback_text" id="feedback_text" required placeholder="Share your experience with our hospital, doctors, or staff..." rows="3" class="form-input-modern"></textarea>
                    </div>
                    <button type="submit" class="btn-submit-modern">Submit Feedback</button>
                </form>
            </div>
        </div>

    </div>
</div>

<footer class="internal-footer">
    &copy; 2025 Care Compass Hospitals &nbsp;|&nbsp; <a href="portal.php">Patient Portal</a>
</footer>

</body>
</html>

