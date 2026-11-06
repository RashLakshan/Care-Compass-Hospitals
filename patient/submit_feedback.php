<?php
session_start();
include '../includes/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = mysqli_real_escape_string($conn, $_SESSION['user_id']);
    $role = mysqli_real_escape_string($conn, $_SESSION['role']);
    $feedback_text = mysqli_real_escape_string($conn, $_POST['feedback_text']);
    $rating = mysqli_real_escape_string($conn, $_POST['rating']);

    // Validate input
    if (empty($feedback_text) || $rating < 1 || $rating > 5) {
        echo "<script>alert('Please provide valid feedback and rating (1-5).'); window.history.back();</script>";
        exit();
    }

    // Insert feedback into database
    $sql = "INSERT INTO feedback (user_id, role, feedback_text, rating) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "isss", $user_id, $role, $feedback_text, $rating);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Thank you for your feedback!'); window.location.href = 'portal.php';</script>";
    } else {
        echo "<script>alert('Error submitting feedback: " . mysqli_error($conn) . "'); window.history.back();</script>";
    }
    mysqli_stmt_close($stmt);
} else {
    header("Location: portal.php");
    exit();
}
?>

