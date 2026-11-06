<?php
session_start();

// Check if user is logged in and is a patient
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: ../auth/login.php"); 
    exit();
}

// Database connection
include '../includes/db_connect.php';

// Fetch patient details and their medical records
$patient_id = $_SESSION['user_id'];

$sql_medical_records = "SELECT mr.id, mr.diagnosis, mr.treatment, mr.date_added, d.name AS doctor_name, s.name AS service_name 
                       FROM medical_records mr 
                       LEFT JOIN doctors d ON mr.doctor_id = d.id 
                       LEFT JOIN services s ON mr.service_id = s.id 
                       WHERE mr.patient_id = ?
                       ORDER BY mr.date_added DESC";
$stmt = mysqli_prepare($conn, $sql_medical_records);
mysqli_stmt_bind_param($stmt, "i", $patient_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$medical_records = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $medical_records[] = $row;
    }
} else {
    echo "Error fetching medical records: " . mysqli_error($conn);
}
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Medical Records — Care Compass Hospital</title>
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
    <div class="internal-manage-wrap">
        <div class="internal-breadcrumb"><a href="portal.php">Patient Portal</a> &rsaquo; Medical Records</div>
        <div class="internal-page-title">My Medical Records</div>

        <?php if (empty($medical_records)): ?>
            <div class="dashboard-card" style="text-align: center; padding: 48px 24px;">
                <div style="font-size: 48px; margin-bottom: 12px;">📋</div>
                <h3 style="margin: 0 0 8px; color: #1a2d4e;">No Medical Records Available</h3>
                <p style="color: #6b7280; margin: 0;">You do not have any recorded medical consultations or diagnosis reports yet.</p>
            </div>
        <?php else: ?>
            <div class="manage-table-container">
                <table class="manage-table">
                    <thead>
                        <tr>
                            <th>Doctor</th>
                            <th>Service</th>
                            <th>Diagnosis</th>
                            <th>Treatment Plan</th>
                            <th>Date Recorded</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($medical_records as $record): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($record['doctor_name'] ?? 'Not Assigned'); ?></strong></td>
                                <td><?php echo htmlspecialchars($record['service_name'] ?? 'General Consultation'); ?></td>
                                <td><?php echo htmlspecialchars($record['diagnosis']); ?></td>
                                <td><?php echo htmlspecialchars($record['treatment']); ?></td>
                                <td><?php echo date('M d, Y - h:i A', strtotime($record['date_added'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

    </div>
</div>

<footer class="internal-footer">
    &copy; 2025 Care Compass Hospitals &nbsp;|&nbsp; <a href="portal.php">Patient Portal</a>
</footer>

</body>
</html>

