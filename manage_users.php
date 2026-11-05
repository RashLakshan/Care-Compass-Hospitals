<?php
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); 
    exit();
}

// Database connection
include 'db_connect.php'; 

// Handle user deletion
if (isset($_GET['delete_id'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $delete_id);
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('User deleted successfully.'); window.location.href = 'manage_users.php';</script>";
    } else {
        echo "<script>alert('Error deleting user: " . mysqli_error($conn) . "');</script>";
    }
    mysqli_stmt_close($stmt);
}

// Fetch all users
$sql = "SELECT * FROM users";
$result = mysqli_query($conn, $sql);
$users = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
} else {
    echo "Error fetching users: " . mysqli_error($conn);
}

// Find user to edit if edit_id is set
$edit_user = null;
if (isset($_GET['edit_id'])) {
    foreach ($users as $u) {
        if ($u['id'] == $_GET['edit_id']) {
            $edit_user = $u;
            break;
        }
    }
}

// Handle add/edit user form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST['add_user']) || isset($_POST['edit_user']))) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $password = isset($_POST['password']) && !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
    $user_id = isset($_POST['user_id']) ? mysqli_real_escape_string($conn, $_POST['user_id']) : null;

    if ($user_id) { // Update existing user
        if ($password) {
            $sql = "UPDATE users SET name = ?, username = ?, mobile = ?, email = ?, role = ?, password = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssssssi", $name, $username, $mobile, $email, $role, $password, $user_id);
        } else {
            $sql = "UPDATE users SET name = ?, username = ?, mobile = ?, email = ?, role = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sssssi", $name, $username, $mobile, $email, $role, $user_id);
        }
    } else { // Add new user
        $sql = "INSERT INTO users (name, username, mobile, email, password, role) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssss", $name, $username, $mobile, $email, $password, $role);
    }

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('User " . ($user_id ? 'updated' : 'added') . " successfully.'); window.location.href = 'manage_users.php';</script>";
    } else {
        echo "<script>alert('Error " . ($user_id ? 'updating' : 'adding') . " user: " . mysqli_error($conn) . "');</script>";
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Care Compass Hospitals - Manage Users</title>

    <link rel="stylesheet" href="admin_styles.css">
</head>
<body>
   
    <?php
$portal_home  = 'admin_portal.php';
$portal_label = 'Admin Panel';
include 'admin_header.php';
?>

    <!-- Manage Users Content (Vertically Centered) -->
    <div class="internal-body"><div class="internal-manage-wrap">
        <div class="internal-breadcrumb"><a href="admin_portal.php">Admin Panel</a> &rsaquo; Users</div>
        <div class="internal-page-title">Manage Users</div>

        <div class="container">
            <div class="vertical-center">
                <h1 class="text-center mb-4" style="color: #007bff; font-weight: bolder;">Manage Users</h1>

                <!-- Add/Edit User Form (Toggleable) -->
                <div class="manage-form" id="manageForm">
                    <h2 class="text-center mb-4" style="color: #007bff; font-weight: bolder;"><?php echo $edit_user ? 'Edit User' : 'Add New User'; ?></h2>
                    <form method="POST" id="userForm">
                        <input type="hidden" name="user_id" value="<?php echo $edit_user ? htmlspecialchars($edit_user['id']) : ''; ?>">
                        <label for="name">Name:</label>
                        <input type="text" name="name" id="name" required placeholder="Enter full name" class="form-input" value="<?php echo $edit_user ? htmlspecialchars($edit_user['name']) : ''; ?>"><br><br>

                        <label for="username">Username:</label>
                        <input type="text" name="username" id="username" required placeholder="Enter username" class="form-input" value="<?php echo $edit_user ? htmlspecialchars($edit_user['username']) : ''; ?>"><br><br>

                        <label for="mobile">Mobile Number:</label>
                        <input type="tel" name="mobile" id="mobile" required placeholder="Enter mobile number (e.g., +94712345678)" class="form-input" value="<?php echo $edit_user ? htmlspecialchars($edit_user['mobile']) : ''; ?>"><br><br>

                        <label for="email">Email:</label>
                        <input type="email" name="email" id="email" required placeholder="Enter email" class="form-input" value="<?php echo $edit_user ? htmlspecialchars($edit_user['email']) : ''; ?>"><br><br>

                        <label for="role">Role:</label>
                        <select name="role" id="role" required class="form-input">
                            <option value="patient" <?php echo ($edit_user && $edit_user['role'] === 'patient') ? 'selected' : ''; ?>>Patient</option>
                            <option value="staff" <?php echo ($edit_user && $edit_user['role'] === 'staff') ? 'selected' : ''; ?>>Staff</option>
                            <option value="admin" <?php echo ($edit_user && $edit_user['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                        </select><br><br>

                        <label for="password">Password (leave blank to keep existing):</label>
                        <input type="password" name="password" id="password" placeholder="Enter new password (optional)" class="form-input"><br><br>

                        <input type="submit" name="<?php echo $edit_user ? 'edit_user' : 'add_user'; ?>" value="<?php echo $edit_user ? 'Update User' : 'Add User'; ?>" class="btn">
                        <button type="button" class="btn" onclick="cancelForm()">Cancel</button>
                    </form>
                </div>

                <!-- User List Table -->
                <div class="manage-table-container" id="userList" style="display: <?php echo isset($_GET['edit_id']) || isset($_GET['add']) ? 'none' : 'block'; ?>;">
                    <h2 class="text-center mb-4" style="color: #007bff; font-weight: bolder;">User List</h2>
                    <table class="manage-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                                    <td><?php echo htmlspecialchars($user['mobile']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                                    <td>
                                        <a href="manage_users.php?edit_id=<?php echo $user['id']; ?>" class="portal-btn" style="background-color: #007bff; width: 80px; padding: 8px 15px;">Edit</a>
                                        <a href="manage_users.php?delete_id=<?php echo $user['id']; ?>" class="portal-btn" style="background-color: #dc3545; width: 80px; padding: 8px 15px;" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <a href="manage_users.php?add=1" class="portal-btn mt-4">Add New User</a>
                </div>
            </div>
        </div>
    
</div><!-- /internal-manage-wrap -->
</div><!-- /internal-body -->



<footer class="internal-footer">
    &copy; 2025 Care Compass Hospitals &nbsp;|&nbsp; <a href="admin_portal.php">Admin Panel</a>
</footer>



    <script src="scripts.js"></script>
    <script>
        function cancelForm() {
            window.location.href = 'manage_users.php';
        }
    </script>
</body>
</html>


