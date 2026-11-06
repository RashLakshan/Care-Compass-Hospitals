# 🏥 Care Compass Hospital Management System

A full-stack, multi-role web application designed for **Care Compass Hospitals** (operating across Colombo, Kandy, and Kurunegala) to manage hospital operations, patient records, doctor allocations, appointment bookings, billing, and clinical services.

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

**Care Compass Hospital** provides healthcare services across multiple branches in Sri Lanka. This web application offers a clean, modular, and role-oriented digital architecture tailored for three distinct user groups:
1. **Patients** who schedule and monitor their healthcare consultations, review diagnoses, and view bills.
2. **Hospital Staff** who manage daily clinical scheduling, medical records, and billing operations.
3. **Administrators** who oversee hospital resources, medical staff, services, user accounts, and system security.

---

## 🚀 Key Features

### 1. Guest & Public Portal
- **Home & Hero Section (`index.php`)**: Hospital overview, key service highlights, doctor profiles, and emergency helpline details.
- **Doctor Directory (`doctors.php`)**: Browse and filter specialist doctors categorized by department, specialty, and branch availability.
- **Services Catalog (`services.php`)**: Details on Western Medicine, Laboratory diagnostics (MRI, Blood tests), Emergency response, and Ayurvedic therapy with online query submission.
- **About Us & Contact (`about.php`, `contact.php`)**: Hospital history, facilities, contact form, inquiry channels, and branch address details.
- **Central Dispatcher (`dashboard.php`)**: Intelligent role-based dispatcher redirecting logged-in users to their respective portal.
- **Authentication (`auth/login.php`, `auth/register.php`, `auth/logout.php`)**: Secure account registration and role-based login routing.

### 2. Patient Portal (`patient/`)
- **Patient Dashboard (`patient/portal.php`)**:
  - Live stat cards: Active appointments, recorded diagnoses, pending bills.
  - Personal profile summary card.
  - Interactive 5-star experience rating and feedback submission form.
- **Visitor Gateway (`patient/gateway.php`)**: Informative entry point with quick registration and sign-in routes.
- **Book Appointment (`patient/appointments.php`)**: Select specialist doctors, optional medical services, and preferred date/time.
- **My Appointments (`patient/view_appointments.php`)**: Real-time list of appointments with color-coded status badges (*Pending*, *Confirmed*, *Cancelled*) and cancellation support (`patient/cancel_appointment.php`).
- **Medical Records (`patient/view_medical_records.php`)**: Access physician diagnoses, prescribed treatments, and medical history.
- **Billing & Invoices (`patient/view_bill_details.php`)**: View invoices, payment methods, transaction dates, and settlement status (*Paid*, *Pending*, *Overdue*).
- **Feedback Handler (`patient/submit_feedback.php`)**: Secure processing of patient ratings and testimonials.

### 3. Hospital Staff Portal (`staff/`)
- **Staff Dashboard (`staff/portal.php`)**:
  - High-level operational KPI metrics: Active patient appointments, total medical records, outstanding bills.
  - Direct navigation to appointment management, patient medical records, and billing records.
- **Patient Medical Records (`staff/view_medical_records.php`)**: Centralized lookup for all patient treatment plans and medical documentation.
- **Manage Appointments & Bills**: Direct operational access to `admin/manage_appointments.php` and `admin/manage_bill_payments.php`.

### 4. Administrator Control Center (`admin/`)
- **Admin Dashboard (`admin/portal.php`)**:
  - Complete operational statistics (Total users, active doctors, scheduled appointments, pending payments).
  - Dedicated modular navigation grid.
- **User Management (`admin/manage_users.php`)**: Add, edit, role-assign (*admin*, *staff*, *patient*), and delete system accounts.
- **Doctor Management (`admin/manage_doctors.php`)**: Onboard doctors, assign specializations, contact details, consultation branches, and profile photos.
- **Services Management (`admin/manage_services.php`)**: Create and configure hospital medical services across Treatment, Laboratory, and Emergency categories.
- **Appointment Management (`admin/manage_appointments.php`)**: Schedule, confirm, update, or remove patient appointments.
- **Electronic Health Records (`admin/manage_medical_records.php`)**: Maintain medical consultation reports, diagnoses, and prescribed treatments.
- **Billing & Payment Management (`admin/manage_bill_payments.php`)**: Manage billing statements, payment methods (*cash*, *card*, *online*), and payment states (*pending*, *paid*, *overdue*).
- **Patient Feedback Review (`admin/manage_feedback.php`)**: Search, paginate, review, and moderate patient feedback submissions.

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
         - index.php, about.php                                        |
         - doctors.php, services.php                          +--------+--------+
         - contact.php                                        |  dashboard.php  |
         - auth/login.php, auth/register.php                  +--------+--------+
                                                                       |
                             +-----------------------+-----------------+-----------------------+
                             |                       |                                         |
                    [ Role: 'admin' ]       [ Role: 'staff' ]                         [ Role: 'patient' ]
                    admin/portal.php        staff/portal.php                          patient/portal.php
                    - Users & Doctors       - Appointments Management                 - Book Appointments
                    - Services & Billing    - View Medical Records                    - View Records & Bills
                    - Records & Feedback    - Billing Management                      - Submit Feedback
```

---

## 💻 Technology Stack

| Layer | Technologies Used |
|---|---|
| **Frontend** | HTML5, CSS3 (Modular Layouts, Modern Variables, Responsive Grid/Flexbox), JavaScript (Vanilla ES6) |
| **Backend** | PHP 8.x (Session-based RBAC, Prepared Statements, Modular Directory Architecture) |
| **Database** | MySQL 8.0+ / MariaDB 10.5+ (Relational schema, foreign keys, UTF-8 charset) |
| **Server** | Apache (WAMP / XAMPP / LAMP) or PHP Built-in Development Server |

---

## 🗄️ Database Schema

The system uses `care_compass_db` consisting of **9 core tables**:

| Table | Description |
|---|---|
| `users` | Accounts, hashed passwords (`password_hash`), contact details, and user roles (`admin`, `staff`, `patient`). |
| `doctors` | Physician profiles, specialization, location, phone, and photo file path. |
| `services` | Medical offerings categorized by `Treatment`, `Laboratory`, or `Emergency`. |
| `appointments` | Bookings linking `patient_id`, `doctor_id`, `service_id`, appointment date, and status. |
| `medical_records` | Clinical diagnoses, physician treatments, prescriptions, and log timestamps. |
| `bill_payments` | Financial records containing `amount`, `payment_method`, settlement date, and status. |
| `feedback` | Patient ratings (1–5 stars) and qualitative feedback testimonials. |
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

1. Open your database manager (**phpMyAdmin** or MySQL command line).
2. Create a new database named `care_compass_db`:
   ```sql
   CREATE DATABASE care_compass_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
   ```
3. Import the SQL file located in `database/care_compass_db.sql`:
   - Using terminal:
     ```bash
     mysql -u root -p care_compass_db < database/care_compass_db.sql
     ```
4. Verify database connection settings in [`includes/db_connect.php`](file:///d:/A%20MY%20Work/WEB%20Dev/care_compass/includes/db_connect.php):
   ```php
   $host = '127.0.0.1';
   $username = 'root';
   $password = '';
   $database = 'care_compass_db';
   ```
   *(The script includes automatic fallback detection between standard MySQL port `3306` and WAMP MariaDB port `3307`).*

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
├── admin/                         # Administrator Management Module
│   ├── manage_appointments.php    # Appointment review & status updates (Admin & Staff)
│   ├── manage_bill_payments.php   # Billing & payment records management (Admin & Staff)
│   ├── manage_doctors.php         # Doctor onboarding, specializations & photo uploads
│   ├── manage_feedback.php        # Patient review & feedback management
│   ├── manage_medical_records.php # Clinical records CRUD
│   ├── manage_services.php        # Hospital service catalog configuration
│   ├── manage_users.php           # User account & role administration
│   └── portal.php                 # Admin Control Center overview & KPI dashboard
├── assets/                        # Static Assets
│   ├── css/
│   │   ├── admin_styles.css       # Unified design system for internal portals
│   │   └── styles.css             # Responsive public portal stylesheet
│   ├── js/
│   │   └── scripts.js             # Client-side validations, interactions & UI animations
│   └── images/                    # Brand assets, banners, emergency & procedure visuals
│       └── doctors/               # Doctor profile photographs
├── auth/                          # Authentication & Session Module
│   ├── login.php                  # Multi-role authentication endpoint
│   ├── logout.php                 # Session termination & clean redirection
│   └── register.php               # Patient self-registration portal
├── database/                      # Database Scripts & Migrations
│   └── care_compass_db.sql        # Relational schema and initial seed data
├── includes/                      # Reusable Components & Configurations
│   ├── db_connect.php             # Database connection handler with auto-port fallback
│   └── header.php                 # Shared responsive header for internal portals
├── patient/                       # Patient Portal Module
│   ├── appointments.php           # Specialist appointment booking interface
│   ├── cancel_appointment.php     # Cancellation processor for pending appointments
│   ├── gateway.php                # Visitor gateway with quick sign-in / registration
│   ├── portal.php                 # Patient personal dashboard, profile & stats
│   ├── submit_feedback.php        # Patient satisfaction feedback processor
│   ├── view_appointments.php      # Appointment history & active consultation tracker
│   ├── view_bill_details.php      # Invoices & payment transaction ledger
│   └── view_medical_records.php   # Diagnosis reports & clinical treatment history
├── staff/                         # Hospital Staff Portal Module
│   ├── portal.php                 # Operational overview & daily KPI tracker
│   └── view_medical_records.php   # Read-only electronic health records inspection
├── about.php                      # Public about page
├── contact.php                    # Public contact & branch locations page
├── dashboard.php                  # Central role-based session dispatcher
├── doctors.php                    # Public searchable doctor catalog
├── index.php                      # Public hospital homepage & landing page
├── services.php                   # Public medical & diagnostic services catalog
├── LICENSE                        # Project license
└── README.md                      # Architecture documentation & setup guide
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
