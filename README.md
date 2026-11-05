# 🏥 Care Compass Hospital Management System

A full-stack, multi-role web application designed for **Care Compass Hospitals** (operating across Colombo, Kandy, and Kurunegala) to manage hospital operations, patient records, doctor allocations, appointment bookings, billing, and services.

---

## 📋 Table of Contents

- [Overview](#-overview)
- [Key Features](#-key-features)
  - [1. Guest & Public Portal](#1-guest--public-portal)
  - [2. Patient Portal](#2-patient-portal)
  - [3. Hospital Staff Portal](#3-hospital-staff-portal)
  - [4. Administrator Control Center](#4-administrator-control-center)
- [System Architecture & Roles](#-system-architecture--roles)
- [Technology Stack](#-technology-stack)
- [Database Schema](#-database-schema)
- [Installation & Setup](#-installation--setup)
  - [Prerequisites](#prerequisites)
  - [Database Setup](#database-setup)
  - [Application Setup](#application-setup)
- [Demo User Credentials](#-demo-user-credentials)
- [Directory Structure](#-directory-structure)
- [Security & Best Practices](#-security--best-practices)
- [License & Credits](#-license--credits)

---

## 🌟 Overview

**Care Compass Hospital** provides healthcare services across multiple branches in Sri Lanka. This web application offers a seamless digital interface tailored for three distinct user groups:
1. **Patients** who schedule and monitor their healthcare consultations.
2. **Hospital Staff** who manage daily clinical scheduling and records.
3. **Administrators** who oversee hospital resources, medical staff, services, and system security.

---

## 🚀 Key Features

### 1. Guest & Public Portal
- **Home & Hero Section (`index.php`)**: Hospital overview, key service highlights, doctor profiles, and emergency helpline details.
- **Doctor Directory (`doctors.php`)**: Browse specialist doctors categorized by department and availability.
- **Services Catalog (`services.php`)**: Details on Western Medicine, Laboratory diagnostics (MRI, Blood tests), Emergency response, and Ayurvedic therapy.
- **About Us & Contact (`about.php`, `contact.php`)**: Hospital history, facilities, contact form, inquiry channels, and Google Maps location details.
- **Authentication (`login.php`, `register.php`)**: Secure account registration and role-based login routing via `dashboard.php`.

### 2. Patient Portal
- **Patient Dashboard (`patient_portal.php`)**:
  - Live stat cards: Active appointments, recorded diagnoses, pending bills.
  - Personal profile summary card.
  - Interactive 5-star experience rating and feedback submission form.
- **Book Appointment (`appointments.php`)**: Select specialist doctors, optional medical services, and preferred date/time.
- **My Appointments (`view_appointments.php`)**: Real-time list of appointments with color-coded status badges (*Pending*, *Confirmed*, *Cancelled*) and cancellation support.
- **Medical Records (`view_medical_records.php`)**: Access physician diagnoses, prescribed treatments, and medical history.
- **Billing & Invoices (`view_bill_details.php`)**: View invoices, payment methods, transaction dates, and settlement status (*Paid*, *Pending*, *Overdue*).

### 3. Hospital Staff Portal
- **Staff Dashboard (`staff_portal.php`)**:
  - High-level KPI metrics: Active patient appointments, total medical records, outstanding bills.
- **Manage Appointments (`manage_appointments.php`)**: Confirm or update incoming patient bookings.
- **Patient Medical Records (`view_med_records.php`)**: Centralized lookup for all patient treatment plans and medical documentation.
- **Manage Bill Payments (`manage_bill_payments.php`)**: Track and update billing transactions, amounts, and settlement statuses.

### 4. Administrator Control Center
- **Admin Dashboard (`admin_portal.php`)**:
  - Complete operational statistics (Total users, active doctors, scheduled appointments, pending payments).
  - Dedicated modular navigation grid.
- **User Management (`manage_users.php`)**: Add, edit, role-assign (*admin*, *staff*, *patient*), and delete system accounts.
- **Doctor Management (`manage_doctors.php`)**: Onboard doctors, assign specializations, contact details, consultation rooms, and profile photos.
- **Services Management (`manage_services.php`)**: Create and configure hospital medical services across Treatment, Laboratory, and Emergency categories.
- **Full Operational Access**: Full control over appointments, electronic medical records, payment transactions, and patient feedback.

---

## 👥 System Architecture & Roles

```
                               +----------------------------+
                               |     Care Compass Web       |
                               +--------------+-------------+
                                              |
                     +------------------------+------------------------+
                     |                                                 |
         [ Public Visitors ]                                [ Authenticated Users ]
         - Home, About, Doctors                                        |
         - Services, Contact                                  +--------+--------+
         - Login / Registration                               |  dashboard.php  |
                                                              +--------+--------+
                                                                       |
                             +-----------------------+-----------------+-----------------------+
                             |                       |                                         |
                    [ Role: 'admin' ]       [ Role: 'staff' ]                         [ Role: 'patient' ]
                    admin_portal.php        staff_portal.php                          patient_portal.php
                    - Users & Doctors       - Manage Appointments                     - Book Appointments
                    - Services & Billing    - View Medical Records                    - View Records & Bills
                    - Records & Feedback    - Manage Bill Payments                    - Submit Feedback
```

---

## 💻 Technology Stack

| Layer | Technologies Used |
|---|---|
| **Frontend** | HTML5, CSS3 (Custom Responsive Layouts & Variables), JavaScript (Vanilla ES6) |
| **Backend** | PHP 8.x (Session management, prepared statements, role-based access control) |
| **Database** | MySQL / MariaDB (Relational schema, foreign keys, UTF-8 charset) |
| **Server** | Apache (WAMP / XAMPP / LAMP) or PHP Built-in Development Server |

---

## 🗄️ Database Schema

The system uses `care_compass_db` consisting of **9 core tables**:

| Table | Description |
|---|---|
| `users` | Accounts, hashed passwords (`password_hash`), contact details, and user roles (`admin`, `staff`, `patient`). |
| `doctors` | Physician profiles, specialization, qualification, phone, email, room, and photo file path. |
| `services` | Medical offerings categorized by `treatment`, `laboratory`, or `emergency`. |
| `appointments` | Bookings linking `patient_id`, `doctor_id`, `service_id`, appointment date, and status. |
| `medical_records` | Clinical diagnoses, physician treatments, prescriptions, and log timestamps. |
| `bill_payments` | Financial records containing `amount`, `payment_method`, settlement date, and status. |
| `feedback` | Patient ratings (1–5 stars) and qualitative feedback. |
| `contact_queries` | General public inquiries submitted via the contact page. |
| `service_queries` | Specialized questions regarding specific medical procedures. |

---

## ⚙️ Installation & Setup

### Prerequisites
- [PHP 8.1+](https://www.php.net/) with `mysqli` extension enabled.
- [MySQL 8.0+](https://www.mysql.com/) or [MariaDB 10.5+](https://mariadb.org/).
- Web Server: **WampServer**, **XAMPP**, or Apache.

---

### Database Setup

1. Open your database manager (**phpMyAdmin** or terminal client).
2. Create a new database named `care_compass_db`:
   ```sql
   CREATE DATABASE care_compass_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
   ```
3. Import the SQL file included in the repository:
   - File: `care_compass_db.sql`
   - Using terminal:
     ```bash
     mysql -u root -p care_compass_db < care_compass_db.sql
     ```
4. Verify database connection settings in [`db_connect.php`](file:///d:/A%20MY%20Work/WEB%20Dev/care_compass/db_connect.php):
   ```php
   $host = '127.0.0.1';
   $username = 'root';
   $password = '';
   $database = 'care_compass_db';
   ```
   *(The script includes automatic fallback detection between standard port `3306` and WAMP MariaDB port `3307`).*

---

### Application Setup

#### Option A: Running via WampServer / XAMPP
1. Move or clone the project folder into your web root:
   - **WampServer**: `C:\wamp64\www\care_compass\`
   - **XAMPP**: `C:\xampp\htdocs\care_compass\`
2. Start the Apache and MySQL/MariaDB services from your control panel.
3. Open your browser and navigate to:
   ```
   http://localhost/care_compass/
   ```

#### Option B: Running with PHP CLI Built-in Server
1. Open PowerShell / Command Prompt inside the project directory:
   ```bash
   cd "d:\A MY Work\WEB Dev\care_compass"
   ```
2. Start the local server:
   ```bash
   php -S 127.0.0.1:8000
   ```
3. Open your browser at:
   ```
   http://127.0.0.1:8000
   ```

---

## 🔑 Demo User Credentials

The database comes pre-seeded with sample user accounts for testing:

| Role | Username | Password | Access Level |
|---|---|---|---|
| **Administrator** | `admin` | `admin123` | Full access to Admin Portal, User Management, Doctors, Services, and System Data |
| **Hospital Staff** | `hasith` | `staff123` | Staff Portal, Appointment verification, Medical Records, Billing records |
| **Patient** | `rash` | `123456` | Patient Portal, Appointment booking & cancellation, Invoices, Medical logs |
| **Patient** | `Pramu` | `123456` | Secondary patient testing profile |

---

## 📁 Directory Structure

```
care_compass/
├── images/                        # Hospital logo, doctor photos, banners, icons
├── care_compass_db.sql            # Database schema & seed data
├── db_connect.php                 # Database connection handler with port fallback
├── dashboard.php                  # Session role router (admin/staff/patient)
├── login.php                      # Authentication portal
├── login2.php                     # Visitor portal gateway (Login / Register options)
├── register.php                   # Patient self-registration
├── logout.php                     # Session termination script
│
├── index.php                      # Public homepage
├── about.php                      # About Care Compass Hospital
├── doctors.php                    # Doctor directory and search
├── services.php                   # Clinical & diagnostic services catalog
├── contact.php                    # Inquiries and location map
│
├── admin_header.php               # Shared minimal header for internal portals
├── admin_styles.css               # Dedicated styling for Admin, Staff & Patient portals
├── styles.css                     # Main styling for public web pages
├── scripts.js                     # Mobile navbar toggle & form validation helpers
│
├── admin_portal.php               # Administrator control center
├── manage_users.php               # Admin: User CRUD
├── manage_doctors.php             # Admin: Doctor onboarding & photo uploads
├── manage_services.php            # Admin: Clinical service management
├── manage_appointments.php        # Admin & Staff: Appointment management
├── manage_medical_records.php     # Admin: Full medical records management
├── manage_bill_payments.php       # Admin & Staff: Billing and payment tracking
├── manage_feedback.php            # Admin: Patient feedback review
│
├── staff_portal.php               # Hospital Staff overview dashboard
├── view_med_records.php           # Staff: Patient medical history lookup
│
├── patient_portal.php             # Patient dashboard & profile overview
├── appointments.php               # Patient: Book appointment form & schedule
├── cancel_appointment.php         # Patient: Cancel pending booking
├── view_appointments.php          # Patient: Appointment status tracking
├── view_medical_records.php       # Patient: Medical diagnosis & treatments
├── view_bill_details.php          # Patient: Billing statements & payment logs
└── submit_feedback.php            # Patient: Feedback form processor
```

---

## 🔒 Security & Best Practices

- **Role-Based Access Control (RBAC)**: Every private endpoint verifies `$_SESSION['user_id']` and the specific allowed `$_SESSION['role']`, preventing privilege escalation.
- **SQL Injection Prevention**: All database queries utilize **Prepared Statements** (`mysqli_prepare` and `mysqli_stmt_bind_param`) for user-supplied inputs.
- **Cross-Site Scripting (XSS) Mitigation**: User outputs are sanitized using `htmlspecialchars()` throughout HTML templates.
- **Password Security**: Passwords are saved using one-way hashes via PHP's native `password_hash()` (Bcrypt).
- **File Upload Validation**: Doctor image uploads are strictly validated by allowed image extensions (`jpg`, `jpeg`, `png`, `webp`).

---

## 📄 License & Credits

- **Project**: Care Compass Hospitals Web Portal
- **Designed & Developed for**: Care Compass Hospital Operations
- **Author**: Rashintha Lakshan Tharanga
- **Year**: 2025 – 2026

