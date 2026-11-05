<?php
session_start();

// Check if user is logged in and is a patient
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: login.php"); 
    exit();
}

// Database connection
include 'db_connect.php';

// Fetch patient details and their bill payments
$patient_id = $_SESSION['user_id'];

$sql_bill_payments = "SELECT bp.id, bp.amount, bp.payment_date, bp.payment_method, bp.status, a.appointment_date AS appointment_date, d.name AS doctor_name, s.name AS service_name 
                     FROM bill_payments bp 
                     LEFT JOIN appointments a ON bp.appointment_id = a.id 
                     LEFT JOIN doctors d ON a.doctor_id = d.id 
                     LEFT JOIN services s ON a.service_id = s.id 
                     WHERE bp.patient_id = ?
                     ORDER BY bp.payment_date DESC";
$stmt = mysqli_prepare($conn, $sql_bill_payments);
mysqli_stmt_bind_param($stmt, "i", $patient_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$bill_payments = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $bill_payments[] = $row;
    }
} else {
    echo "Error fetching bill payments: " . mysqli_error($conn);
}
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bill Details — Care Compass Hospital</title>
    <link rel="stylesheet" href="admin_styles.css">
</head>
<body>

<?php
$portal_home  = 'patient_portal.php';
$portal_label = 'Patient Portal';
include 'admin_header.php';
?>

<div class="internal-body">
    <div class="internal-manage-wrap">
        <div class="internal-breadcrumb"><a href="patient_portal.php">Patient Portal</a> &rsaquo; Bill Payments</div>
        <div class="internal-page-title">My Bill Details</div>

        <?php if (empty($bill_payments)): ?>
            <div class="dashboard-card" style="text-align: center; padding: 48px 24px;">
                <div style="font-size: 48px; margin-bottom: 12px;">💳</div>
                <h3 style="margin: 0 0 8px; color: #1a2d4e;">No Bills or Payments Found</h3>
                <p style="color: #6b7280; margin: 0;">You have no active or historical billing statements on file.</p>
            </div>
        <?php else: ?>
            <div class="manage-table-container">
                <table class="manage-table">
                    <thead>
                        <tr>
                            <th>Appointment Date</th>
                            <th>Doctor</th>
                            <th>Service</th>
                            <th>Amount</th>
                            <th>Date & Time</th>
                            <th>Method</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bill_payments as $payment): ?>
                            <tr>
                                <td><?php echo $payment['appointment_date'] ? date('M d, Y', strtotime($payment['appointment_date'])) : 'N/A'; ?></td>
                                <td><strong><?php echo htmlspecialchars($payment['doctor_name'] ?? 'Not Assigned'); ?></strong></td>
                                <td><?php echo htmlspecialchars($payment['service_name'] ?? 'Consultation'); ?></td>
                                <td><strong>LKR <?php echo number_format($payment['amount'], 2); ?></strong></td>
                                <td><?php echo date('M d, Y - h:i A', strtotime($payment['payment_date'])); ?></td>
                                <td><?php echo htmlspecialchars(ucfirst($payment['payment_method'])); ?></td>
                                <td>
                                    <?php 
                                    $p_status = strtolower($payment['status']);
                                    $badge_class = 'badge-' . $p_status;
                                    ?>
                                    <span class="status-badge <?php echo $badge_class; ?>">
                                        <?php echo htmlspecialchars(ucfirst($payment['status'])); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

    </div>
</div>

<footer class="internal-footer">
    &copy; 2025 Care Compass Hospitals &nbsp;|&nbsp; <a href="patient_portal.php">Patient Portal</a>
</footer>

</body>
</html>
