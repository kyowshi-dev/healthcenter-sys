# Health Center Information System

A web-based information management system for the Barangay Health Center of Sta. Ana, Tagoloan, built using the XAMPP stack (Cross-Platform, Apache, MySQL, PHP).

## Features

- Patient registration and management
- Consultation records
- Maternal health tracking (prenatal and postpartum)
- Immunization management
- Report generation
- Role-based access control

## System Requirements

- PHP 8.0 or higher
- MySQL 8.0 or higher
- Apache 2.4 or higher
- Windows, MacOS, Linux Operating System

## Installation

1. Clone or download the project files to your web server directory
2. Create a MySQL database named `healthcenter_db`
3. Import the database schema (see database_schema.sql)
4. Update database credentials in `src/config/config.php`
5. Ensure the web server has write permissions to necessary directories
6. Access the application through your web browser

## Database Setup

Run the following SQL commands to create the necessary tables:

```sql
-- Users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('System Administrator', 'Health Worker', 'Medical Doctor', 'Viewer/Auditor', 'Barangay Health Worker') NOT NULL,
    status ENUM('Active', 'Inactive') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Patients table
CREATE TABLE patients (
    id INT PRIMARY KEY AUTO_INCREMENT,
    enrollment_id VARCHAR(20) UNIQUE NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    date_of_birth DATE NOT NULL,
    gender ENUM('Male', 'Female') NOT NULL,
    address TEXT,
    phone VARCHAR(20),
    emergency_contact VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Consultations table
CREATE TABLE consultations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    patient_id INT NOT NULL,
    consultation_date DATE NOT NULL,
    type ENUM('General', 'Follow-up', 'Emergency', 'Prenatal') NOT NULL,
    chief_complaint TEXT,
    diagnosis TEXT,
    treatment TEXT,
    blood_pressure VARCHAR(20),
    temperature DECIMAL(4,2),
    weight DECIMAL(5,2),
    height DECIMAL(5,2),
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Immunizations table
CREATE TABLE immunizations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    patient_id INT NOT NULL,
    vaccine VARCHAR(100) NOT NULL,
    dose_number INT NOT NULL,
    admin_date DATE NOT NULL,
    lot_number VARCHAR(50),
    site VARCHAR(50),
    adverse_events TEXT,
    administered_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id),
    FOREIGN KEY (administered_by) REFERENCES users(id)
);

-- Prenatal records table
CREATE TABLE prenatal_records (
    id INT PRIMARY KEY AUTO_INCREMENT,
    patient_id INT NOT NULL,
    consultation_id INT NOT NULL,
    lmp DATE,
    edd DATE,
    gravidity INT,
    parity INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id),
    FOREIGN KEY (consultation_id) REFERENCES consultations(id)
);

-- Postpartum records table
CREATE TABLE postpartum_records (
    id INT PRIMARY KEY AUTO_INCREMENT,
    patient_id INT NOT NULL,
    consultation_id INT NOT NULL,
    delivery_date DATE NOT NULL,
    delivery_type ENUM('Normal', 'Cesarean') DEFAULT 'Normal',
    newborn_info TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id),
    FOREIGN KEY (consultation_id) REFERENCES consultations(id)
);
```

## Default Login

- Username: admin
- Password: admin123
- Role: System Administrator

## Usage

1. Login with your credentials
2. Navigate through the different modules using the top navigation
3. Register patients, record consultations, manage immunizations, etc.
4. Generate reports from the Reports section
5. Manage users and system settings from the Admin section

## Security Features

- Password hashing using bcrypt
- Role-based access control
- Session management
- Input validation and sanitization
- SQL injection prevention

## Support

For technical support or questions, please contact the system administrator.

## License

This project is developed for the Barangay Health Center of Sta. Ana, Tagoloan.
