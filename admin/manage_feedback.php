<?php
session_start();

// Check if user is logged in and is an admin or staff
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff')) {
    header("Location: ../auth/login.php");
    exit();
}

include '../includes/db_connect.php';

// Fetch feedback with pagination 
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$sql_feedback = "SELECT f.id, f.feedback_text, f.rating, f.created_at, u.name, u.role 
                 FROM feedback f 
                 LEFT JOIN users u ON f.user_id = u.id 
                 WHERE (f.feedback_text LIKE ? OR u.name LIKE ?) 
                 LIMIT ? OFFSET ?";
$search_term = "%{$search}%";
$stmt = mysqli_prepare($conn, $sql_feedback);
mysqli_stmt_bind_param($stmt, "ssii", $search_term, $search_term, $limit, $offset);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$feedback = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $feedback[] = $row;
    }
} else {
    echo "Error fetching feedback: " . mysqli_error($conn);
}
mysqli_stmt_close($stmt);

// Count total feedback for pagination
$sql_count = "SELECT COUNT(*) AS total FROM feedback f 
              LEFT JOIN users u ON f.user_id = u.id 
              WHERE (f.feedback_text LIKE ? OR u.name LIKE ?)";
$stmt_count = mysqli_prepare($conn, $sql_count);
mysqli_stmt_bind_param($stmt_count, "ss", $search_term, $search_term);
mysqli_stmt_execute($stmt_count);
$result_count = mysqli_stmt_get_result($stmt_count);
$total_feedback = mysqli_fetch_assoc($result_count)['total'] ?? 0;
mysqli_stmt_close($stmt_count);
$total_pages = ceil($total_feedback / $limit);

// Handle feedback deletion 
if (isset($_GET['delete_id']) && $_SESSION['role'] === 'admin') {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $sql = "DELETE FROM feedback WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $delete_id);
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Feedback deleted successfully.'); window.location.href = 'manage_feedback.php';</script>";
    } else {
        echo "<script>alert('Error deleting feedback: " . mysqli_error($conn) . "');</script>";
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Care Compass Hospitals - Manage Feedback</title>
    <link rel="stylesheet" href="../assets/css/admin_styles.css">
</head>
<body>
    <?php
    $portal_home  = ($_SESSION['role'] === 'staff') ? '../staff/portal.php' : 'portal.php';
    $portal_label = ($_SESSION['role'] === 'staff') ? 'Staff Portal' : 'Admin Panel';
    $base_path    = '../';
    include '../includes/header.php';
    ?>

    <div class="internal-body">
        <div class="internal-manage-wrap">
            <div class="internal-breadcrumb"><a href="<?php echo $portal_home; ?>"><?php echo $portal_label; ?></a> &rsaquo; Feedback</div>
            <div class="internal-page-title">Manage Feedback</div>

            <div class="container">
                <div class="vertical-center">
                    <h1 class="text-center mb-4" style="color: #007bff; font-weight: bolder;">Manage Feedback</h1>

                    <!-- Search and Pagination Controls -->
                    <div class="text-center mb-4">
                        <input type="text" name="search" id="search" placeholder="Search by feedback or user name..." class="form-input" value="<?php echo htmlspecialchars($search); ?>">
                        <button onclick="window.location.href='manage_feedback.php?search=' + encodeURIComponent(document.getElementById('search').value)" class="btn" style="margin-left: 10px;">Search</button>
                    </div>

                    <div class="manage-table-container">
                        <h2 class="text-center mb-4" style="color: #007bff; font-weight: bolder;">Feedback List</h2>
                        <table class="manage-table">
                            <thead>
                                <tr>
                                    <th>User Name</th>
                                    <th>Role</th>
                                    <th>Feedback</th>
                                    <th>Rating (1-5)</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($feedback as $item): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['name'] ?? 'Unknown'); ?></td>
                                        <td><?php echo htmlspecialchars($item['role']); ?></td>
                                        <td><?php echo htmlspecialchars(substr($item['feedback_text'], 0, 50)) . (strlen($item['feedback_text']) > 50 ? '...' : ''); ?></td>
                                        <td><?php echo htmlspecialchars($item['rating']); ?> ★</td>
                                        <td><?php echo date('Y-m-d H:i', strtotime($item['created_at'])); ?></td>
                                        <td>
                                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                                <a href="manage_feedback.php?delete_id=<?php echo $item['id']; ?>" class="portal-btn" style="background-color: #dc3545; width: 80px; padding: 8px 15px;" onclick="return confirm('Are you sure you want to delete this feedback?')">Delete</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="text-center mt-4">
                            <?php if ($total_pages > 1): ?>
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <a href="manage_feedback.php?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>" class="btn" style="<?php echo $i === $page ? 'background-color: #0056b3;' : 'background-color: #007bff;'; ?> margin: 5px;">
                                        <?php echo $i; ?>
                                    </a>
                                <?php endfor; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Logout Button for Staff/Admin -->
                    <div class="text-center mt-5">
                        <a href="../auth/logout.php" class="btn" style="background-color: #dc3545; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none;">Logout</a>
                    </div>
                </div>
            </div>
        </div><!-- /internal-manage-wrap -->
    </div><!-- /internal-body -->

    <footer class="internal-footer">
        &copy; 2025 Care Compass Hospitals &nbsp;|&nbsp; <a href="<?php echo $portal_home; ?>"><?php echo $portal_label; ?></a>
    </footer>

    <script src="../assets/js/scripts.js"></script>
</body>
</html>

