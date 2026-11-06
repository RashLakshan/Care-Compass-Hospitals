<?php
// Start session
session_start();

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff')) {
    header("Location: ../auth/login.php");
    exit();
}

include '../includes/db_connect.php';

// Ensure tables exist
$sql_check_appointments = "SHOW TABLES LIKE 'appointments'";
$result_appointments = mysqli_query($conn, $sql_check_appointments);
if (mysqli_num_rows($result_appointments) == 0) {
    $sql_create_appointments = "CREATE TABLE appointments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        patient_id INT NOT NULL,
        doctor_id INT NOT NULL,
        service_id INT,
        appointment_date DATETIME NOT NULL,
        status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
        FOREIGN KEY (patient_id) REFERENCES users(id),
        FOREIGN KEY (doctor_id) REFERENCES doctors(id),
        FOREIGN KEY (service_id) REFERENCES services(id)
    )";
    if (!mysqli_query($conn, $sql_create_appointments)) {
        echo "Error creating appointments table: " . mysqli_error($conn);
        exit();
    }
}

$sql_check_doctors = "SHOW TABLES LIKE 'doctors'";
$result_doctors = mysqli_query($conn, $sql_check_doctors);
if (mysqli_num_rows($result_doctors) == 0) {
    $sql_create_doctors = "CREATE TABLE doctors (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        specialty VARCHAR(100) NOT NULL,
        location VARCHAR(100) NOT NULL,
        contact VARCHAR(20) NOT NULL,
        photo VARCHAR(255) DEFAULT NULL
    )";
    if (!mysqli_query($conn, $sql_create_doctors)) {
        echo "Error creating doctors table: " . mysqli_error($conn);
        exit();
    }
}

$sql_check_services = "SHOW TABLES LIKE 'services'";
$result_services = mysqli_query($conn, $sql_check_services);
if (mysqli_num_rows($result_services) == 0) {
    $sql_create_services = "CREATE TABLE services (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        description TEXT NOT NULL,
        category VARCHAR(50) NOT NULL
    )";
    if (!mysqli_query($conn, $sql_create_services)) {
        echo "Error creating services table: " . mysqli_error($conn);
        exit();
    }
}

// Handle appointment deletion
if (isset($_GET['delete_id'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $sql = "DELETE FROM appointments WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $delete_id);
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Appointment deleted successfully.'); window.location.href = 'manage_appointments.php';</script>";
    } else {
        echo "<script>alert('Error deleting appointment: " . mysqli_error($conn) . "');</script>";
    }
    mysqli_stmt_close($stmt);
}

// Fetch all appointments with patient, doctor, and service details
$sql = "SELECT a.id, a.patient_id, a.doctor_id, a.service_id, a.appointment_date, a.status, u.name AS patient_name, d.name AS doctor_name, s.name AS service_name 
        FROM appointments a 
        LEFT JOIN users u ON a.patient_id = u.id 
        LEFT JOIN doctors d ON a.doctor_id = d.id 
        LEFT JOIN services s ON a.service_id = s.id";
$result = mysqli_query($conn, $sql);
$appointments = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $appointments[] = $row;
    }
} else {
    echo "Error fetching appointments: " . mysqli_error($conn);
}

// Find appointment to edit if edit_id is set
$edit_appointment = null;
if (isset($_GET['edit_id'])) {
    foreach ($appointments as $app) {
        if ($app['id'] == $_GET['edit_id']) {
            $edit_appointment = $app;
            break;
        }
    }
}

// Fetch lists for dropdowns in the form
$patients = [];
$doctors = [];
$services = [];

$sql_patients = "SELECT id, name FROM users WHERE role = 'patient'";
$result_patients = mysqli_query($conn, $sql_patients);
if ($result_patients) {
    while ($row = mysqli_fetch_assoc($result_patients)) {
        $patients[] = $row;
    }
}

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

// Handle add/edit appointment form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST['add_appointment']) || isset($_POST['edit_appointment']))) {
    $patient_id = mysqli_real_escape_string($conn, $_POST['patient_id']);
    $doctor_id = mysqli_real_escape_string($conn, $_POST['doctor_id']);
    $service_id = mysqli_real_escape_string($conn, $_POST['service_id']) ?: null; // Allow NULL if empty
    $appointment_date = mysqli_real_escape_string($conn, $_POST['appointment_date']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $appointment_id = isset($_POST['appointment_id']) ? mysqli_real_escape_string($conn, $_POST['appointment_id']) : null;

    if ($appointment_id) { // Update existing appointment
        $sql = "UPDATE appointments SET patient_id = ?, doctor_id = ?, service_id = ?, appointment_date = ?, status = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iissis", $patient_id, $doctor_id, $service_id, $appointment_date, $status, $appointment_id);
    } else { // Add new appointment
        $sql = "INSERT INTO appointments (patient_id, doctor_id, service_id, appointment_date, status) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iisss", $patient_id, $doctor_id, $service_id, $appointment_date, $status);
    }

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Appointment " . ($appointment_id ? 'updated' : 'added') . " successfully.'); window.location.href = 'manage_appointments.php';</script>";
    } else {
        echo "<script>alert('Error " . ($appointment_id ? 'updating' : 'adding') . " appointment: " . mysqli_error($conn) . "');</script>";
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Care Compass Hospitals - Manage Appointments</title>
    <link rel="stylesheet" href="../assets/css/admin_styles.css">
</head>
<body>
    <?php
    $portal_home  = ($_SESSION['role'] === 'staff') ? '../staff/portal.php' : 'portal.php';
    $portal_label = ($_SESSION['role'] === 'staff') ? 'Staff Portal' : 'Admin Panel';
    $base_path    = '../';
    include '../includes/header.php';
    ?>

    <!-- Manage Appointments Content -->
    <div class="internal-body">
        <div class="internal-manage-wrap">
            <div class="internal-breadcrumb"><a href="<?php echo $portal_home; ?>"><?php echo $portal_label; ?></a> &rsaquo; Appointments</div>
            <div class="internal-page-title">Manage Appointments</div>

            <div class="container">
                <div class="vertical-center">
                    <h1 class="text-center mb-4" style="color: #007bff; font-weight: bolder;">Manage Appointments</h1>

                    <!-- Add/Edit Appointment Form -->
                    <div class="manage-form" id="manageForm">
                        <h2 class="text-center mb-4" style="color: #007bff; font-weight: bolder;"><?php echo $edit_appointment ? 'Edit Appointment' : 'Add New Appointment'; ?></h2>
                        <form method="POST" id="appointmentForm">
                            <input type="hidden" name="appointment_id" value="<?php echo $edit_appointment ? htmlspecialchars($edit_appointment['id']) : ''; ?>">
                            <label for="patient_id">Patient:</label>
                            <select name="patient_id" id="patient_id" required class="form-input">
                                <option value="">Select a Patient</option>
                                <?php foreach ($patients as $patient): ?>
                                    <option value="<?php echo htmlspecialchars($patient['id']); ?>" <?php echo ($edit_appointment && $edit_appointment['patient_id'] == $patient['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($patient['name']); ?></option>
                                <?php endforeach; ?>
                            </select><br><br>

                            <label for="doctor_id">Doctor:</label>
                            <select name="doctor_id" id="doctor_id" required class="form-input">
                                <option value="">Select a Doctor</option>
                                <?php foreach ($doctors as $doctor): ?>
                                    <option value="<?php echo htmlspecialchars($doctor['id']); ?>" <?php echo ($edit_appointment && $edit_appointment['doctor_id'] == $doctor['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($doctor['name']); ?></option>
                                <?php endforeach; ?>
                            </select><br><br>

                            <label for="service_id">Service (optional):</label>
                            <select name="service_id" id="service_id" class="form-input">
                                <option value="">None</option>
                                <?php foreach ($services as $service): ?>
                                    <option value="<?php echo htmlspecialchars($service['id']); ?>" <?php echo ($edit_appointment && $edit_appointment['service_id'] == $service['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($service['name']); ?></option>
                                <?php endforeach; ?>
                            </select><br><br>

                            <label for="appointment_date">Appointment Date &amp; Time:</label>
                            <input type="datetime-local" name="appointment_date" id="appointment_date" required class="form-input" value="<?php echo ($edit_appointment && !empty($edit_appointment['appointment_date'])) ? date('Y-m-d\TH:i', strtotime($edit_appointment['appointment_date'])) : ''; ?>"><br><br>

                            <label for="status">Status:</label>
                            <select name="status" id="status" required class="form-input">
                                <option value="pending" <?php echo ($edit_appointment && $edit_appointment['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="confirmed" <?php echo ($edit_appointment && $edit_appointment['status'] == 'confirmed') ? 'selected' : ''; ?>>Confirmed</option>
                                <option value="cancelled" <?php echo ($edit_appointment && $edit_appointment['status'] == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                            </select><br><br>

                            <input type="submit" name="<?php echo $edit_appointment ? 'edit_appointment' : 'add_appointment'; ?>" value="<?php echo $edit_appointment ? 'Update Appointment' : 'Add Appointment'; ?>" class="btn">
                            <button type="button" class="btn" onclick="cancelForm()">Cancel</button>
                        </form>
                    </div>

                    <!-- Appointment List Table -->
                    <div class="manage-table-container" id="appointmentList" style="display: <?php echo isset($_GET['edit_id']) || isset($_GET['add']) ? 'none' : 'block'; ?>;">
                        <h2 class="text-center mb-4" style="color: #007bff; font-weight: bolder;">Appointment List</h2>
                        <table class="manage-table">
                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>Doctor</th>
                                    <th>Service</th>
                                    <th>Appointment Date &amp; Time</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($appointments as $appointment): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($appointment['patient_name'] ?? 'None'); ?></td>
                                        <td><?php echo htmlspecialchars($appointment['doctor_name'] ?? 'None'); ?></td>
                                        <td><?php echo htmlspecialchars($appointment['service_name'] ?? 'None'); ?></td>
                                        <td><?php echo date('Y-m-d H:i', strtotime($appointment['appointment_date'])); ?></td>
                                        <td><?php echo htmlspecialchars($appointment['status'] ?? 'Pending'); ?></td>
                                        <td>
                                            <a href="manage_appointments.php?edit_id=<?php echo $appointment['id']; ?>" class="portal-btn" style="background-color: #007bff; width: 80px; padding: 8px 15px;">Edit</a>
                                            <a href="manage_appointments.php?delete_id=<?php echo $appointment['id']; ?>" class="portal-btn" style="background-color: #dc3545; width: 80px; padding: 8px 15px;" onclick="return confirm('Are you sure you want to delete this appointment?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <a href="manage_appointments.php?add=1" class="portal-btn mt-4">Add New Appointment</a>
                    </div>
                </div>
            </div>
        </div><!-- /internal-manage-wrap -->
    </div><!-- /internal-body -->

    <footer class="internal-footer">
        &copy; 2025 Care Compass Hospitals &nbsp;|&nbsp; <a href="<?php echo $portal_home; ?>"><?php echo $portal_label; ?></a>
    </footer>

    <!-- Custom JS -->
    <script src="../assets/js/scripts.js"></script>
    <script>
        function cancelForm() {
            window.location.href = 'manage_appointments.php';
        }
    </script>
</body>
</html>
