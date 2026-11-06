<?php

session_start();

include '../includes/db_connect.php';


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $identifier = mysqli_real_escape_string($conn, $_POST['identifier']); 
    $password = $_POST['password'];

    
    $sql = "SELECT * FROM users WHERE (username = ? OR mobile = ?) LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $identifier, $identifier);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['name'] = $row['name']; 

            
            if ($row['role'] == 'admin') {
                header("Location: ../admin/portal.php");
            } elseif ($row['role'] == 'staff') {
                header("Location: ../staff/portal.php");
            } elseif ($row['role'] == 'patient') {
                header("Location: ../patient/portal.php"); 
            }
            exit();
        } else {
            echo "<script>alert('Invalid password.');</script>";
        }
    } else {
        echo "<script>alert('Invalid username or mobile number.');</script>";
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Care Compass Hospitals - Login</title>
    
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <!-- Header Section -->
    <header class="auth-header">
        <div class="container auth-header-inner">
            <a class="navbar-brand" href="../index.php">
                <img src="../assets/images/cchlogo.png" alt="Care Compass Hospital">
            </a>
            <a href="../index.php" class="btn-back-home">&#8592; Back to Home</a>
        </div>
    </header>

    <!-- Login Content  -->
    <section class="login-content">
        <div class="container">
            <div class="vertical-center">
                <h1 class="text-center mb-4" style="color: #007bff; font-weight: bolder;">Patient Login</h1>
                <p class="text-center mb-5">Access your account to manage appointments and medical records.</p>

                <div class="login-form">
                    <form method="POST">
                        <label for="identifier">Username or Mobile Number:</label>
                        <input type="text" name="identifier" id="identifier" required placeholder="Enter your username or mobile number (e.g., +94712345678)" class="form-input"><br><br>

                        <label for="password">Password:</label>
                        <input type="password" name="password" id="password" required placeholder="Enter your password" class="form-input"><br><br>

                        <input type="submit" name="login" value="Login" class="btn">
                    </form>
                    <p class="text-center mt-4">Don't have an account? <a href="register.php" style="color: #007bff; font-weight: bold; text-decoration: none;">Register here</a>.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer  -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section about">
                    <h2>About Us</h2>
                    <p>We provide high-quality products and services to make your life easier. Stay connected for updates.</p>
                </div>

                <div class="footer-section links">
                    <h2>Quick Links</h2>
                    <ul>
                        <li><a href="../index.php">Home</a></li>
                        <li><a href="../services.php">Services</a></li>
                        <li><a href="../doctors.php">Doctors</a></li>
                        <li><a href="../patient/appointments.php">Appointments</a></li>
                        <li><a href="login.php">Patient Portal</a></li>
                        <li><a href="../contact.php">Contact Us</a></li>
                    </ul>
                </div>

                <div class="footer-section contact">
                    <h2>Contact Us</h2>
                    <p>Email: cchospital@gmail.com</p>
                    <p>Phone: +94 788 888 624</p>
                </div>

                <div class="footer-section social">
                    <h2>Follow Us</h2>
                    <div class="social-media">
                        <a href="#"><img src="../assets/images/facebook.png" alt="Facebook"></a>
                        <a href="#"><img src="../assets/images/twitter.png" alt="Twitter"></a>
                        <a href="#"><img src="../assets/images/instagram.png" alt="Instagram"></a>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2025 | Designed by <a href="#">Rashintha Lakshan</a></p>
            </div>
        </div>
    </footer>

    <script src="../assets/js/scripts.js"></script>
</body>
</html>

