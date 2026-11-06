<?php
session_start();

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff')) {
    header("Location: ../auth/login.php");
    exit();
}

// Database connection
include '../includes/db_connect.php';

$sql_check_bill_payments = "SHOW TABLES LIKE 'bill_payments'";
$result_bill_payments = mysqli_query($conn, $sql_check_bill_payments);
if (mysqli_num_rows($result_bill_payments) == 0) {
    $sql_create_bill_payments = "CREATE TABLE bill_payments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        appointment_id INT NOT NULL,
        patient_id INT NOT NULL,
        amount DECIMAL(10, 2) NOT NULL,
        payment_date DATETIME NOT NULL,
        payment_method ENUM('cash', 'card', 'online') NOT NULL,
        status ENUM('pending', 'paid', 'overdue') DEFAULT 'pending',
        FOREIGN KEY (appointment_id) REFERENCES appointments(id),
        FOREIGN KEY (patient_id) REFERENCES users(id)
    )";
    if (!mysqli_query($conn, $sql_create_bill_payments)) {
        echo "Error creating bill_payments table: " . mysqli_error($conn);
        exit();
    }
}

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

$sql_check_users = "SHOW TABLES LIKE 'users'";
$result_users = mysqli_query($conn, $sql_check_users);
if (mysqli_num_rows($result_users) == 0) {
    $sql_create_users = "CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        username VARCHAR(50) NOT NULL,
        mobile VARCHAR(20) NOT NULL,
        email VARCHAR(100) NOT NULL,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'staff', 'patient') DEFAULT 'patient'
    )";
    if (!mysqli_query($conn, $sql_create_users)) {
        echo "Error creating users table: " . mysqli_error($conn);
        exit();
    }
}

// Handle bill payment deletion
if (isset($_GET['delete_id'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $sql = "DELETE FROM bill_payments WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $delete_id);
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Bill payment deleted successfully.'); window.location.href = 'manage_bill_payments.php';</script>";
    } else {
        echo "<script>alert('Error deleting bill payment: " . mysqli_error($conn) . "');</script>";
    }
    mysqli_stmt_close($stmt);
}

// Fetch all bill payments with patient and appointment details
$sql = "SELECT bp.id, bp.appointment_id, bp.patient_id, bp.amount, bp.payment_date, bp.payment_method, bp.status, u.name AS patient_name, a.appointment_date AS appointment_date 
        FROM bill_payments bp 
        LEFT JOIN users u ON bp.patient_id = u.id 
        LEFT JOIN appointments a ON bp.appointment_id = a.id";
$result = mysqli_query($conn, $sql);
$bill_payments = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $bill_payments[] = $row;
    }
} else {
    echo "Error fetching bill payments: " . mysqli_error($conn);
}

// Find bill payment to edit if edit_id is set
$edit_payment = null;
if (isset($_GET['edit_id'])) {
    foreach ($bill_payments as $bp) {
        if ($bp['id'] == $_GET['edit_id']) {
            $edit_payment = $bp;
            break;
        }
    }
}

// Fetch lists for dropdowns in the form
$patients = [];
$appointments = [];

$sql_patients = "SELECT id, name FROM users WHERE role = 'patient'";
$result_patients = mysqli_query($conn, $sql_patients);
if ($result_patients) {
    while ($row = mysqli_fetch_assoc($result_patients)) {
        $patients[] = $row;
    }
}

$sql_appointments = "SELECT a.id, a.appointment_date, a.patient_id, a.status, u.name AS patient_name 
                     FROM appointments a 
                     LEFT JOIN users u ON a.patient_id = u.id 
                     ORDER BY a.appointment_date DESC";
$result_appointments = mysqli_query($conn, $sql_appointments);
if ($result_appointments) {
    while ($row = mysqli_fetch_assoc($result_appointments)) {
        $appointments[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST['add_bill_payment']) || isset($_POST['edit_bill_payment']))) {
    $appointment_id = mysqli_real_escape_string($conn, $_POST['appointment_id']);
    $patient_id = mysqli_real_escape_string($conn, $_POST['patient_id']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);
    $payment_date = mysqli_real_escape_string($conn, $_POST['payment_date']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $bill_payment_id = isset($_POST['bill_payment_id']) ? mysqli_real_escape_string($conn, $_POST['bill_payment_id']) : null;

    if ($bill_payment_id) { // Update existing bill payment
        $sql = "UPDATE bill_payments SET appointment_id = ?, patient_id = ?, amount = ?, payment_date = ?, payment_method = ?, status = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iidsssi", $appointment_id, $patient_id, $amount, $payment_date, $payment_method, $status, $bill_payment_id);
    } else { // Add new bill payment
        $sql = "INSERT INTO bill_payments (appointment_id, patient_id, amount, payment_date, payment_method, status) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iidsss", $appointment_id, $patient_id, $amount, $payment_date, $payment_method, $status);
    }

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Bill payment " . ($bill_payment_id ? 'updated' : 'added') . " successfully.'); window.location.href = 'manage_bill_payments.php';</script>";
    } else {
        echo "<script>alert('Error " . ($bill_payment_id ? 'updating' : 'adding') . " bill payment: " . mysqli_error($conn) . "');</script>";
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Care Compass Hospitals - Manage Bill Payments</title>
    <link rel="stylesheet" href="../assets/css/admin_styles.css">
</head>
<body>
    <?php
    $portal_home  = ($_SESSION['role'] === 'staff') ? '../staff/portal.php' : 'portal.php';
    $portal_label = ($_SESSION['role'] === 'staff') ? 'Staff Portal' : 'Admin Panel';
    $base_path    = '../';
    include '../includes/header.php';
    ?>

    <!-- Manage Bill Payments Content -->
    <div class="internal-body">
        <div class="internal-manage-wrap">
            <div class="internal-breadcrumb"><a href="<?php echo $portal_home; ?>"><?php echo $portal_label; ?></a> &rsaquo; Bill Payments</div>
            <div class="internal-page-title">Manage Bill Payments</div>

            <div class="container">
                <div class="vertical-center">
                    <h1 class="text-center mb-4" style="color: #007bff; font-weight: bolder;">Manage Bill Payments</h1>

                    <div class="manage-form" id="manageForm">
                        <h2 class="text-center mb-4" style="color: #007bff; font-weight: bolder;"><?php echo $edit_payment ? 'Edit Bill Payment' : 'Add New Bill Payment'; ?></h2>
                        <form method="POST" id="billPaymentForm">
                            <input type="hidden" name="bill_payment_id" value="<?php echo $edit_payment ? htmlspecialchars($edit_payment['id']) : ''; ?>">
                            <label for="appointment_id">Appointment:</label>
                            <select name="appointment_id" id="appointment_id" required class="form-input">
                                <option value="">Select an Appointment</option>
                                <?php foreach ($appointments as $appointment): ?>
                                    <option value="<?php echo htmlspecialchars($appointment['id']); ?>" <?php echo ($edit_payment && $edit_payment['appointment_id'] == $appointment['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($appointment['appointment_date'])) . ' - ' . ($appointment['patient_name'] ?? 'Patient #' . $appointment['patient_id']) . ' (' . ucfirst($appointment['status']) . ')'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select><br><br>

                            <label for="patient_id">Patient:</label>
                            <select name="patient_id" id="patient_id" required class="form-input">
                                <option value="">Select a Patient</option>
                                <?php foreach ($patients as $patient): ?>
                                    <option value="<?php echo htmlspecialchars($patient['id']); ?>" <?php echo ($edit_payment && $edit_payment['patient_id'] == $patient['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($patient['name']); ?></option>
                                <?php endforeach; ?>
                            </select><br><br>

                            <label for="amount">Amount (LKR):</label>
                            <input type="number" name="amount" id="amount" step="0.01" required placeholder="Enter amount" class="form-input" value="<?php echo $edit_payment ? htmlspecialchars($edit_payment['amount']) : ''; ?>"><br><br>

                            <label for="payment_date">Payment Date &amp; Time:</label>
                            <input type="datetime-local" name="payment_date" id="payment_date" required class="form-input" value="<?php echo ($edit_payment && !empty($edit_payment['payment_date'])) ? date('Y-m-d\TH:i', strtotime($edit_payment['payment_date'])) : ''; ?>"><br><br>

                            <label for="payment_method">Payment Method:</label>
                            <select name="payment_method" id="payment_method" required class="form-input">
                                <option value="cash" <?php echo ($edit_payment && $edit_payment['payment_method'] == 'cash') ? 'selected' : ''; ?>>Cash</option>
                                <option value="card" <?php echo ($edit_payment && $edit_payment['payment_method'] == 'card') ? 'selected' : ''; ?>>Card</option>
                                <option value="online" <?php echo ($edit_payment && $edit_payment['payment_method'] == 'online') ? 'selected' : ''; ?>>Online</option>
                            </select><br><br>

                            <label for="status">Status:</label>
                            <select name="status" id="status" required class="form-input">
                                <option value="pending" <?php echo ($edit_payment && $edit_payment['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="paid" <?php echo ($edit_payment && $edit_payment['status'] == 'paid') ? 'selected' : ''; ?>>Paid</option>
                                <option value="overdue" <?php echo ($edit_payment && $edit_payment['status'] == 'overdue') ? 'selected' : ''; ?>>Overdue</option>
                            </select><br><br>

                            <input type="submit" name="<?php echo $edit_payment ? 'edit_bill_payment' : 'add_bill_payment'; ?>" value="<?php echo $edit_payment ? 'Update Bill Payment' : 'Add Bill Payment'; ?>" class="btn">
                            <button type="button" class="btn" onclick="cancelForm()">Cancel</button>
                        </form>
                    </div>

                    <!-- Bill Payment List Table -->
                    <div class="manage-table-container" id="billPaymentList" style="display: <?php echo isset($_GET['edit_id']) || isset($_GET['add']) ? 'none' : 'block'; ?>;">
                        <h2 class="text-center mb-4" style="color: #007bff; font-weight: bolder;">Bill Payment List</h2>
                        <table class="manage-table">
                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>Appointment</th>
                                    <th>Amount (LKR)</th>
                                    <th>Payment Date &amp; Time</th>
                                    <th>Payment Method</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bill_payments as $payment): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($payment['patient_name'] ?? 'None'); ?></td>
                                        <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($payment['appointment_date'])) ?? 'None'); ?></td>
                                        <td><?php echo number_format($payment['amount'], 2); ?></td>
                                        <td><?php echo date('Y-m-d H:i', strtotime($payment['payment_date'])); ?></td>
                                        <td><?php echo htmlspecialchars($payment['payment_method']); ?></td>
                                        <td><?php echo htmlspecialchars($payment['status']); ?></td>
                                        <td>
                                            <a href="manage_bill_payments.php?edit_id=<?php echo $payment['id']; ?>" class="portal-btn" style="background-color: #007bff; width: 80px; padding: 8px 15px;">Edit</a>
                                            <a href="manage_bill_payments.php?delete_id=<?php echo $payment['id']; ?>" class="portal-btn" style="background-color: #dc3545; width: 80px; padding: 8px 15px;" onclick="return confirm('Are you sure you want to delete this bill payment?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <a href="manage_bill_payments.php?add=1" class="portal-btn mt-4">Add New Bill Payment</a>
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
            window.location.href = 'manage_bill_payments.php';
        }
    </script>
</body>
</html>

