<?php
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php"); 
    exit();
}

include '../includes/db_connect.php';

// Ensure the medical_records, doctors, and users tables exist 
$sql_check_medical_records = "SHOW TABLES LIKE 'medical_records'";
$result_medical_records = mysqli_query($conn, $sql_check_medical_records);
if (mysqli_num_rows($result_medical_records) == 0) {
    $sql_create_medical_records = "CREATE TABLE medical_records (
        id INT AUTO_INCREMENT PRIMARY KEY,
        patient_id INT NOT NULL,
        doctor_id INT NOT NULL,
        service_id INT,
        diagnosis TEXT NOT NULL,
        treatment TEXT NOT NULL,
        date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (patient_id) REFERENCES users(id),
        FOREIGN KEY (doctor_id) REFERENCES doctors(id),
        FOREIGN KEY (service_id) REFERENCES services(id)
    )";
    if (!mysqli_query($conn, $sql_create_medical_records)) {
        echo "Error creating medical_records table: " . mysqli_error($conn);
        exit();
    }
}

// Ensure doctors table exists 
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

// Ensure services table exists 
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

// Handle medical record deletion
if (isset($_GET['delete_id'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $sql = "DELETE FROM medical_records WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $delete_id);
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Medical record deleted successfully.'); window.location.href = 'manage_medical_records.php';</script>";
    } else {
        echo "<script>alert('Error deleting medical record: " . mysqli_error($conn) . "');</script>";
    }
    mysqli_stmt_close($stmt);
}

// Fetch all medical records with patient, doctor, and service details
$sql = "SELECT mr.id, mr.patient_id, mr.doctor_id, mr.service_id, mr.diagnosis, mr.treatment, mr.date_added, u.name AS patient_name, d.name AS doctor_name, s.name AS service_name 
        FROM medical_records mr 
        LEFT JOIN users u ON mr.patient_id = u.id 
        LEFT JOIN doctors d ON mr.doctor_id = d.id 
        LEFT JOIN services s ON mr.service_id = s.id";
$result = mysqli_query($conn, $sql);
$medical_records = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $medical_records[] = $row;
    }
} else {
    echo "Error fetching medical records: " . mysqli_error($conn);
}

// Find medical record to edit if edit_id is set
$edit_record = null;
if (isset($_GET['edit_id'])) {
    foreach ($medical_records as $rec) {
        if ($rec['id'] == $_GET['edit_id']) {
            $edit_record = $rec;
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

// Handle add/edit medical record form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST['add_medical_record']) || isset($_POST['edit_medical_record']))) {
    $patient_id = mysqli_real_escape_string($conn, $_POST['patient_id']);
    $doctor_id = mysqli_real_escape_string($conn, $_POST['doctor_id']);
    $service_id = mysqli_real_escape_string($conn, $_POST['service_id']) ?: null; // Allow NULL if empty
    $diagnosis = mysqli_real_escape_string($conn, $_POST['diagnosis']);
    $treatment = mysqli_real_escape_string($conn, $_POST['treatment']);
    $medical_record_id = isset($_POST['medical_record_id']) ? mysqli_real_escape_string($conn, $_POST['medical_record_id']) : null;

    if ($medical_record_id) { 
        $sql = "UPDATE medical_records SET patient_id = ?, doctor_id = ?, service_id = ?, diagnosis = ?, treatment = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iisssi", $patient_id, $doctor_id, $service_id, $diagnosis, $treatment, $medical_record_id);
    } else { // Add new medical record
        $sql = "INSERT INTO medical_records (patient_id, doctor_id, service_id, diagnosis, treatment) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iisss", $patient_id, $doctor_id, $service_id, $diagnosis, $treatment);
    }

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Medical record " . ($medical_record_id ? 'updated' : 'added') . " successfully.'); window.location.href = 'manage_medical_records.php';</script>";
    } else {
        echo "<script>alert('Error " . ($medical_record_id ? 'updating' : 'adding') . " medical record: " . mysqli_error($conn) . "');</script>";
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Care Compass Hospitals - Manage Medical Records</title>
    <link rel="stylesheet" href="../assets/css/admin_styles.css">
</head>
<body>
    <?php
    $portal_home  = 'portal.php';
    $portal_label = 'Admin Panel';
    $base_path    = '../';
    include '../includes/header.php';
    ?>

    <!-- Manage Medical Records Content -->
    <div class="internal-body">
        <div class="internal-manage-wrap">
            <div class="internal-breadcrumb"><a href="portal.php">Admin Panel</a> &rsaquo; Medical Records</div>
            <div class="internal-page-title">Manage Medical Records</div>

            <div class="container">
                <div class="vertical-center">
                    <h1 class="text-center mb-4" style="color: #007bff; font-weight: bolder;">Manage Medical Records</h1>

                    <!-- Add/Edit Medical Record Form -->
                    <div class="manage-form" id="manageForm">
                        <h2 class="text-center mb-4" style="color: #007bff; font-weight: bolder;"><?php echo $edit_record ? 'Edit Medical Record' : 'Add New Medical Record'; ?></h2>
                        <form method="POST" id="medicalRecordForm">
                            <input type="hidden" name="medical_record_id" value="<?php echo $edit_record ? htmlspecialchars($edit_record['id']) : ''; ?>">
                            <label for="patient_id">Patient:</label>
                            <select name="patient_id" id="patient_id" required class="form-input">
                                <option value="">Select a Patient</option>
                                <?php foreach ($patients as $patient): ?>
                                    <option value="<?php echo htmlspecialchars($patient['id']); ?>" <?php echo ($edit_record && $edit_record['patient_id'] == $patient['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($patient['name']); ?></option>
                                <?php endforeach; ?>
                            </select><br><br>

                            <label for="doctor_id">Doctor:</label>
                            <select name="doctor_id" id="doctor_id" required class="form-input">
                                <option value="">Select a Doctor</option>
                                <?php foreach ($doctors as $doctor): ?>
                                    <option value="<?php echo htmlspecialchars($doctor['id']); ?>" <?php echo ($edit_record && $edit_record['doctor_id'] == $doctor['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($doctor['name']); ?></option>
                                <?php endforeach; ?>
                            </select><br><br>

                            <label for="service_id">Service (optional):</label>
                            <select name="service_id" id="service_id" class="form-input">
                                <option value="">None</option>
                                <?php foreach ($services as $service): ?>
                                    <option value="<?php echo htmlspecialchars($service['id']); ?>" <?php echo ($edit_record && $edit_record['service_id'] == $service['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($service['name']); ?></option>
                                <?php endforeach; ?>
                            </select><br><br>

                            <label for="diagnosis">Diagnosis:</label>
                            <textarea name="diagnosis" id="diagnosis" required placeholder="Enter diagnosis" class="form-input" rows="4"><?php echo $edit_record ? htmlspecialchars($edit_record['diagnosis']) : ''; ?></textarea><br><br>

                            <label for="treatment">Treatment:</label>
                            <textarea name="treatment" id="treatment" required placeholder="Enter treatment" class="form-input" rows="4"><?php echo $edit_record ? htmlspecialchars($edit_record['treatment']) : ''; ?></textarea><br><br>

                            <input type="submit" name="<?php echo $edit_record ? 'edit_medical_record' : 'add_medical_record'; ?>" value="<?php echo $edit_record ? 'Update Medical Record' : 'Add Medical Record'; ?>" class="btn">
                            <button type="button" class="btn" onclick="cancelForm()">Cancel</button>
                        </form>
                    </div>

                    <!-- Medical Record List Table -->
                    <div class="manage-table-container" id="medicalRecordList" style="display: <?php echo isset($_GET['edit_id']) || isset($_GET['add']) ? 'none' : 'block'; ?>;">
                        <h2 class="text-center mb-4" style="color: #007bff; font-weight: bolder;">Medical Record List</h2>
                        <table class="manage-table">
                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>Doctor</th>
                                    <th>Service</th>
                                    <th>Diagnosis</th>
                                    <th>Treatment</th>
                                    <th>Date Added</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($medical_records as $record): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($record['patient_name'] ?? 'None'); ?></td>
                                        <td><?php echo htmlspecialchars($record['doctor_name'] ?? 'None'); ?></td>
                                        <td><?php echo htmlspecialchars($record['service_name'] ?? 'None'); ?></td>
                                        <td><?php echo htmlspecialchars(substr($record['diagnosis'], 0, 50)) . (strlen($record['diagnosis']) > 50 ? '...' : ''); ?></td>
                                        <td><?php echo htmlspecialchars(substr($record['treatment'], 0, 50)) . (strlen($record['treatment']) > 50 ? '...' : ''); ?></td>
                                        <td><?php echo date('Y-m-d H:i', strtotime($record['date_added'])); ?></td>
                                        <td>
                                            <a href="manage_medical_records.php?edit_id=<?php echo $record['id']; ?>" class="portal-btn" style="background-color: #007bff; width: 80px; padding: 8px 15px;">Edit</a>
                                            <a href="manage_medical_records.php?delete_id=<?php echo $record['id']; ?>" class="portal-btn" style="background-color: #dc3545; width: 80px; padding: 8px 15px;" onclick="return confirm('Are you sure you want to delete this medical record?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <a href="manage_medical_records.php?add=1" class="portal-btn mt-4">Add New Medical Record</a>
                    </div>
                </div>
            </div>
        </div><!-- /internal-manage-wrap -->
    </div><!-- /internal-body -->

    <footer class="internal-footer">
        &copy; 2025 Care Compass Hospitals &nbsp;|&nbsp; <a href="portal.php">Admin Panel</a>
    </footer>

    <!-- Custom JS -->
    <script src="../assets/js/scripts.js"></script>
    <script>
        function cancelForm() {
            window.location.href = 'manage_medical_records.php';
        }
    </script>
</body>
</html>
