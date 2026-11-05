<?php

session_start();

// Database connection
include 'db_connect.php';

// Fetch doctors from the database
$doctors = [];
$sql = "SELECT id, name, specialty, location, contact FROM doctors";
$result = mysqli_query($conn, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $doctors[] = $row;
    }
} else {
    echo "Error fetching doctors: " . mysqli_error($conn);
}

// Handle search query if submitted
$search_query = "";
if (isset($_GET['search'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT id, name, specialty, location, contact FROM doctors WHERE name LIKE ? OR specialty LIKE ? OR location LIKE ?";
    $stmt = mysqli_prepare($conn, $sql);
    $search_term = "%" . $search_query . "%";
    mysqli_stmt_bind_param($stmt, "sss", $search_term, $search_term, $search_term);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $search_results = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $search_results[] = $row;
        }
    } else {
        echo "Error searching doctors: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Care Compass Hospitals - Doctors</title>
    <!-- Custom CSS  -->
    <link rel="stylesheet" href="styles.css">
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
                    <a href="doctors.php" class="active">Doctors</a>
                    <a href="services.php">Services</a>
                    <a href="ptportalloging.php">Patient Portal</a>
                    <a href="contact.php">Contact Us</a>
                </div>
                <a href="appointments.php" class="btn">Appointment</a>
            </div>
        </nav>
    </header>

    <!-- Doctors Content  -->
    <section class="doctors-content">
        <div class="container">
            <div class="vertical-center">
                <h1 class="text-center mb-4" style="color: #007bff; font-weight: bolder;">Our Doctors</h1>
                <p class="text-center mb-5">Meet our highly qualified medical professionals across Kandy, Colombo, and Kurunegala.</p>

                <!-- Search Form -->
                <div class="search-bar text-center mb-5">
                    <form method="GET" action="doctors.php">
                        <input type="text" name="search" placeholder="Search doctors by name, specialty, or location..." value="<?php echo htmlspecialchars($search_query); ?>" class="form-input" style="width: 300px; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 16px;">
                        <input type="submit" value="Search" class="btn" style="margin-left: 10px; padding: 10px 20px;">
                    </form>
                </div>

                <?php if (!empty($search_query) && empty($search_results)): ?>
                    <p class="text-center">No doctors found matching your search.</p>
                <?php elseif (!empty($search_results)): ?>
                    <h2 class="text-center mb-4" style="color: #007bff; font-weight: bolder;">Search Results</h2>
                    <div class="doctor-container">
                        <?php foreach ($search_results as $doctor): ?>
                            <div class="doctor-box">
                                <img src="images/doctors/<?php echo htmlspecialchars($doctor['id']); ?>.jpg" alt="<?php echo htmlspecialchars($doctor['name']); ?>" class="doctor-photo">
                                <h3><?php echo htmlspecialchars($doctor['name']); ?></h3>
                                <p><strong>Specialty:</strong> <?php echo htmlspecialchars($doctor['specialty']); ?></p>
                                <p><strong>Location:</strong> <?php echo htmlspecialchars($doctor['location']); ?></p>
                                <p><strong>Contact:</strong> <?php echo htmlspecialchars($doctor['contact']); ?></p>
                                <a href="appointments.php?doctor_id=<?php echo $doctor['id']; ?>" class="btn">Book Appointment</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="doctor-container">
                        <?php foreach ($doctors as $doctor): ?>
                            <div class="doctor-box">
                                <img src="images/doctors/<?php echo htmlspecialchars($doctor['id']); ?>.jpg" alt="<?php echo htmlspecialchars($doctor['name']); ?>" class="doctor-photo">
                                <h3><?php echo htmlspecialchars($doctor['name']); ?></h3>
                                <p><strong>Specialty:</strong> <?php echo htmlspecialchars($doctor['specialty']); ?></p>
                                <p><strong>Location:</strong> <?php echo htmlspecialchars($doctor['location']); ?></p>
                                <p><strong>Contact:</strong> <?php echo htmlspecialchars($doctor['contact']); ?></p>
                                <a href="appointments.php?doctor_id=<?php echo $doctor['id']; ?>" class="btn">Book Appointment</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
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