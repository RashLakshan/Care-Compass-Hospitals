<?php
session_start();

// Database connection
include 'db_connect.php';

// Handle registration submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $role = 'patient'; // Default role for new users

    // Check if username, email, or mobile number already exists
    $sql_check = "SELECT * FROM users WHERE username = ? OR email = ? OR mobile = ?";
    $stmt_check = mysqli_prepare($conn, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "sss", $username, $email, $mobile);
    mysqli_stmt_execute($stmt_check);
    $result = mysqli_stmt_get_result($stmt_check);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Username, email, or mobile number already exists.');</script>";
    } else {
        $sql = "INSERT INTO users (name, username, mobile, email, password, role) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssss", $name, $username, $mobile, $email, $password, $role);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Registration successful! Please log in.'); window.location.href = 'login.php';</script>";
        } else {
            echo "<script>alert('Error registering user: " . mysqli_error($conn) . "');</script>";
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_stmt_close($stmt_check);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Care Compass Hospitals - Register</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Header Section -->
    <header class="auth-header">
        <div class="container auth-header-inner">
            <a class="navbar-brand" href="index.php">
                <img src="images/cchlogo.png" alt="Care Compass Hospital">
            </a>
            <a href="index.php" class="btn-back-home">&#8592; Back to Home</a>
        </div>
    </header>

    <!-- Register Content (Vertically Centered) -->
    <section class="register-content">
        <div class="container">
            <div class="vertical-center">
                <h1 class="text-center mb-4" style="color: #007bff; font-weight: bolder;">Patient Registration</h1>
                <p class="text-center mb-5">Create an account to access our services and manage your healthcare.</p>

                <div class="register-form">
                    <form method="POST">
                        <label for="name">Name:</label>
                        <input type="text" name="name" id="name" required placeholder="Enter your full name" class="form-input"><br><br>

                        <label for="username">Username:</label>
                        <input type="text" name="username" id="username" required placeholder="Choose a username" class="form-input"><br><br>

                        <label for="mobile">Mobile Number:</label>
                        <input type="tel" name="mobile" id="mobile" required placeholder="Enter your mobile number (e.g., +94712345678)" class="form-input"><br><br>

                        <label for="email">Email:</label>
                        <input type="email" name="email" id="email" required placeholder="Enter your email" class="form-input"><br><br>

                        <label for="password">Password:</label>
                        <input type="password" name="password" id="password" required placeholder="Enter a strong password" class="form-input"><br><br>

                        <input type="submit" name="register" value="Register" class="btn">
                    </form>
                    <p class="text-center mt-4">Already have an account? <a href="login.php" style="color: #007bff; font-weight: bold; text-decoration: none;">Login here</a>.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
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
                        <li><a href="index.php">Home</a></li>
                        <li><a href="services.php">Services</a></li>
                        <li><a href="doctors.php">Doctors</a></li>
                        <li><a href="appointments.php">Appointments</a></li>
                        <li><a href="login.php">Patient Portal</a></li>
                        <li><a href="contact.php">Contact Us</a></li>
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
                        <a href="#"><img src="images/facebook.png" alt="Facebook"></a>
                        <a href="#"><img src="images/twitter.png" alt="Twitter"></a>
                        <a href="#"><img src="images/instagram.png" alt="Instagram"></a>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p>© 2025 | Designed by <a href="#">Rashintha Lakshan</a></p>
            </div>
        </div>
    </footer>

    <script src="scripts.js"></script>
</body>
</html>