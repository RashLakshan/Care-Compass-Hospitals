<?php
// Start session
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: login.php"); 
    exit();
}

// Database connection
include 'db_connect.php';

$patient_id = $_SESSION['user_id'];

// Handle form submission for booking a new appointment
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['book_appointment'])) {
    $doctor_id = mysqli_real_escape_string($conn, $_POST['doctor_id']);
    $service_id = mysqli_real_escape_string($conn, $_POST['service_id']) ?: null;
    $appointment_date = mysqli_real_escape_string($conn, $_POST['appointment_date']);

    $sql = "INSERT INTO appointments (patient_id, doctor_id, service_id, appointment_date, status) VALUES (?, ?, ?, ?, 'pending')";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iiss", $patient_id, $doctor_id, $service_id, $appointment_date);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Appointment booked successfully.'); window.location.href = 'appointments.php';</script>";
    } else {
        echo "<script>alert('Error booking appointment: " . mysqli_error($conn) . "');</script>";
    }
    mysqli_stmt_close($stmt);
}

// Fetch all appointments for the patient 
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

// Fetch available doctors and services for the booking form
$doctors = [];
$services = [];

$sql_doctors = "SELECT id, name FROM doctors";
$result_doctors = mysqli_query($conn, $sql_doctors);
if ($result_doctors) {
    while ($row = mysqli_fetch_assoc($result_doctors)) {
        $doctors[] = $row;
    }
}

$sql_services = "SELECT id, name FROM services";
$result_services = mysqli_query($conn, $sql_services);
if ($result_services) {
    while ($row = mysqli_fetch_assoc($result_services)) {
        $services[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment — Care Compass Hospital</title>
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
        <div class="internal-breadcrumb"><a href="patient_portal.php">Patient Portal</a> &rsaquo; Book Appointment</div>
        <div class="internal-page-title">Book an Appointment</div>

        <!-- Book New Appointment Form -->
        <div class="dashboard-card" style="margin-bottom: 32px;">
            <div class="dashboard-card-header">
                <span class="card-header-icon">🗓️</span>
                <h3>Schedule New Appointment</h3>
            </div>
            <form method="POST" id="appointmentForm" class="modern-feedback-form">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 18px;">
                    <div class="form-group">
                        <label for="doctor_id">Doctor <span style="color:#dc3545;">*</span></label>
                        <select name="doctor_id" id="doctor_id" required class="form-input-modern">
                            <option value="">Select a Doctor</option>
                            <?php foreach ($doctors as $doctor): ?>
                                <option value="<?php echo htmlspecialchars($doctor['id']); ?>" <?php echo (isset($_GET['doctor_id']) && $_GET['doctor_id'] == $doctor['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($doctor['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="service_id">Medical Service (Optional)</label>
                        <select name="service_id" id="service_id" class="form-input-modern">
                            <option value="">None / General Consultation</option>
                            <?php foreach ($services as $service): ?>
                                <option value="<?php echo htmlspecialchars($service['id']); ?>">
                                    <?php echo htmlspecialchars($service['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="appointment_date">Preferred Date & Time <span style="color:#dc3545;">*</span></label>
                        <input type="datetime-local" name="appointment_date" id="appointment_date" required class="form-input-modern">
                    </div>
                </div>

                <div style="margin-top: 10px;">
                    <input type="submit" name="book_appointment" value="Book Appointment" class="btn-submit-modern">
                </div>
            </form>
        </div>

        <!-- Existing Appointments List -->
        <div class="internal-page-title">My Scheduled Appointments</div>
        <?php if (empty($appointments)): ?>
            <div class="dashboard-card" style="text-align: center; padding: 36px 20px;">
                <p style="color: #6b7280; margin: 0;">You have no active appointments scheduled at this moment.</p>
            </div>
        <?php else: ?>
            <div class="manage-table-container">
                <table class="manage-table">
                    <thead>
                        <tr>
                            <th>Doctor</th>
                            <th>Service</th>
                            <th>Appointment Date & Time</th>
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
                                    $app_status = strtolower($appointment['status']);
                                    $badge_class = 'badge-' . $app_status;
                                    ?>
                                    <span class="status-badge <?php echo $badge_class; ?>">
                                        <?php echo htmlspecialchars(ucfirst($appointment['status'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($app_status === 'pending'): ?>
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

<script src="scripts.js"></script>
</body>
</html>
