<?php

session_start();


include 'db_connect.php';


$feedback_limit = 3;
$sql_feedback = "SELECT f.feedback_text, f.rating, f.created_at, u.name 
                 FROM feedback f 
                 LEFT JOIN users u ON f.user_id = u.id 
                 ORDER BY f.created_at DESC 
                 LIMIT ?";
$stmt = mysqli_prepare($conn, $sql_feedback);
mysqli_stmt_bind_param($stmt, "i", $feedback_limit);
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Care Compass Hospitals</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
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
                    <a href="index.php" class="active">Home</a>
                    <a href="doctors.php">Doctors</a>
                    <a href="services.php">Services</a>
                    <a href="ptportalloging.php">Patient Portal</a>
                    <a href="contact.php">Contact Us</a>
                    <a href="about.php">About Us</a>
                </div>
                <a href="appointments.php" class="btn">Appointment</a>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h2>Your Health, Our Priority</h2>
            <p>Welcome to Care Compass Hospitals - Quality care across Colombo, Kandy, and Kurunegala.</p>
            <a href="appointments.php" class="btn">Book an Appointment</a>
        </div>
    </section>

    <!-- Key Links Section -->
    <section class="key-links">
        <h2>Explore Our Features</h2>
        <div class="links-container">
            <a href="doctors.php" class="link-box">Meet Our Experts</a>
            <a href="appointments.php" class="link-box">Book Your Visit</a>
            <a href="ptportalloging.php" class="link-box">Patient Portal</a>
            <a href="about.php" class="link-box">About Us</a>
        </div>
    </section>

    <!-- Services Overview -->
    <section class="services">
        <h2>Our Services</h2>
        <div class="service-container">
            <div class="service-box">
                <img src="images/surgery.jpg" alt="Medical Treatments">
                <h3>Medical Treatments</h3>
                <p>Expert care in Western, Ayurvedic, and more.</p>
                <a href="services.php#Western Medicine">Learn More</a>
            </div>
            <div class="service-box">
                <img src="images/lab.jpg" alt="Laboratory Tests">
                <h3>Laboratory Tests</h3>
                <p>Accurate diagnostics for your health needs.</p>
                <a href="services.php#Blood Tests">Learn More</a>
            </div>
            <div class="service-box">
                <img src="images/emergencty.png" alt="Emergency Care">
                <h3>Emergency Care</h3>
                <p>24/7 support for urgent medical situations.</p>
                <a href="services.php#Emergency Care">Learn More</a>
            </div>
        </div>
    </section>

    <!-- Testimonials  -->
    <section class="testimonials">
        <h2>What Our Patients Say</h2>
        <div class="testimonial-container">
            <?php if (empty($feedback)): ?>
                <p class="text-center">No feedback available yet.</p>
            <?php else: ?>
                <?php foreach ($feedback as $item): ?>
                    <div class="testimonial-box">
                        <p>“<?php echo htmlspecialchars($item['feedback_text']); ?>” – <?php echo htmlspecialchars($item['name']); ?> (Rating: <?php echo $item['rating']; ?> ★)</p>
                        <small><?php echo date('Y-m-d', strtotime($item['created_at'])); ?></small>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
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
                        <li><a href="about.php">About</a></li>
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
                        <a href="https://www.facebook.com/share/15zxU44JMz/?mibextid=wwXIfr"><img src="images/facebook.png" alt="Facebook"></a>
                        <a href="https://x.com/Rash_lakshan?t=GwXPhZpm9inpAfr3paZRwQ&s=08"><img src="images/twitter.png" alt="Twitter"></a>
                        <a href="#"><img src="images/instagram.png" alt="Instagram"></a>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p>© 2025 | Designed by <a href="https://www.linkedin.com/in/rashintha-lakshan-a14842291/">Rashintha Lakshan</a></p>
            </div>
        </div>
    </footer>

    <!-- Custom JS  -->
    <script src="scripts.js"></script>
</body>
</html>