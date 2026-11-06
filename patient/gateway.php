<?php
session_start();

// If user is already logged in, redirect directly to their dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: ../dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient &amp; Visitor Portal — Care Compass Hospitals</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

    <!-- Header Section -->
    <header>
        <!-- Top Contact Bar -->
        <div class="top-bar">
            <div class="container">
                <div class="contact-info">
                    <a href="tel:+94788888624">📞 +94 788 888 624</a>
                    <a href="mailto:cchospital@gmail.com">✉️ cchospital@gmail.com</a>
                </div>   
            </div>
        </div>

        <!-- Navigation Bar -->
        <nav class="navbar">
            <div class="container">
                <a class="navbar-brand" href="../index.php">
                    <img src="../assets/images/cchlogo.png" alt="Care Compass Hospital" width="300">
                </a>
                <button class="menu-toggle">☰</button>
                <div class="nav-links">
                    <a href="../index.php">Home</a>
                    <a href="../doctors.php">Doctors</a>
                    <a href="../services.php">Services</a>
                    <a href="gateway.php" class="active">Patient Portal</a>
                    <a href="../contact.php">Contact Us</a>
                </div>
                <a href="appointments.php" class="btn">Appointment</a>
            </div>
        </nav>
    </header>

    <!-- Gateway Portal Section -->
    <main class="gateway-wrapper">
        <div class="gateway-container">

            <!-- Hero Intro -->
            <div class="gateway-hero">
                <span class="gateway-badge">Patient &amp; Visitor Services</span>
                <h1>Welcome to <span>Care Compass Portal</span></h1>
                <p>Your secure digital gateway for scheduling doctor appointments, reviewing clinical diagnoses, and managing healthcare services across all our hospital branches.</p>
            </div>

            <!-- Two Creative Action Cards -->
            <div class="gateway-cards-grid">

                <!-- Card 1: Existing Account / Login -->
                <div class="gateway-card">
                    <div>
                        <div class="gateway-card-header">
                            <div class="gateway-icon-wrap">👤</div>
                            <div>
                                <h2>Existing Patient</h2>
                                <p class="card-subtitle">Already have an account with us?</p>
                            </div>
                        </div>

                        <div class="gateway-card-body">
                            <p>Sign in to your private healthcare dashboard to view scheduled doctor appointments, review electronic medical records, and settle hospital invoices securely.</p>
                            <ul class="gateway-perks">
                                <li><span class="perk-check">✓</span> View and manage scheduled appointments</li>
                                <li><span class="perk-check">✓</span> Access clinical diagnoses &amp; treatment history</li>
                                <li><span class="perk-check">✓</span> Pay and track billing statements online</li>
                            </ul>
                        </div>
                    </div>

                    <a href="../auth/login.php" class="btn-gateway-action">
                        <span>Access your account to manage...</span>
                        <span>&rarr;</span>
                    </a>
                </div>

                <!-- Card 2: New Visitor / Register -->
                <div class="gateway-card register-card">
                    <div>
                        <div class="gateway-card-header">
                            <div class="gateway-icon-wrap">📝</div>
                            <div>
                                <h2>New Visitor</h2>
                                <p class="card-subtitle">First time visiting Care Compass?</p>
                            </div>
                        </div>

                        <div class="gateway-card-body">
                            <p>Create your personal patient profile in less than a minute. Gain immediate access to book specialist doctors, track consultation slots, and manage care for your family.</p>
                            <ul class="gateway-perks">
                                <li><span class="perk-check">✓</span> Quick, secure self-registration in 60 seconds</li>
                                <li><span class="perk-check">✓</span> Instant appointment booking with top consultants</li>
                                <li><span class="perk-check">✓</span> Comprehensive multi-branch hospital support</li>
                            </ul>
                        </div>
                    </div>

                    <a href="../auth/register.php" class="btn-gateway-action btn-gateway-register">
                        <span>If you don't have an account, register here</span>
                        <span>&rarr;</span>
                    </a>
                </div>

            </div>

            <!-- Hospital Trust Strip -->
            <div class="gateway-trust-strip">
                <div class="trust-item">
                    <span class="trust-item-icon">🏥</span>
                    <span>3 Modern Branches (Colombo, Kandy, Kurunegala)</span>
                </div>
                <div class="trust-item">
                    <span class="trust-item-icon">🩺</span>
                    <span>50+ Specialist Doctors &amp; Surgeons</span>
                </div>
                <div class="trust-item">
                    <span class="trust-item-icon">🛡️</span>
                    <span>100% Confidential Medical Records</span>
                </div>
                <div class="trust-item">
                    <span class="trust-item-icon">📞</span>
                    <span>24/7 Helpline: +94 788 888 624</span>
                </div>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section about">
                    <h2>About Us</h2>
                    <p>We provide high-quality healthcare and medical services to make your life easier. Quality care across Colombo, Kandy, and Kurunegala.</p>
                </div>

                <div class="footer-section links">
                    <h2>Quick Links</h2>
                    <ul>
                        <li><a href="../index.php">Home</a></li>
                        <li><a href="../doctors.php">Doctors</a></li>
                        <li><a href="../services.php">Services</a></li>
                        <li><a href="../auth/login.php">Patient Login</a></li>
                        <li><a href="../auth/register.php">Register</a></li>
                        <li><a href="../contact.php">Contact Us</a></li>
                    </ul>
                </div>

                <div class="footer-section contact">
                    <h2>Contact Us</h2>
                    <p>Email: <a href="mailto:cchospital@gmail.com">cchospital@gmail.com</a></p>
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
                <p>&copy; 2025 Care Compass Hospitals | Designed by <a href="#">Rashintha Lakshan</a></p>
            </div>
        </div>
    </footer>

    <script src="../assets/js/scripts.js"></script>
</body>
</html>

