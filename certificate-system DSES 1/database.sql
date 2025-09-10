-- Create database
CREATE DATABASE IF NOT EXISTS certificate_system;
USE certificate_system;

-- Create certificates table
CREATE TABLE IF NOT EXISTS certificates (
    id VARCHAR(50) PRIMARY KEY,
    student_name VARCHAR(255) NOT NULL,
    course_name VARCHAR(255) NOT NULL,
    course_hours VARCHAR(10) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    instructor_name VARCHAR(255) NOT NULL,
    instructor_title VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    pdf_path VARCHAR(500),
    qr_code_path VARCHAR(500)
);

-- Insert sample data
INSERT INTO certificates (
    id, student_name, course_name, course_hours, 
    start_date, end_date, instructor_name, instructor_title
) VALUES 
(
    'CERT-2025-001',
    'John Doe',
    'Robotics',
    '42',
    '2025-02-03',
    '2025-02-08',
    'Dr. Vikas Shinde',
    'Director'
);
