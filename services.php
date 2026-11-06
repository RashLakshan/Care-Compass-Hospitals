<?php
session_start();

// Database connection
include 'includes/db_connect.php';

$services = [];
$sql = "SELECT id, name, description, category FROM services";
$result = mysqli_query($conn, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $services[] = $row;
    }
} else {
    echo "Error fetching services: " . mysqli_error($conn);
}

// Handle search query if submitted
$search_query = "";
if (isset($_GET['search'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT id, name, description, category FROM services WHERE name LIKE ? OR category LIKE ?";
    $stmt = mysqli_prepare($conn, $sql);
    $search_term = "%" . $search_query . "%";
    mysqli_stmt_bind_param($stmt, "ss", $search_term, $search_term);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $search_results = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $search_results[] = $row;
        }
    } else {
        echo "Error searching services: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}

// Handle service query submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_query'])) {
    $patient_name = mysqli_real_escape_string($conn, $_POST['patient_name']);
    $query = mysqli_real_escape_string($conn, $_POST['query']);
    $service_id = mysqli_real_escape_string($conn, $_POST['service_id']);

    $sql = "INSERT INTO service_queries (patient_name, query, service_id, status) VALUES (?, ?, ?, 'pending')";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssi", $patient_name, $query, $service_id);

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
    <title>Care Compass Hospitals - Services</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <!-- Header Section  -->
    <header>
        <!-- Top Contact Bar -->
        <div class="top-bar">
            <div class="container">
                <div class="contact-info">
                    <a href="tel:+94788888624">📞 +94 788 888 624</a>
                    <a href="mailto:cchospital@gmail.com">✉️ cchospital@gmail.com</a>
                </div>
                <nav class="top-nav">
                    <a href="auth/login.php">Log in</a>
                    <a href="auth/register.php">Register</a>
                </nav>
            </div>
        </div>

        <!-- Navigation Bar -->
        <nav class="navbar">
            <div class="container">
                <a class="navbar-brand" href="index.php">
                    <img src="assets/images/cchlogo.png" alt="Care Compass Hospital" width="300">
                </a>
                <button class="menu-toggle">☰</button>
                <div class="nav-links">
                    <a href="index.php">Home</a>
                    <a href="doctors.php">Doctors</a>
                    <a href="services.php" class="active">Services</a>
                    <a href="patient/gateway.php">Patient Portal</a>
                    <a href="contact.php">Contact Us</a>
                </div>
                <a href="patient/appointments.php" class="btn">Appointment</a>
            </div>
        </nav>
    </header>

    <!-- Services Content (Vertically Centered) -->
    <section class="services-content">
    <div class="container">
        <div class="vertical-center">
            <h1 class="text-center mb-4" style="color: #007bff; font-weight: bolder;">Our Services</h1>
            <p class="text-center mb-5">Explore our comprehensive healthcare services across Kandy, Colombo, and Kurunegala.</p>

            <!-- Search Form -->
            <div class="search-bar text-center mb-5">
                <form method="GET" action="services.php">
                    <input type="text" name="search" placeholder="Search services by name or category..." value="<?php echo htmlspecialchars($search_query); ?>" class="form-input">
                    <input type="submit" value="Search" class="btn">
                </form>
            </div>

            <?php if (!empty($search_query) && empty($search_results)): ?>
                <p class="text-center">No services found matching your search.</p>
            <?php elseif (!empty($search_results)): ?>
                <h2 class="text-center mb-4" style="color: #007bff; font-weight: bolder;">Search Results</h2>
                <div class="service-container two-rows">
                    <?php foreach ($search_results as $service): ?>
                        <div class="service-box">
                            <h3><?php echo htmlspecialchars($service['name']); ?></h3>
                            <p><?php echo htmlspecialchars($service['description']); ?></p>
                            <p><strong>Category:</strong> <?php echo htmlspecialchars($service['category']); ?></p>
                            <form method="POST" class="query-form">
                                <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">
                                <label for="patient_name_<?php echo $service['id']; ?>">Your Name:</label>
                                <input type="text" name="patient_name" id="patient_name_<?php echo $service['id']; ?>" required placeholder="Enter your name" class="form-input" style="width: 100%; margin-bottom: 10px;"><br>
                                <label for="query_<?php echo $service['id']; ?>">Your Query:</label>
                                <textarea name="query" id="query_<?php echo $service['id']; ?>" required placeholder="Your question or request..." rows="3" class="form-input" style="width: 100%; margin-bottom: 10px;"></textarea><br>
                                <input type="submit" name="submit_query" value="Submit Query" class="btn">
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="service-container two-rows">
                    <?php foreach ($services as $service): ?>
                        <div class="service-box">
                            <h3><?php echo htmlspecialchars($service['name']); ?></h3>
                            <p><?php echo htmlspecialchars($service['description']); ?></p>
                            <p><strong>Category:</strong> <?php echo htmlspecialchars($service['category']); ?></p>
                            <form method="POST" class="query-form">
                                <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">
                                <label for="patient_name_<?php echo $service['id']; ?>">Your Name:</label>
                                <input type="text" name="patient_name" id="patient_name_<?php echo $service['id']; ?>" required placeholder="Enter your name" class="form-input" style="width: 100%; margin-bottom: 10px;"><br>
                                <label for="query_<?php echo $service['id']; ?>">Your Query:</label>
                                <textarea name="query" id="query_<?php echo $service['id']; ?>" required placeholder="Your question or request..." rows="3" class="form-input" style="width: 100%; margin-bottom: 10px;"></textarea><br>
                                <input type="submit" name="submit_query" value="Submit Query" class="btn">
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
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
                        <li><a href="patient/appointments.php">Appointments</a></li>
                        <li><a href="auth/login.php">Patient Portal</a></li>
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
                        <a href="#"><img src="assets/images/facebook.png" alt="Facebook"></a>
                        <a href="#"><img src="assets/images/twitter.png" alt="Twitter"></a>
                        <a href="#"><img src="assets/images/instagram.png" alt="Instagram"></a>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p>© 2025 | Designed by <a href="#">Rashintha Lakshan</a></p>
            </div>
        </div>
    </footer>

    <script src="assets/js/scripts.js"></script>
</body>
</html>
