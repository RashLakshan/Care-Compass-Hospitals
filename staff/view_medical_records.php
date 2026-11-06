<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header("Location: ../auth/login.php"); 
    exit();
}

// Database connection
include '../includes/db_connect.php';

// Fetch all medical records for staff review
$sql_medical_records = "SELECT mr.id, mr.diagnosis, mr.treatment, mr.date_added, u.name AS patient_name, d.name AS doctor_name, s.name AS service_name 
                       FROM medical_records mr 
                       LEFT JOIN users u ON mr.patient_id = u.id 
                       LEFT JOIN doctors d ON mr.doctor_id = d.id 
                       LEFT JOIN services s ON mr.service_id = s.id 
                       ORDER BY mr.date_added DESC";
$result = mysqli_query($conn, $sql_medical_records);
$medical_records = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $medical_records[] = $row;
    }
} else {
    echo "Error fetching medical records: " . mysqli_error($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Care Compass Hospitals - View Medical Records</title>
    <link rel="stylesheet" href="../assets/css/admin_styles.css">
</head>
<body>
    <?php
    $portal_home  = 'portal.php';
    $portal_label = 'Staff Portal';
    $base_path    = '../';
    include '../includes/header.php';
    ?>

    <!-- View Medical Records Content -->
    <div class="internal-body">
        <div class="internal-manage-wrap">
            <div class="internal-breadcrumb"><a href="portal.php">Staff Portal</a> &rsaquo; Medical Records</div>
            <div class="internal-page-title">Medical Records</div>
            <div class="container">
                <div class="vertical-center">
                    <?php if (empty($medical_records)): ?>
                        <div class="dashboard-card" style="text-align: center; padding: 48px 24px;">
                            <div style="font-size: 48px; margin-bottom: 12px;">📋</div>
                            <h3 style="margin: 0 0 8px; color: #1a2d4e;">No Medical Records Available</h3>
                            <p style="color: #6b7280; margin: 0;">There are currently no medical records on file.</p>
                        </div>
                    <?php else: ?>
                        <div class="manage-table-container">
                            <table class="manage-table">
                                <thead>
                                    <tr>
                                        <th>Patient</th>
                                        <th>Doctor</th>
                                        <th>Service</th>
                                        <th>Diagnosis</th>
                                        <th>Treatment</th>
                                        <th>Date Added</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($medical_records as $record): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($record['patient_name'] ?? 'Not Assigned'); ?></td>
                                            <td><?php echo htmlspecialchars($record['doctor_name'] ?? 'Not Assigned'); ?></td>
                                            <td><?php echo htmlspecialchars($record['service_name'] ?? 'None'); ?></td>
                                            <td><?php echo htmlspecialchars(substr($record['diagnosis'], 0, 50)) . (strlen($record['diagnosis']) > 50 ? '...' : ''); ?></td>
                                            <td><?php echo htmlspecialchars(substr($record['treatment'], 0, 50)) . (strlen($record['treatment']) > 50 ? '...' : ''); ?></td>
                                            <td><?php echo date('Y-m-d H:i', strtotime($record['date_added'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div><!-- /internal-manage-wrap -->
    </div><!-- /internal-body -->

    <footer class="internal-footer">
        &copy; 2025 Care Compass Hospitals &nbsp;|&nbsp; <a href="portal.php">Staff Portal</a>
    </footer>

    <script src="../assets/js/scripts.js"></script>
</body>
</html>
