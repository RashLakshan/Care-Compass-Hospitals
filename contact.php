<?php

session_start();


include 'db_connect.php';

// Handle contact query submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_query'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    $sql = "INSERT INTO contact_queries (name, email, message, status) VALUES (?, ?, ?, 'pending')";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $name, $email, $message);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Query submitted successfully! We will get back to you soon.');</script>";
    } else {
        echo "<script>alert('Error submitting query: " . mysqli_error($conn) . "');</script>";
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Care Compass Hospitals - Contact Us</title>
    
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    
    <header>
        <!-- Top Contact Bar -->
        <div class="top-bar">
            <div class="container">
                <div class="contact-info">
                    <a href="tel:+94788888624">📞 +94 788 888 624</a>
                    <a href="mailto:cchospital@gmail.com">✉️ cchospital@gmail.com</a>
                </div>
                <nav class="top-nav">
                    <a href="login.php">Log in</a>
                    <a href="register.php">Register</a>
                </nav>
            </div>
        </div>

        <!-- Navigation Bar -->
        <nav class="navbar">
            <div class="container">
                <a class="navbar-brand" href="index.php">
                    <img src="images/cchlogo.png" alt="Care Compass Hospital" width="300">
                </a>
                <button class="menu-toggle">☰</button>
                <div class="nav-links">
                    <a href="index.php">Home</a>
                    <a href="doctors.php">Doctors</a>
                    <a href="services.php">Services</a>
                    <a href="ptportalloging.php">Patient Portal</a>
                    <a href="contact.php" class="active">Contact Us</a>
                </div>
                <a href="appointments.php" class="btn">Appointment</a>
            </div>
        </nav>
    </header>

    <!-- Contact Content  -->
    <section class="contact-content">
        <div class="container">
            <div class="vertical-center">
                <h1 class="text-center mb-4" style="color: #007bff; font-weight: bolder;">Contact Us</h1>
                <p class="text-center mb-5">Get in touch with Care Compass Hospitals for inquiries, feedback, or emergency assistance.</p>

                <!-- Contact Information -->
                <div class="contact-info">
                    <h2 class="text-center mb-4" style="color: #007bff; font-weight: bolder;">Our Locations</h2>
                    <div class="location-container">
                        <div class="location-box">
                            <h3 class="mb-3" style="color: #333; font-weight: bold;">Kandy Branch</h3>
                            <p>Address: 123 Health Road, Kandy, Sri Lanka</p>
                            <p>Phone: (081) 123-4567</p>
                            <p>Email: kandy@carecompass.lk</p>
                            <p>Emergency: 123-456-7890 (24/7)</p>
                        </div>
                        <div class="location-box">
                            <h3 class="mb-3" style="color: #333; font-weight: bold;">Colombo Branch</h3>
                            <p>Address: 456 Wellness Street, Colombo, Sri Lanka</p>
                            <p>Phone: (011) 765-4321</p>
                            <p>Email: colombo@carecompass.lk</p>
                            <p>Emergency: 123-456-7890 (24/7)</p>
                        </div>
                        <div class="location-box">
                            <h3 class="mb-3" style="color: #333; font-weight: bold;">Kurunegala Branch</h3>
                            <p>Address: 789 Care Avenue, Kurunegala, Sri Lanka</p>
                            <p>Phone: (037) 890-1234</p>
                            <p>Email: kurunegala@carecompass.lk</p>
                            <p>Emergency: 123-456-7890 (24/7)</p>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="contact-form">
                    <h2 class="text-center mb-4" style="color: #007bff; font-weight: bolder;">Send Us a Message</h2>
                    <form method="POST" class="contact-form-content">
                        <label for="name">Your Name:</label>
                        <input type="text" name="name" id="name" required placeholder="Enter your name" class="form-input"><br><br>

                        <label for="email">Your Email:</label>
                        <input type="email" name="email" id="email" required placeholder="Enter your email" class="form-input"><br><br>

                        <label for="message">Message:</label>
                        <textarea name="message" id="message" required placeholder="Your message or query..." rows="5" class="form-input"></textarea><br><br>

                        <input type="submit" name="submit_query" value="Submit" class="btn">
                    </form>
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
                        <li><a href="index.php">Home</a></li>
                        <li><a href="services.php">Services</a></li>
                        <li><a href="doctors.php">Doctors</a></li>
                        <li><a href="appointments.php">Appointments</a></li>
                        <li><a href="login.php">Patient Portal</a></li>
                        <li><a href="contact.php">Contact</a></li>
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