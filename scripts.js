// Simple interactivity for the homepage
document.addEventListener("DOMContentLoaded", function() {
    
    const doctorNavLinks = document.querySelectorAll("nav ul li a");
    doctorNavLinks.forEach(link => {
        link.addEventListener("click", function() {
            doctorNavLinks.forEach(l => l.classList.remove("active"));
            this.classList.add("active");
        });
    });

    // Optional: Add fade-in animation for sections
    const sections = document.querySelectorAll('section');
    sections.forEach(section => {
        section.style.opacity = 0;
        section.style.transition = 'opacity 0.5s';
        setTimeout(() => {
            section.style.opacity = 1;
        }, 200);
    });
});




// Simple interactivity for the contact page

document.addEventListener("DOMContentLoaded", function() {
   
    const registerNavLinks = document.querySelectorAll("nav ul li a");
    registerNavLinks.forEach(link => {
        link.addEventListener("click", function() {
            registerNavLinks.forEach(l => l.classList.remove("active"));
            this.classList.add("active");
        });
    });

    // Toggle mobile menu
    const menuToggle = document.querySelector('.menu-toggle');
    const navLinksContainer = document.querySelector('.nav-links');
    if (menuToggle && navLinksContainer) {
        menuToggle.addEventListener('click', function() {
            navLinksContainer.classList.toggle('show');
        });
    }

    // Form validation for contact form
    const contactForm = document.querySelector('.contact-form-content form');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            const name = this.querySelector('input[name="name"]').value.trim();
            const email = this.querySelector('input[name="email"]').value.trim();
            const message = this.querySelector('textarea[name="message"]').value.trim();

            if (name === "" || email === "" || message === "") {
                e.preventDefault();
                alert("Please fill in all fields.");
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                e.preventDefault();
                alert("Please enter a valid email address.");
            }
        });
    }

    const sections = document.querySelectorAll('.contact-content > div');
    sections.forEach(section => {
        section.style.opacity = 0;
        section.style.transition = 'opacity 0.5s';
        setTimeout(() => {
            section.style.opacity = 1;
        }, 200);
    });
});




// Simple interactivity for the doctors page

document.addEventListener("DOMContentLoaded", function() {
    
    const loginNavLinks = document.querySelectorAll("nav ul li a");
    loginNavLinks.forEach(link => {
        link.addEventListener("click", function() {
            loginNavLinks.forEach(l => l.classList.remove("active"));
            this.classList.add("active");
        });
    });

    // Toggle mobile menu
    const menuToggle = document.querySelector('.menu-toggle');
    const doctorNavLinksContainer = document.querySelector('.nav-links');
    if (menuToggle && doctorNavLinksContainer) {
        menuToggle.addEventListener('click', function() {
            doctorNavLinksContainer.classList.toggle('show');
        });
    }

    // Form validation for search form
    const searchForm = document.querySelector('.search-bar form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const searchInput = this.querySelector('input[name="search"]');
            if (searchInput.value.trim() === "") {
                e.preventDefault();
                alert("Please enter a search term.");
            }
        });
    }

    const sections = document.querySelectorAll('.doctors-content > div');
    sections.forEach(section => {
        section.style.opacity = 0;
        section.style.transition = 'opacity 0.5s';
        setTimeout(() => {
            section.style.opacity = 1;
        }, 200);
    });
});



// Simple interactivity for the services page
document.addEventListener("DOMContentLoaded", function() {
    
    const portalNavLinks = document.querySelectorAll("nav ul li a");
    portalNavLinks.forEach(link => {
        link.addEventListener("click", function() {
            portalNavLinks.forEach(l => l.classList.remove("active"));
            this.classList.add("active");
        });
    });

    // Toggle mobile menu
    const menuToggle = document.querySelector('.menu-toggle');
    const servicesNavLinks = document.querySelector('.nav-links');
    if (menuToggle && servicesNavLinks) {
        menuToggle.addEventListener('click', function() {
            servicesNavLinks.classList.toggle('show');
        });
    }

    // Form validation for search form
    const searchForm = document.querySelector('.search-bar form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const searchInput = this.querySelector('input[name="search"]');
            if (searchInput.value.trim() === "") {
                e.preventDefault();
                alert("Please enter a search term.");
            }
        });
    }

    // Form validation for query forms
    const queryForms = document.querySelectorAll('.query-form');
    queryForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const patientName = this.querySelector('input[name="patient_name"]').value.trim();
            const query = this.querySelector('textarea[name="query"]').value.trim();

            if (patientName === "" || query === "") {
                e.preventDefault();
                alert("Please fill in all fields for your query.");
            }
        });
    });

    // Add fade-in animation for service sections
    const sections = document.querySelectorAll('.services-content > div');
    sections.forEach(section => {
        section.style.opacity = 0;
        section.style.transition = 'opacity 0.5s';
        setTimeout(() => {
            section.style.opacity = 1;
        }, 200);
    });
});


// Simple interactivity for the register page___________________________________________________________

document.addEventListener("DOMContentLoaded", function() {
    
    const navLinks = document.querySelectorAll("nav ul li a");
    navLinks.forEach(link => {
        link.addEventListener("click", function() {
            navLinks.forEach(l => l.classList.remove("active"));
            this.classList.add("active");
        });
    });

    // Toggle mobile menu
    const menuToggle = document.querySelector('.menu-toggle');
    const registerNavLinksContainer = document.querySelector('.nav-links');
    if (menuToggle && registerNavLinksContainer) {
        menuToggle.addEventListener('click', function() {
            registerNavLinksContainer.classList.toggle('show');
        });
    }

    // Form validation for registration
    const registerForm = document.querySelector('.register-form form');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const name = this.querySelector('input[name="name"]').value.trim();
            const username = this.querySelector('input[name="username"]').value.trim();
            const mobile = this.querySelector('input[name="mobile"]').value.trim();
            const email = this.querySelector('input[name="email"]').value.trim();
            const password = this.querySelector('input[name="password"]').value.trim();

            if (name === "" || username === "" || mobile === "" || email === "" || password === "") {
                e.preventDefault();
                alert("Please fill in all fields.");
            } else if (username.length < 3) {
                e.preventDefault();
                alert("Username must be at least 3 characters long.");
            } else if (password.length < 6) {
                e.preventDefault();
                alert("Password must be at least 6 characters long.");
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                e.preventDefault();
                alert("Please enter a valid email address.");
            } else if (!/^\+?94\d{9}$/.test(mobile)) {
                e.preventDefault();
                alert("Please enter a valid Sri Lankan mobile number (e.g., +94712345678 or 0712345678).");
            }
        });
    }

    // Add fade-in animation for register section
    const sections = document.querySelectorAll('.register-content > div');
    sections.forEach(section => {
        section.style.opacity = 0;
        section.style.transition = 'opacity 0.5s';
        setTimeout(() => {
            section.style.opacity = 1;
        }, 200);
    });
});



// interactivity for the login page_------------------------------------------------------------------------

document.addEventListener("DOMContentLoaded", function() {
    
    const loginNavLinks = document.querySelectorAll("nav ul li a");
    loginNavLinks.forEach(link => {
        link.addEventListener("click", function() {
            loginNavLinks.forEach(l => l.classList.remove("active"));
            this.classList.add("active");
        });
    });

    // Toggle mobile menu
    const menuToggle = document.querySelector('.menu-toggle');
    const loginNavLinksContainer = document.querySelector('.nav-links');
    if (menuToggle && loginNavLinksContainer) {
        menuToggle.addEventListener('click', function() {
            loginNavLinksContainer.classList.toggle('show');
        });
    }

    // Form validation for login
    const loginForm = document.querySelector('.login-form form');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const identifier = this.querySelector('input[name="identifier"]').value.trim();
            const password = this.querySelector('input[name="password"]').value.trim();

            if (identifier === "" || password === "") {
                e.preventDefault();
                alert("Please fill in all fields.");
            } else if (!/^[^\s@]+$/.test(identifier) && !/^\+?94\d{9}$/.test(identifier)) {
                e.preventDefault();
                alert("Please enter a valid username or Sri Lankan mobile number (e.g., +94712345678 or username).");
            }
        });
    }

    // Add fade-in animation for login and portal sections
    const sections = document.querySelectorAll('.login-content > div, .patient-portal-content > div');
    sections.forEach(section => {
        section.style.opacity = 0;
        section.style.transition = 'opacity 0.5s';
        setTimeout(() => {
            section.style.opacity = 1;
        }, 200);
    });
});



//  login, patient portal, and admin portal pages_____________________________________________

document.addEventListener("DOMContentLoaded", function() {
    
    const navLinks = document.querySelectorAll("nav ul li a");
    navLinks.forEach(link => {
        link.addEventListener("click", function() {
            navLinks.forEach(l => l.classList.remove("active"));
            this.classList.add("active");
        });
    });

    // Toggle mobile menu
    const menuToggle = document.querySelector('.menu-toggle');
    const portalNavLinksContainer = document.querySelector('.nav-links');
    if (menuToggle && portalNavLinksContainer) {
        menuToggle.addEventListener('click', function() {
            portalNavLinksContainer.classList.toggle('show');
        });
    }

    // validation for login 
    const loginForm = document.querySelector('.login-form form');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const identifier = this.querySelector('input[name="identifier"]').value.trim();
            const password = this.querySelector('input[name="password"]').value.trim();

            if (identifier === "" || password === "") {
                e.preventDefault();
                alert("Please fill in all fields.");
            } else if (!/^[^\s@]+$/.test(identifier) && !/^\+?94\d{9}$/.test(identifier)) {
                e.preventDefault();
                alert("Please enter a valid username or Sri Lankan mobile number (e.g., +94712345678 or username).");
            }
        });
    }

    const sections = document.querySelectorAll('.login-content > div, .patient-portal-content > div, .admin-portal-content > div');
    sections.forEach(section => {
        section.style.opacity = 0;
        section.style.transition = 'opacity 0.5s';
        setTimeout(() => {
            section.style.opacity = 1;
        }, 200);
    });
});



// manage users pages __________________________________________________________________________


document.addEventListener("DOMContentLoaded", function() {
    
    const navLinks = document.querySelectorAll("nav ul li a");
    navLinks.forEach(link => {
        link.addEventListener("click", function() {
            navLinks.forEach(l => l.classList.remove("active"));
            this.classList.add("active");
        });
    });

    // Toggle mobile menu
    const menuToggle = document.querySelector('.menu-toggle');
    const manageNavLinks = document.querySelector('.nav-links');
    if (menuToggle && manageNavLinks) {
        menuToggle.addEventListener('click', function() {
            manageNavLinks.classList.toggle('show');
        });
    }

    // Form validation for login (if present)
    const loginForm = document.querySelector('.login-form form');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const identifier = this.querySelector('input[name="identifier"]').value.trim();
            const password = this.querySelector('input[name="password"]').value.trim();

            if (identifier === "" || password === "") {
                e.preventDefault();
                alert("Please fill in all fields.");
            } else if (!/^[^\s@]+$/.test(identifier) && !/^\+?94\d{9}$/.test(identifier)) {
                e.preventDefault();
                alert("Please enter a valid username or Sri Lankan mobile number (e.g., +94712345678 or username).");
            }
        });
    }

    // Form validation for user management form
    const userForm = document.querySelector('#userForm');
    if (userForm) {
        userForm.addEventListener('submit', function(e) {
            const name = this.querySelector('input[name="name"]').value.trim();
            const username = this.querySelector('input[name="username"]').value.trim();
            const mobile = this.querySelector('input[name="mobile"]').value.trim();
            const email = this.querySelector('input[name="email"]').value.trim();
            const role = this.querySelector('select[name="role"]').value;
            const password = this.querySelector('input[name="password"]').value.trim();

            if (name === "" || username === "" || mobile === "" || email === "" || role === "") {
                e.preventDefault();
                alert("Please fill in all required fields.");
            } else if (username.length < 3) {
                e.preventDefault();
                alert("Username must be at least 3 characters long.");
            } else if (!/^\+?94\d{9}$/.test(mobile)) {
                e.preventDefault();
                alert("Please enter a valid Sri Lankan mobile number (e.g., +94712345678 or 0712345678).");
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                e.preventDefault();
                alert("Please enter a valid email address.");
            } else if (password && password.length < 6) {
                e.preventDefault();
                alert("Password must be at least 6 characters long if provided.");
            }
        });
    }

    // Cancel form function
    function cancelForm() {
        window.location.href = 'manage_users.php';
    }

    //Add fade-in animation for manage sections
    const sections = document.querySelectorAll('.manage-content > div');
    sections.forEach(section => {
        section.style.opacity = 0;
        section.style.transition = 'opacity 0.5s';
        setTimeout(() => {
            section.style.opacity = 1;
        }, 200);
    });
});

// Form validation and interactivity for manage doctors page

document.addEventListener("DOMContentLoaded", function() {
    
    const doctorForm = document.querySelector('#doctorForm');
    if (doctorForm) {
        doctorForm.addEventListener('submit', function(e) {
            const name = this.querySelector('input[name="name"]').value.trim();
            const specialty = this.querySelector('input[name="specialty"]').value.trim();
            const location = this.querySelector('input[name="location"]').value.trim();
            const contact = this.querySelector('input[name="contact"]').value.trim();

            if (name === "" || specialty === "" || location === "" || contact === "") {
                e.preventDefault();
                alert("Please fill in all required fields.");
            } else if (!/^\+?94\d{9}$/.test(contact)) {
                e.preventDefault();
                alert("Please enter a valid Sri Lankan contact number (e.g., +94712345678 or 0712345678).");
            }
        });
    }

    // Cancel form function for doctors
    function cancelForm() {
        window.location.href = 'manage_doctors.php';
    }

    // Add fade-in animation for manage doctors sections
    const sections = document.querySelectorAll('.manage-content > div');
    sections.forEach(section => {
        section.style.opacity = 0;
        section.style.transition = 'opacity 0.5s';
        setTimeout(() => {
            section.style.opacity = 1;
        }, 200);
    });
});

// Form validation and interactivity for manage services page
document.addEventListener("DOMContentLoaded", function() {
    
    const serviceForm = document.querySelector('#serviceForm');
    if (serviceForm) {
        serviceForm.addEventListener('submit', function(e) {
            const name = this.querySelector('input[name="name"]').value.trim();
            const description = this.querySelector('textarea[name="description"]').value.trim();
            const category = this.querySelector('input[name="category"]').value.trim();

            if (name === "" || description === "" || category === "") {
                e.preventDefault();
                alert("Please fill in all required fields.");
            }
        });
    }

    // Cancel form function for services
    function cancelForm() {
        window.location.href = 'manage_services.php';
    }

    // Add fade-in animation for manage services sections
    const sections = document.querySelectorAll('.manage-content > div');
    sections.forEach(section => {
        section.style.opacity = 0;
        section.style.transition = 'opacity 0.5s';
        setTimeout(() => {
            section.style.opacity = 1;
        }, 200);
    });
});

// manage appointments page_______________________________________________________________________________

document.addEventListener("DOMContentLoaded", function() {
    
    const appointmentForm = document.querySelector('#appointmentForm');
    if (appointmentForm) {
        appointmentForm.addEventListener('submit', function(e) {
            const patient_id = this.querySelector('select[name="patient_id"]').value;
            const doctor_id = this.querySelector('select[name="doctor_id"]').value;
            const appointment_date = this.querySelector('input[name="appointment_date"]').value;
            const status = this.querySelector('select[name="status"]').value;

            if (patient_id === "" || doctor_id === "" || appointment_date === "" || status === "") {
                e.preventDefault();
                alert("Please fill in all required fields.");
            } else if (!/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/.test(appointment_date)) {
                e.preventDefault();
                alert("Please enter a valid date and time in the format YYYY-MM-DDTHH:MM.");
            }
        });
    }

    // Cancel form function for appointments
    function cancelForm() {
        window.location.href = 'manage_appointments.php';
    }

    //Add fade-in animation for manage appointments sections
    const sections = document.querySelectorAll('.manage-content > div');
    sections.forEach(section => {
        section.style.opacity = 0;
        section.style.transition = 'opacity 0.5s';
        setTimeout(() => {
            section.style.opacity = 1;
        }, 200);
    });
});


// manage medical records page______________________________________________________________________

document.addEventListener("DOMContentLoaded", function() {
    
    const medicalRecordForm = document.querySelector('#medicalRecordForm');
    if (medicalRecordForm) {
        medicalRecordForm.addEventListener('submit', function(e) {
            const patient_id = this.querySelector('select[name="patient_id"]').value;
            const doctor_id = this.querySelector('select[name="doctor_id"]').value;
            const diagnosis = this.querySelector('textarea[name="diagnosis"]').value.trim();
            const treatment = this.querySelector('textarea[name="treatment"]').value.trim();

            if (patient_id === "" || doctor_id === "" || diagnosis === "" || treatment === "") {
                e.preventDefault();
                alert("Please fill in all required fields.");
            }
        });
    }

    // Cancel form function for medical records
    function cancelForm() {
        window.location.href = 'manage_medical_records.php';
    }

    // Add fade-in animation for manage medical records sections
    const sections = document.querySelectorAll('.manage-content > div');
    sections.forEach(section => {
        section.style.opacity = 0;
        section.style.transition = 'opacity 0.5s';
        setTimeout(() => {
            section.style.opacity = 1;
        }, 200);
    });
});

// bill payments page__-----------------------------------------------------------------------------------

document.addEventListener("DOMContentLoaded", function() {
    
    const billPaymentForm = document.querySelector('#billPaymentForm');
    if (billPaymentForm) {
        billPaymentForm.addEventListener('submit', function(e) {
            const appointment_id = this.querySelector('select[name="appointment_id"]').value;
            const patient_id = this.querySelector('select[name="patient_id"]').value;
            const amount = this.querySelector('input[name="amount"]').value.trim();
            const payment_date = this.querySelector('input[name="payment_date"]').value;
            const payment_method = this.querySelector('select[name="payment_method"]').value;
            const status = this.querySelector('select[name="status"]').value;

            if (appointment_id === "" || patient_id === "" || amount === "" || payment_date === "" || payment_method === "" || status === "") {
                e.preventDefault();
                alert("Please fill in all required fields.");
            } else if (!/^\d+(\.\d{1,2})?$/.test(amount)) {
                e.preventDefault();
                alert("Please enter a valid amount (e.g., 100.00 or 50).");
            } else if (!/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/.test(payment_date)) {
                e.preventDefault();
                alert("Please enter a valid date and time in the format YYYY-MM-DDTHH:MM.");
            }
        });
    }

    // Cancel form function for bill payments
    function cancelForm() {
        window.location.href = 'manage_bill_payments.php';
    }

    //Add fade-in animation for manage bill payments sections
    const sections = document.querySelectorAll('.manage-content > div');
    sections.forEach(section => {
        section.style.opacity = 0;
        section.style.transition = 'opacity 0.5s';
        setTimeout(() => {
            section.style.opacity = 1;
        }, 200);
    });
});


// patient portal appointments page------------------------------------------------------------

document.addEventListener("DOMContentLoaded", function() {
    
    const sections = document.querySelectorAll('.patient-portal-content > div');
    sections.forEach(section => {
        section.style.opacity = 0;
        section.style.transition = 'opacity 0.5s';
        setTimeout(() => {
            section.style.opacity = 1;
        }, 200);
    });
});

// edical records page----------------------------------------------------------------------------
document.addEventListener("DOMContentLoaded", function() {
    // Optional: Add fade-in animation for patient portal content
    const sections = document.querySelectorAll('.patient-portal-content > div');
    sections.forEach(section => {
        section.style.opacity = 0;
        section.style.transition = 'opacity 0.5s';
        setTimeout(() => {
            section.style.opacity = 1;
        }, 200);
    });
});

//manage bills page---------------------------------------------------------------------------------

document.addEventListener("DOMContentLoaded", function() {
    // Add fade-in animation for patient portal content
    const sections = document.querySelectorAll('.patient-portal-content > div');
    sections.forEach(section => {
        section.style.opacity = 0;
        section.style.transition = 'opacity 0.5s';
        setTimeout(() => {
            section.style.opacity = 1;
        }, 200);
    });
});


// appointments page-----------------------------------------------------------------------
document.addEventListener("DOMContentLoaded", function() {
    // Form validation for appointment booking form
    const appointmentForm = document.querySelector('#appointmentForm');
    if (appointmentForm) {
        appointmentForm.addEventListener('submit', function(e) {
            const doctor_id = this.querySelector('select[name="doctor_id"]').value;
            const appointment_date = this.querySelector('input[name="appointment_date"]').value;
            const service_id = this.querySelector('select[name="service_id"]').value;

            if (doctor_id === "" || appointment_date === "") {
                e.preventDefault();
                alert("Please fill in all required fields (Doctor and Appointment Date & Time are required).");
            } else if (!/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/.test(appointment_date)) {
                e.preventDefault();
                alert("Please enter a valid date and time in the format YYYY-MM-DDTHH:MM.");
            }
        });
    }

    // Cancel appointment confirmation (for pending appointments)
    const cancelLinks = document.querySelectorAll('.portal-btn[style*="background-color: #dc3545"]');
    cancelLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to cancel this appointment?')) {
                e.preventDefault();
            }
        });
    });

    // Optional: Add fade-in animation for patient portal content
    const sections = document.querySelectorAll('.patient-portal-content > div');
    sections.forEach(section => {
        section.style.opacity = 0;
        section.style.transition = 'opacity 0.5s';
        setTimeout(() => {
            section.style.opacity = 1;
        }, 200);
    });
});


document.addEventListener("DOMContentLoaded", function() {
    // Add confirmation for logout button
    const logoutButton = document.querySelector('a[href="logout.php"]');
    if (logoutButton) {
        logoutButton.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to log out?')) {
                e.preventDefault();
            }
        });
    }
});

// staff portal page------------------------------------------------------------------------
document.addEventListener("DOMContentLoaded", function() {
   
    const sections = document.querySelectorAll('.manage-content > div');
    sections.forEach(section => {
        section.style.opacity = 0;
        section.style.transition = 'opacity 0.5s';
        setTimeout(() => {
            section.style.opacity = 1;
        }, 200);
    });

    // Add confirmation for logout button
    const logoutButton = document.querySelector('a[href="logout.php"]');
    if (logoutButton) {
        logoutButton.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to log out?')) {
                e.preventDefault();
            }
        });
    }
});

// records page------------------------------------------------------------------------------------

document.addEventListener("DOMContentLoaded", function() {
    const sections = document.querySelectorAll('.patient-portal-content > div');
    sections.forEach(section => {
        section.style.opacity = 0;
        section.style.transition = 'opacity 0.5s';
        setTimeout(() => {
            section.style.opacity = 1;
        }, 200);
    });

    // Add confirmation for logout button
    const logoutButton = document.querySelector('a[href="logout.php"]');
    if (logoutButton) {
        logoutButton.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to log out?')) {
                e.preventDefault();
            }
        });
    }
});

// Interactivity for about us page
document.addEventListener("DOMContentLoaded", function() {
    
    const sections = document.querySelectorAll('.about-content > .vertical-center > .about-section');
    sections.forEach(section => {
        section.style.opacity = 0;
        section.style.transition = 'opacity 0.5s';
        setTimeout(() => {
            section.style.opacity = 1;
        }, 200);
    });
});

// Form validation for feedback submission__________________________________________________________

const feedbackForm = document.querySelector('form[action="submit_feedback.php"]');
if (feedbackForm) {
    feedbackForm.addEventListener('submit', function(e) {
        const feedbackText = this.querySelector('textarea[name="feedback_text"]').value.trim();
        const rating = this.querySelector('select[name="rating"]').value;

        if (feedbackText === "" || rating < 1 || rating > 5) {
            e.preventDefault();
            alert("Please provide valid feedback and a rating (1-5 stars).");
        }
    });
}