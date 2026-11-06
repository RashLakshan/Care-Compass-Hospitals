# 🏥 Care Compass Hospital Management System

> **Academic Project Notice**: This software was designed and developed as a **University Academic Assignment** for a Web Application Development and Software Engineering module. It represents an end-to-end, multi-role hospital management web portal demonstrating secure server-side programming, relational database management, and modular architecture.

[![PHP](https://img.shields.io/badge/PHP-8.1%2B-777BB4?style=flat-square&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-4479A1?style=flat-square&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Apache](https://img.shields.io/badge/Server-Apache%20%2F%20WAMP%20%2F%20XAMPP-D22128?style=flat-square&logo=apache&logoColor=white)](https://httpd.apache.org/)
[![License](https://img.shields.io/badge/License-Apache%202.0-blue.svg?style=flat-square)](LICENSE)
[![Project Type](https://img.shields.io/badge/Project-University%20Assignment-success?style=flat-square)](#-project-information)

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
- [Directory Structure](#-directory-structure)
- [Installation & Setup](#-installation--setup)
  - [Prerequisites](#prerequisites)
  - [Database Setup](#database-setup)
  - [Application Setup](#application-setup)
- [Demo User Credentials](#-demo-user-credentials)
- [Security & Implementation Standards](#-security--implementation-standards)
- [Limitations & Future Roadmap](#-limitations--future-roadmap)
- [Project Information](#-project-information)
- [License](#-license)

---

## 🌟 Overview

**Care Compass Hospital** delivers comprehensive healthcare services across three major regional branches in Sri Lanka (Colombo, Kandy, and Kurunegala). 

This application provides a clean, responsive, and secure web interface engineered to streamline administrative overhead, improve clinical communication, and empower patients with self-service healthcare management. The system coordinates three distinct authenticated user tiers—**Patients**, **Hospital Staff**, and **Administrators**—alongside a public-facing informational portal.

---

## 🚀 Key Features

### 1. Guest & Public Portal
- **Landing Page (`index.php`)**: Hospital overview, key service highlights, doctor profiles, dynamic patient testimonials, and emergency contact details.
- **Doctor Directory (`doctors.php`)**: Search and filter specialist doctors by name, medical specialty, or branch location.
- **Services Catalog (`services.php`)**: Detailed breakdown of Western Medicine, Laboratory diagnostics (MRI, Blood tests), Emergency response, and Ayurvedic therapy with online service query submission.
- **About Us & Contact (`about.php`, `contact.php`)**: Hospital history, facilities, branch locations, interactive contact inquiry forms, and emergency hotlines.
- **Role-Based Dispatcher (`dashboard.php`)**: Central authentication gatekeeper that automatically detects the authenticated user's session role and redirects them to their designated portal.
- **Authentication Gateway (`auth/`)**:
  - `auth/login.php`: Unified login allowing authentication via username or registered mobile number.
  - `auth/register.php`: Patient self-registration with duplicate username, email, and phone validation.
  - `auth/logout.php`: Complete session termination and secure redirect to the login gateway.

### 2. Patient Portal (`patient/`)
- **Patient Dashboard (`patient/portal.php`)**:
  - Real-time KPI summary cards: Active appointments, electronic medical records, and pending/unpaid bills.
  - Personal profile summary detailing contact details and patient identifier.
  - Integrated 5-star rating and qualitative feedback submission form.
- **Visitor Gateway (`patient/gateway.php`)**: Informative entry point with quick registration and sign-in routes for patients and visitors.
- **Book Appointment (`patient/appointments.php`)**: Schedule appointments by selecting specialist doctors, optional medical services, and preferred date/time slots.
- **Appointment Management (`patient/view_appointments.php`)**: Real-time list of booked visits with color-coded status badges (*Pending*, *Confirmed*, *Cancelled*) and cancellation support (`patient/cancel_appointment.php`).
- **Electronic Health Records (`patient/view_medical_records.php`)**: Secure lookup for clinical diagnoses, prescribed treatment plans, consulting doctors, and visit timestamps.
- **Billing & Invoices (`patient/view_bill_details.php`)**: Access itemized invoices, payment methods (*cash*, *card*, *online*), transaction timestamps, and settlement statuses (*Paid*, *Pending*, *Overdue*).
- **Feedback Handler (`patient/submit_feedback.php`)**: Secure server-side validation and insertion of patient satisfaction feedback.

### 3. Hospital Staff Portal (`staff/`)
- **Staff Dashboard (`staff/portal.php`)**:
  - Daily overview metrics: Active appointments, total clinical records, and pending bills.
  - Quick-action navigation grid for daily workflow tasks.
- **Medical Records Inspection (`staff/view_medical_records.php`)**: Read-only clinical lookup across all registered patients, assigned doctors, diagnoses, and treatment plans.
- **Operational Workflow Access**: Staff access to appointment scheduling/status management (`admin/manage_appointments.php`) and billing ledger management (`admin/manage_bill_payments.php`).

### 4. Administrator Control Center (`admin/`)
- **Executive Dashboard (`admin/portal.php`)**:
  - High-level system metrics (Total users, active doctors, scheduled appointments, pending invoices).
  - Navigation hub for hospital operations management.
- **User Account Management (`admin/manage_users.php`)**: Add, edit, assign roles (*admin*, *staff*, *patient*), reset passwords, and delete system users.
- **Doctor Management (`admin/manage_doctors.php`)**: Onboard physicians, define specialties, assign hospital branch locations, manage contact numbers, and upload profile photos.
- **Services Catalog Administration (`admin/manage_services.php`)**: Configure medical offerings across clinical categories (*Treatment*, *Laboratory*, *Emergency*).
- **Appointment Administration (`admin/manage_appointments.php`)**: Create, update, reschedule, confirm, or remove patient appointments.
- **Medical Records Management (`admin/manage_medical_records.php`)**: Add, modify, or delete official medical records, diagnostic observations, and prescription regimens.
- **Billing & Financial Records (`admin/manage_bill_payments.php`)**: Record, adjust, and reconcile billing statements, amounts in LKR, payment methods, and statuses.
- **Feedback Moderation (`admin/manage_feedback.php`)**: Searchable, paginated feedback review table with moderation and deletion capabilities.

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
                    - User Management       - Appointments Management                 - Book Appointments
                    - Doctor Profiles       - View Medical Records                    - View Health Records
                    - Service Catalog       - Billing Management                      - Invoice Statements
                    - Records & Billing                                               - Submit Feedback
```

---

## 💻 Technology Stack

| Layer | Component | Description |
|---|---|---|
| **Frontend** | HTML5 / CSS3 / JavaScript | Custom responsive CSS grid & flexbox layouts, CSS variables, vanilla ES6 DOM scripting for form validation and UI transitions. |
| **CSS Framework** | Bootstrap 5.3 (Alpha) | Used for select public grid layouts alongside custom stylesheets. |
| **Backend** | PHP 8.1+ | Server-side rendering, Session-based Role-Based Access Control (RBAC), and modular architecture. |
| **Database** | MySQL 8.0+ / MariaDB 10.5+ | Relational schema with foreign key constraints, indexing, and `utf8mb4` character encoding. |
| **Database Driver** | PHP `mysqli` Extension | Object-oriented & procedural prepared statements (`mysqli_prepare`, `mysqli_stmt_bind_param`). |
| **Web Server** | Apache (WampServer / XAMPP) | Local development hosting with multi-port fallback support. |

---

## 🗄️ Database Schema

The underlying database `care_compass_db` consists of **9 core relational tables**:

| Table | Primary Key | Description |
|---|---|---|
| `users` | `id` | System user accounts, hashed credentials (`password_hash`), contact details, and assigned roles (`admin`, `staff`, `patient`). |
| `doctors` | `id` | Physician directory including full name, clinical specialty, hospital branch location, telephone, and optional photo file path. |
| `services` | `id` | Clinical and diagnostic offerings categorized by `Treatment`, `Laboratory`, or `Emergency`. |
| `appointments` | `id` | Consultations linking `patient_id` (`users`), `doctor_id` (`doctors`), and optional `service_id` (`services`) with booking date/time and status (`pending`, `confirmed`, `cancelled`). |
| `medical_records` | `id` | Health records containing clinical diagnosis, prescribed treatment, date recorded, referencing `patient_id`, `doctor_id`, and `service_id`. |
| `bill_payments` | `id` | Financial ledger recording invoice amounts (LKR), payment methods (`cash`, `card`, `online`), statuses (`pending`, `paid`, `overdue`), and timestamps. |
| `feedback` | `id` | Patient satisfaction ratings (1–5 stars) and qualitative review testimonials linked to `user_id`. |
| `contact_queries` | `id` | General visitor inquiries submitted via the public contact form. |
| `service_queries` | `id` | Patient inquiries specific to medical procedures and services. |

---

## 📁 Directory Structure

```
care_compass/
├── admin/                         # Administrator Management Module
│   ├── manage_appointments.php    # Appointment scheduling, review & status updates
│   ├── manage_bill_payments.php   # Billing statements & payment management
│   ├── manage_doctors.php         # Doctor onboarding, specializations & photo uploads
│   ├── manage_feedback.php        # Patient review moderation & feedback management
│   ├── manage_medical_records.php # Electronic medical records CRUD
│   ├── manage_services.php        # Hospital service catalog configuration
│   ├── manage_users.php           # User account & role administration
│   └── portal.php                 # Admin Control Center dashboard & KPI metrics
├── assets/                        # Static Client-Side Assets
│   ├── css/
│   │   ├── admin_styles.css       # Unified design system for internal portals
│   │   └── styles.css             # Public portal responsive stylesheet
│   ├── js/
│   │   └── scripts.js             # Client-side validations, interactions & UI animations
│   └── images/                    # Brand assets, banners, emergency & procedure visuals
│       └── doctors/               # Doctor profile photographs
├── auth/                          # Authentication & Session Module
│   ├── login.php                  # Multi-role authentication endpoint (username/mobile)
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
├── LICENSE                        # Apache 2.0 License
└── README.md                      # Technical documentation & project guide
```

---

## ⚙️ Installation & Setup

### Prerequisites
- **PHP**: Version 8.1 or higher (with `mysqli` and `session` extensions enabled).
- **Database**: MySQL 8.0+ or MariaDB 10.5+.
- **Web Server**: Apache via **WampServer**, **XAMPP**, **LAMP**, or the native PHP Built-in CLI Server.
- **Git**: Installed for repository cloning.

---

### Step 1: Clone the Repository
Clone the project into your local web server's document root:

```bash
# For WampServer
cd C:\wamp64\www\
git clone https://github.com/RashLakshan/Care-Compass-Hospitals.git care_compass

# For XAMPP
cd C:\xampp\htdocs\
git clone https://github.com/RashLakshan/Care-Compass-Hospitals.git care_compass
```

---

### Step 2: Database Setup

1. Start your **MySQL / MariaDB** server.
2. Open your preferred database management tool (**phpMyAdmin**, MySQL Workbench, or CLI).
3. Create the database:
   ```sql
   CREATE DATABASE care_compass_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
   ```
4. Import the pre-configured database schema and sample records from [`database/care_compass_db.sql`](database/care_compass_db.sql):
   - **Via CLI**:
     ```bash
     mysql -u root -p care_compass_db < database/care_compass_db.sql
     ```
   - **Via phpMyAdmin**: Select `care_compass_db` > Click **Import** > Choose `database/care_compass_db.sql` > Click **Import**.

---

### Step 3: Database Connection Configuration

Inspect [`includes/db_connect.php`](includes/db_connect.php) to confirm connection credentials:

```php
$host     = '127.0.0.1';
$username = 'root';
$password = '';
$database = 'care_compass_db';
```

> **Automatic Port Detection**: The connection script automatically connects to standard MySQL port `3306` first and transparently falls back to WAMP MariaDB port `3307` if needed.

---

### Step 4: Running the Application

#### Method A: Via WampServer / XAMPP (Recommended)
1. Ensure Apache and MySQL/MariaDB services are active.
2. Launch your web browser and navigate to:
   ```
   http://localhost/care_compass/
   ```

#### Method B: Via PHP Built-in Development Server
If running without WAMP/XAMPP:
1. Open a terminal inside the project directory:
   ```bash
   cd care_compass
   ```
2. Start the built-in server:
   ```bash
   php -S 127.0.0.1:8000
   ```
3. Open your browser and navigate to:
   ```
   http://127.0.0.1:8000
   ```

---

## 🔑 Demo User Credentials

The database script [`database/care_compass_db.sql`](database/care_compass_db.sql) is pre-seeded with sample development accounts for grading and testing purposes:

| Assigned Role | Username | Password | Purpose & Scope |
|---|---|---|---|
| **Administrator** | `admin` | `admin123` | Full access to Admin Portal, User Management, Doctor profiles, Services, System Records, and Feedback. |
| **Hospital Staff** | `hasith` | `staff123` | Operational access to Staff Portal, Appointment scheduling/updates, Medical Records, and Billing ledger. |
| **Patient** | `rash` | `123456` | Primary patient testing account with existing appointments, billing history, and medical records. |
| **Patient** | `Pramu` | `123456` | Secondary patient testing account for appointment booking verification. |

> **Security Note**: All demo passwords in the database are stored as cryptographic hashes generated via PHP's native `password_hash()` (Bcrypt). The plain-text passwords listed above are strictly intended for local academic evaluation.

---

## 🔒 Security & Implementation Standards

- **Role-Based Access Control (RBAC)**: Every private endpoint validates both `$_SESSION['user_id']` and the specific permitted `$_SESSION['role']` at the script head, redirecting unauthorized users to prevent privilege escalation.
- **SQL Injection Prevention**: Database operations utilize parameterized **Prepared Statements** (`mysqli_prepare`, `mysqli_stmt_bind_param`) across all input channels.
- **Cross-Site Scripting (XSS) Mitigation**: User-supplied values rendered in HTML contexts are sanitized via `htmlspecialchars()` with proper escaping.
- **Password Security**: Passwords are saved as one-way Bcrypt hashes using PHP's native `password_hash()` and verified via `password_verify()`.
- **Upload Whitelisting**: Physician photo uploads are strictly verified against an allowed extension whitelist (`jpg`, `jpeg`, `png`, `gif`, `webp`) before being written to disk.
- **Defensive Database Bootstrapping**: Key administrative management scripts include schema verification logic that safely creates missing core tables if not yet initialized.

---

## 🛠️ Limitations & Future Roadmap

As an academic project focused on core web engineering concepts, certain enterprise features were intentionally scoped out:

- **Payment Gateway Integration**: Billing statements currently record payment methods (*cash*, *card*, *online*) and track payment statuses (*paid*, *pending*, *overdue*) in the database. Future enhancements would integrate external payment processors (e.g., Stripe, PayHere).
- **Automated Messaging & Notifications**: Patient appointments currently track contact numbers for administrative follow-up. Integration with an SMS gateway (e.g., Twilio) or automated transactional email notifications (e.g., PHPMailer / SendGrid) represents an ideal next step.
- **Automated Testing Suite**: Current verification relies on manual functional testing and PHP syntax linting. A future milestone is introducing unit and integration testing via **PHPUnit**.
- **REST API & Architectural Evolution**: Future versions could separate the presentation layer by exposing RESTful API endpoints for prospective mobile or modern single-page application (SPA) frontends.

---

## 📌 Project Information

- **Project Title**: Care Compass Hospital Management System
- **Project Type**: University Academic Assignment / Coursework Project
- **Module / Domain**: Web Application Development & Software Engineering
- **Author / Developer**: Rashintha Lakshan Tharanga
- **Academic Year**: 2024 – 2025
- **Version**: 2.0.0 (Modular Refactor)
- **Repository**: [https://github.com/RashLakshan/Care-Compass-Hospitals](https://github.com/RashLakshan/Care-Compass-Hospitals)

---

## 📄 License

This project is open-source software licensed under the **[Apache License 2.0](LICENSE)**.
