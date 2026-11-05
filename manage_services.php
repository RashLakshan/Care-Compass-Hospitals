<?php
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); // Redirect to login in the same directory
    exit();
}

// Database connection
include 'db_connect.php';

// Ensure the services table exists 
$sql_check = "SHOW TABLES LIKE 'services'";
$result = mysqli_query($conn, $sql_check);
if (mysqli_num_rows($result) == 0) {
    $sql_create = "CREATE TABLE services (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        description TEXT NOT NULL,
        category VARCHAR(50) NOT NULL
    )";
    if (!mysqli_query($conn, $sql_create)) {
        echo "Error creating services table: " . mysqli_error($conn);
        exit();
    }
}

// Handle service deletion
if (isset($_GET['delete_id'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $sql = "DELETE FROM services WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $delete_id);
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Service deleted successfully.'); window.location.href = 'manage_services.php';</script>";
    } else {
        echo "<script>alert('Error deleting service: " . mysqli_error($conn) . "');</script>";
    }
    mysqli_stmt_close($stmt);
}

// Fetch all services
$sql = "SELECT * FROM services";
$result = mysqli_query($conn, $sql);
$services = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $services[] = $row;
    }
} else {
    echo "Error fetching services: " . mysqli_error($conn);
}

// Find service to edit if edit_id is set
$edit_service = null;
if (isset($_GET['edit_id'])) {
    foreach ($services as $srv) {
        if ($srv['id'] == $_GET['edit_id']) {
            $edit_service = $srv;
            break;
        }
    }
}

// Handle add/edit service form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST['add_service']) || isset($_POST['edit_service']))) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $service_id = isset($_POST['service_id']) ? mysqli_real_escape_string($conn, $_POST['service_id']) : null;

    if ($service_id) { // Update existing service
        $sql = "UPDATE services SET name = ?, description = ?, category = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssi", $name, $description, $category, $service_id);
    } else { // Add new service
        $sql = "INSERT INTO services (name, description, category) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $name, $description, $category);
    }

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Service " . ($service_id ? 'updated' : 'added') . " successfully.'); window.location.href = 'manage_services.php';</script>";
    } else {
        echo "<script>alert('Error " . ($service_id ? 'updating' : 'adding') . " service: " . mysqli_error($conn) . "');</script>";
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Care Compass Hospitals - Manage Services</title>
    
    <link rel="stylesheet" href="admin_styles.css">
</head>
<body>
    <?php
$portal_home  = 'admin_portal.php';
$portal_label = 'Admin Panel';
include 'admin_header.php';
?>

    <!-- Manage Services Content -->
    <div class="internal-body"><div class="internal-manage-wrap">
        <div class="internal-breadcrumb"><a href="admin_portal.php">Admin Panel</a> &rsaquo; Services</div>
        <div class="internal-page-title">Manage Services</div>

        <div class="container">
            <div class="vertical-center">
                <h1 class="text-center mb-4" style="color: #007bff; font-weight: bolder;">Manage Services</h1>

                <!-- Add/Edit Service Form -->
                <div class="manage-form" id="manageForm">
                    <h2 class="text-center mb-4" style="color: #007bff; font-weight: bolder;"><?php echo $edit_service ? 'Edit Service' : 'Add New Service'; ?></h2>
                    <form method="POST" id="serviceForm">
                        <input type="hidden" name="service_id" value="<?php echo $edit_service ? htmlspecialchars($edit_service['id']) : ''; ?>">
                        <label for="name">Name:</label>
                        <input type="text" name="name" id="name" required placeholder="Enter service name" class="form-input" value="<?php echo $edit_service ? htmlspecialchars($edit_service['name']) : ''; ?>"><br><br>

                        <label for="description">Description:</label>
                        <textarea name="description" id="description" required placeholder="Enter service description" class="form-input" rows="4"><?php echo $edit_service ? htmlspecialchars($edit_service['description']) : ''; ?></textarea><br><br>

                        <label for="category">Category:</label>
                        <input type="text" name="category" id="category" required placeholder="Enter category (e.g., Treatment, Laboratory)" class="form-input" value="<?php echo $edit_service ? htmlspecialchars($edit_service['category']) : ''; ?>"><br><br>

                        <input type="submit" name="<?php echo $edit_service ? 'edit_service' : 'add_service'; ?>" value="<?php echo $edit_service ? 'Update Service' : 'Add Service'; ?>" class="btn">
                        <button type="button" class="btn" onclick="cancelForm()">Cancel</button>
                    </form>
                </div>

                <!-- Service List Table -->
                <div class="manage-table-container" id="serviceList" style="display: <?php echo isset($_GET['edit_id']) || isset($_GET['add']) ? 'none' : 'block'; ?>;">
                    <h2 class="text-center mb-4" style="color: #007bff; font-weight: bolder;">Service List</h2>
                    <table class="manage-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Category</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($services as $service): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($service['name']); ?></td>
                                    <td><?php echo htmlspecialchars(substr($service['description'], 0, 50)) . (strlen($service['description']) > 50 ? '...' : ''); ?></td>
                                    <td><?php echo htmlspecialchars($service['category']); ?></td>
                                    <td>
                                        <a href="manage_services.php?edit_id=<?php echo $service['id']; ?>" class="portal-btn" style="background-color: #007bff; width: 80px; padding: 8px 15px;">Edit</a>
                                        <a href="manage_services.php?delete_id=<?php echo $service['id']; ?>" class="portal-btn" style="background-color: #dc3545; width: 80px; padding: 8px 15px;" onclick="return confirm('Are you sure you want to delete this service?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <a href="manage_services.php?add=1" class="portal-btn mt-4">Add New Service</a>
                </div>
            </div>
        </div>
    
</div><!-- /internal-manage-wrap -->
</div><!-- /internal-body -->



<footer class="internal-footer">
    &copy; 2025 Care Compass Hospitals &nbsp;|&nbsp; <a href="admin_portal.php">Admin Panel</a>
</footer>



    <!-- Custom JS -->
    <script src="scripts.js"></script>
    <script>
        function cancelForm() {
            window.location.href = 'manage_services.php';
        }
    </script>
</body>
</html>


