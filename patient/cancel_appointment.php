<?php
session_start();

// Check if user is logged in and is a patient
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: ../auth/login.php");
    exit();
}

include '../includes/db_connect.php';

// Handle appointment cancellation
if (isset($_GET['id'])) {
    $appointment_id = intval($_GET['id']);
    $patient_id = intval($_SESSION['user_id']);

    // Check if appointment exists, belongs to patient, and is pending
    $sql_check = "SELECT id FROM appointments WHERE id = ? AND patient_id = ? AND status = 'pending'";
    $stmt_check = mysqli_prepare($conn, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "ii", $appointment_id, $patient_id);
    mysqli_stmt_execute($stmt_check);
    $result_check = mysqli_stmt_get_result($stmt_check);

    if (mysqli_num_rows($result_check) > 0) {
        $sql = "UPDATE appointments SET status = 'cancelled' WHERE id = ? AND patient_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $appointment_id, $patient_id);
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Appointment cancelled successfully.'); window.location.href = 'appointments.php';</script>";
        } else {
            echo "<script>alert('Error cancelling appointment: " . mysqli_error($conn) . "'); window.location.href = 'appointments.php';</script>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Appointment not found or cannot be cancelled because it is not pending.'); window.location.href = 'appointments.php';</script>";
    }
    mysqli_stmt_close($stmt_check);
} else {
    header("Location: appointments.php");
    exit();
}
?>

