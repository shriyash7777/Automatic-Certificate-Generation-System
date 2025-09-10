📜 Certificate Management System with QR Code

A web-based system to generate, manage, and verify certificates digitally. 
🚀 Features

Auto-generate certificates with unique QR codes

Online verification system for authenticity checks

Secure database using MySQL

Bulk student upload via CSV/Excel

Customizable certificate templates

Admin dashboard for student/course management

Automatic PDF certificate generation with unique IDs

🛠️ Tech Stack

Frontend: HTML, CSS, JavaScript

Backend: PHP

Database: MySQL

Other: QR Code Generator (PHP library)

📂 Project Structure
certificate-system/
├── config/          # Database configuration
├── uploads/         # Uploaded CSV files
├── certificates/    # Generated certificates (PDFs)
├── templates/       # Certificate templates
├── index.php        # Main entry point
├── upload.php       # Upload student data
├── generate.php     # Generate certificates
└── verify.php       # Verify certificates via QR

⚙️ Setup Instructions

Clone this repository:

git clone https://github.com/yourusername/certificate-system.git
cd certificate-system


Import the database:

Create a MySQL database (e.g., certificate_system)

Import the SQL file provided in /config/

Update database credentials in config/db.php

Run the project on a local server (XAMPP, WAMP, or LAMP)

Access the system in your browser:

http://localhost/certificate-system

🔑 How It Works

Admin uploads student details (CSV/Excel).

System generates certificates automatically with QR codes.

Certificates are stored as PDFs and can be downloaded.

Anyone can scan the QR code to verify certificate authenticity online.

📸 Screenshots

(Add screenshots of dashboard, generated certificate, QR verification page)

📌 Future Enhancements

Email certificates directly to students

Add role-based access (Admin, Faculty, Student)

Cloud deployment (AWS/Heroku)

👨‍💻 Author

Shriyash Tambe

💼 Intern at (Dnyanda Sustainable Engineering Solutions Private Limited)

🌐 LinkedIn (https://www.linkedin.com/in/shriyash-tambe-36b0a3259/)

📧 shriyashtambe7777@gmail.com
