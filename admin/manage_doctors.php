<?php
// Start session
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php"); 
    exit();
}

// Database connection
include '../includes/db_connect.php';

// Fetch admin details
$sql_check = "SHOW TABLES LIKE 'doctors'";
$result = mysqli_query($conn, $sql_check);
if (mysqli_num_rows($result) == 0) {
    $sql_create = "CREATE TABLE doctors (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        specialty VARCHAR(100) NOT NULL,
        location VARCHAR(100) NOT NULL,
        contact VARCHAR(20) NOT NULL,
        photo VARCHAR(255) DEFAULT NULL
    )";
    if (!mysqli_query($conn, $sql_create)) {
        echo "Error creating doctors table: " . mysqli_error($conn);
        exit();
    }
}

// Handle doctor deletion
if (isset($_GET['delete_id'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $sql = "DELETE FROM doctors WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $delete_id);
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Doctor deleted successfully.'); window.location.href = 'manage_doctors.php';</script>";
    } else {
        echo "<script>alert('Error deleting doctor: " . mysqli_error($conn) . "');</script>";
    }
    mysqli_stmt_close($stmt);
}

// Fetch all doctors
$sql = "SELECT * FROM doctors";
$result = mysqli_query($conn, $sql);
$doctors = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $doctors[] = $row;
    }
} else {
    echo "Error fetching doctors: " . mysqli_error($conn);
}

// Find doctor to edit if edit_id is set
$edit_doctor = null;
if (isset($_GET['edit_id'])) {
    foreach ($doctors as $doc) {
        if ($doc['id'] == $_GET['edit_id']) {
            $edit_doctor = $doc;
            break;
        }
    }
}

// Handle add/edit doctor form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST['add_doctor']) || isset($_POST['edit_doctor']))) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $specialty = mysqli_real_escape_string($conn, $_POST['specialty']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $photo = isset($_FILES['photo']['name']) && !empty($_FILES['photo']['name']) ? $_FILES['photo']['name'] : null;
    $doctor_id = isset($_POST['doctor_id']) ? mysqli_real_escape_string($conn, $_POST['doctor_id']) : null;

    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if ($doctor_id) { // Update existing doctor
        if ($photo) {
            $ext = strtolower(pathinfo($photo, PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed_extensions)) {
                echo "<script>alert('Invalid image format. Allowed: JPG, PNG, GIF, WEBP.'); window.history.back();</script>";
                exit();
            }
            $target_dir = "../assets/images/doctors/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $target_file = $target_dir . basename($photo);
            move_uploaded_file($_FILES['photo']['tmp_name'], $target_file);
            $sql = "UPDATE doctors SET name = ?, specialty = ?, location = ?, contact = ?, photo = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sssssi", $name, $specialty, $location, $contact, $photo, $doctor_id);
        } else {
            $sql = "UPDATE doctors SET name = ?, specialty = ?, location = ?, contact = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssssi", $name, $specialty, $location, $contact, $doctor_id);
        }
    } else { // Add new doctor
        if ($photo) {
            $ext = strtolower(pathinfo($photo, PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed_extensions)) {
                echo "<script>alert('Invalid image format. Allowed: JPG, PNG, GIF, WEBP.'); window.history.back();</script>";
                exit();
            }
            $target_dir = "../assets/images/doctors/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $target_file = $target_dir . basename($photo);
            move_uploaded_file($_FILES['photo']['tmp_name'], $target_file);
            $sql = "INSERT INTO doctors (name, specialty, location, contact, photo) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sssss", $name, $specialty, $location, $contact, $photo);
        } else {
            $sql = "INSERT INTO doctors (name, specialty, location, contact) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssss", $name, $specialty, $location, $contact);
        }
    }

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Doctor " . ($doctor_id ? 'updated' : 'added') . " successfully.'); window.location.href = 'manage_doctors.php';</script>";
    } else {
        echo "<script>alert('Error " . ($doctor_id ? 'updating' : 'adding') . " doctor: " . mysqli_error($conn) . "');</script>";
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Care Compass Hospitals - Manage Doctors</title>
    <link rel="stylesheet" href="../assets/css/admin_styles.css">
</head>
<body>
    <?php
    $portal_home  = 'portal.php';
    $portal_label = 'Admin Panel';
    $base_path    = '../';
    include '../includes/header.php';
    ?>

    <!-- Manage Doctors Content -->
    <div class="internal-body">
        <div class="internal-manage-wrap">
            <div class="internal-breadcrumb"><a href="portal.php">Admin Panel</a> &rsaquo; Doctors</div>
            <div class="internal-page-title">Manage Doctors</div>

            <div class="container">
                <div class="vertical-center">
                    <h1 class="text-center mb-4" style="color: #007bff; font-weight: bolder;">Manage Doctors</h1>

                    <!-- Add/Edit Doctor Form -->
                    <div class="manage-form" id="manageForm">
                        <h2 class="text-center mb-4" style="color: #007bff; font-weight: bolder;"><?php echo $edit_doctor ? 'Edit Doctor' : 'Add New Doctor'; ?></h2>
                        <form method="POST" id="doctorForm" enctype="multipart/form-data">
                            <input type="hidden" name="doctor_id" value="<?php echo $edit_doctor ? htmlspecialchars($edit_doctor['id']) : ''; ?>">
                            <label for="name">Name:</label>
                            <input type="text" name="name" id="name" required placeholder="Enter doctor name" class="form-input" value="<?php echo $edit_doctor ? htmlspecialchars($edit_doctor['name']) : ''; ?>"><br><br>

                            <label for="specialty">Specialty:</label>
                            <input type="text" name="specialty" id="specialty" required placeholder="Enter specialty (e.g., Cardiology)" class="form-input" value="<?php echo $edit_doctor ? htmlspecialchars($edit_doctor['specialty']) : ''; ?>"><br><br>

                            <label for="location">Location:</label>
                            <input type="text" name="location" id="location" required placeholder="Enter location (e.g., Kandy)" class="form-input" value="<?php echo $edit_doctor ? htmlspecialchars($edit_doctor['location']) : ''; ?>"><br><br>

                            <label for="contact">Contact:</label>
                            <input type="tel" name="contact" id="contact" required placeholder="Enter contact number (e.g., +94712345678)" class="form-input" value="<?php echo $edit_doctor ? htmlspecialchars($edit_doctor['contact']) : ''; ?>"><br><br>

                            <label for="photo">Photo (optional):</label>
                            <input type="file" name="photo" id="photo" class="form-input" accept="image/*"><br><br>
                            <?php if ($edit_doctor && !empty($edit_doctor['photo'])): ?>
                                <p>Current Photo: <img src="../assets/images/doctors/<?php echo htmlspecialchars($edit_doctor['photo']); ?>" alt="Doctor Photo" style="max-width: 100px; max-height: 100px;"></p>
                            <?php endif; ?>

                            <input type="submit" name="<?php echo $edit_doctor ? 'edit_doctor' : 'add_doctor'; ?>" value="<?php echo $edit_doctor ? 'Update Doctor' : 'Add Doctor'; ?>" class="btn">
                            <button type="button" class="btn" onclick="cancelForm()">Cancel</button>
                        </form>
                    </div>

                    <!-- Doctor List Table -->
                    <div class="manage-table-container" id="doctorList" style="display: <?php echo isset($_GET['edit_id']) || isset($_GET['add']) ? 'none' : 'block'; ?>;">
                        <h2 class="text-center mb-4" style="color: #007bff; font-weight: bolder;">Doctor List</h2>
                        <table class="manage-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Specialty</th>
                                    <th>Location</th>
                                    <th>Contact</th>
                                    <th>Photo</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($doctors as $doctor): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($doctor['name']); ?></td>
                                        <td><?php echo htmlspecialchars($doctor['specialty']); ?></td>
                                        <td><?php echo htmlspecialchars($doctor['location']); ?></td>
                                        <td><?php echo htmlspecialchars($doctor['contact']); ?></td>
                                        <td><?php if (!empty($doctor['photo'])): ?><img src="../assets/images/doctors/<?php echo htmlspecialchars($doctor['photo']); ?>" alt="Doctor Photo" style="max-width: 100px; max-height: 100px;"><?php else: ?>No Photo<?php endif; ?></td>
                                        <td>
                                            <a href="manage_doctors.php?edit_id=<?php echo $doctor['id']; ?>" class="portal-btn" style="background-color: #007bff; width: 80px; padding: 8px 15px;">Edit</a>
                                            <a href="manage_doctors.php?delete_id=<?php echo $doctor['id']; ?>" class="portal-btn" style="background-color: #dc3545; width: 80px; padding: 8px 15px;" onclick="return confirm('Are you sure you want to delete this doctor?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <a href="manage_doctors.php?add=1" class="portal-btn mt-4">Add New Doctor</a>
                    </div>
                </div>
            </div>
        </div><!-- /internal-manage-wrap -->
    </div><!-- /internal-body -->

    <footer class="internal-footer">
        &copy; 2025 Care Compass Hospitals &nbsp;|&nbsp; <a href="portal.php">Admin Panel</a>
    </footer>

    <script src="../assets/js/scripts.js"></script>
    <script>
        function cancelForm() {
            window.location.href = 'manage_doctors.php';
        }
    </script>
</body>
</html>

