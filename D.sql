
CREATE DATABASE IF NOT EXISTS hospital_roster;
USE hospital_roster;


CREATE TABLE IF NOT EXISTS departments (
    department_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);


CREATE TABLE IF NOT EXISTS staff (
    staff_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    role ENUM('Doctor', 'Nurse', 'Technician', 'Admin') NOT NULL,
    department_id INT,
    password VARCHAR(255) NOT NULL,
    status ENUM('Active','Inactive') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (department_id) REFERENCES departments(department_id) ON DELETE SET NULL ON UPDATE CASCADE
);


CREATE TABLE IF NOT EXISTS duty_roster (
    roster_id INT AUTO_INCREMENT PRIMARY KEY,
    staff_id INT NOT NULL,
    department_id INT NOT NULL,
    duty_date DATE NOT NULL,
    shift ENUM('Morning','Afternoon','Night') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (staff_id) REFERENCES staff(staff_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (department_id) REFERENCES departments(department_id) ON DELETE CASCADE ON UPDATE CASCADE
);


INSERT INTO departments (name) VALUES 
('Emergency'), 
('Surgery'), 
('Pediatrics'), 
('Radiology'), 
('Cardiology');


INSERT INTO staff (full_name, email, role, department_id, password, status)
VALUES (
    'Admin User', 
    'admin@hospital.com', 
    'Admin', 
    NULL, 
    '$2y$10$E8o1h4yHf.Zmkn8E5pZhiunVns7JoZ9T7PrGnZ0F7S9MSyVfU1l9q', 
    'Active'
);

CREATE TABLE roster_comments (
    comment_id INT AUTO_INCREMENT PRIMARY KEY,
    roster_id INT NOT NULL,
    user_id INT NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE leave_requests (
    request_id INT AUTO_INCREMENT PRIMARY KEY,
    roster_id INT NOT NULL,
    user_id INT NOT NULL,
    reason TEXT NOT NULL,
    status ENUM('Pending','Approved','Rejected') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
