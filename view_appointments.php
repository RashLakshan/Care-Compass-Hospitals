<?php
session_start();

// Check if user is logged in and is a patient
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: login.php"); 
    exit();
}

// Database connection
include 'db_connect.php';

// Fetch patient details and their appointments
$patient_id = $_SESSION['user_id'];

$sql_appointments = "SELECT a.id, a.appointment_date, a.status, d.name AS doctor_name, s.name AS service_name 
                    FROM appointments a 
                    LEFT JOIN doctors d ON a.doctor_id = d.id 
                    LEFT JOIN services s ON a.service_id = s.id 
                    WHERE a.patient_id = ? AND a.status != 'cancelled'
                    ORDER BY a.appointment_date DESC";
$stmt = mysqli_prepare($conn, $sql_appointments);
mysqli_stmt_bind_param($stmt, "i", $patient_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$appointments = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $appointments[] = $row;
    }
} else {
    echo "Error fetching appointments: " . mysqli_error($conn);
}
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Appointments — Care Compass Hospital</title>
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
        <div class="internal-breadcrumb"><a href="patient_portal.php">Patient Portal</a> &rsaquo; Appointments</div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
            <div class="internal-page-title" style="margin-bottom: 0;">My Appointments</div>
            <a href="appointments.php" class="btn-action-primary">+ Book New Appointment</a>
        </div>

        <?php if (empty($appointments)): ?>
            <div class="dashboard-card" style="text-align: center; padding: 48px 24px;">
                <div style="font-size: 48px; margin-bottom: 12px;">📅</div>
                <h3 style="margin: 0 0 8px; color: #1a2d4e;">No Active Appointments Found</h3>
                <p style="color: #6b7280; margin: 0 0 20px;">You currently do not have any scheduled appointments.</p>
                <div>
                    <a href="appointments.php" class="btn-action-primary">Schedule Your First Appointment</a>
                </div>
            </div>
        <?php else: ?>
            <div class="manage-table-container">
                <table class="manage-table">
                    <thead>
                        <tr>
                            <th>Doctor</th>
                            <th>Service</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($appointments as $appointment): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($appointment['doctor_name'] ?? 'Not Assigned'); ?></strong></td>
                                <td><?php echo htmlspecialchars($appointment['service_name'] ?? 'General Consultation'); ?></td>
                                <td><?php echo date('M d, Y - h:i A', strtotime($appointment['appointment_date'])); ?></td>
                                <td>
                                    <?php 
                                    $status = strtolower($appointment['status']);
                                    $badge_class = 'badge-' . $status;
                                    ?>
                                    <span class="status-badge <?php echo $badge_class; ?>">
                                        <?php echo htmlspecialchars(ucfirst($appointment['status'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($status === 'pending'): ?>
                                        <a href="cancel_appointment.php?id=<?php echo $appointment['id']; ?>" class="btn-action-cancel" onclick="return confirm('Are you sure you want to cancel this appointment?')">Cancel</a>
                                    <?php else: ?>
                                        <span style="color: #9ca3af; font-size: 13px;">—</span>
                                    <?php endif; ?>
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
