<?php
require_once '../config/database.php';

$message = '';
$messageType = '';

if ($_POST) {
    if (isset($_POST['student_name'])) {
        $studentName = trim($_POST['student_name']);
        $courseName = trim($_POST['course_name']);
        $courseHours = trim($_POST['course_hours']);
        $startDate = $_POST['start_date'];
        $endDate = $_POST['end_date'];
        $instructorName = trim($_POST['instructor_name']);
        $instructorTitle = trim($_POST['instructor_title']);
        
        if (empty($studentName) || empty($courseName) || empty($courseHours) || empty($startDate) || empty($endDate) || empty($instructorName) || empty($instructorTitle)) {
            $message = 'All fields are required.';
            $messageType = 'error';
        } else {
            try {
                $pdo = getConnection();
                
                // Create table if it doesn't exist
                $createTable = "CREATE TABLE IF NOT EXISTS certificates (
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
                )";
                $pdo->exec($createTable);
                
                $certificateId = 'DSESCC' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
                
                // Check if certificate ID already exists
                $stmt = $pdo->prepare("SELECT id FROM certificates WHERE id = ?");
                $stmt->execute([$certificateId]);
                
                while ($stmt->fetch()) {
                    $certificateId = 'DSESCC' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
                    $stmt->execute([$certificateId]);
                }
                
                // Insert certificate data
                $stmt = $pdo->prepare("INSERT INTO certificates (id, student_name, course_name, course_hours, start_date, end_date, instructor_name, instructor_title) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$certificateId, $studentName, $courseName, $courseHours, $startDate, $endDate, $instructorName, $instructorTitle]);
                
                // Redirect to generate certificate
                header("Location: generate.php?id=" . $certificateId);
                exit();
            } catch (Exception $e) {
                $message = 'Error generating certificate: ' . $e->getMessage();
                $messageType = 'error';
            }
        }
    } elseif (isset($_FILES['bulk_file'])) {
        // Handle bulk upload logic here
        // This is a placeholder for the actual bulk processing logic
        // You would typically move the file to a temporary location and process it
        $message = 'Bulk upload functionality is not yet implemented.';
        $messageType = 'info';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Certificate System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .header {
            background: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .header h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 2.5rem;
        }
        
        .header p {
            color: #666;
            font-size: 1.1rem;
        }
        
        .form-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
            width: 100%;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .nav-links {
            text-align: center;
            margin-top: 20px;
        }
        
        .nav-links a {
            color: #667eea;
            text-decoration: none;
            margin: 0 15px;
            font-weight: 600;
        }
        
        .nav-links a:hover {
            text-decoration: underline;
        }
        
        .tabs {
            display: flex;
            margin-bottom: 0;
            border-bottom: 2px solid #e1e5e9;
        }
        
        .tab {
            padding: 15px 30px;
            background: #f8f9fa;
            border: none;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            color: #666;
            border-radius: 8px 8px 0 0;
            margin-right: 5px;
        }
        
        .tab.active {
            background: white;
            color: #667eea;
            border-bottom: 2px solid white;
            margin-bottom: -2px;
        }
        
        .tab-content {
            display: none;
            padding-top: 30px;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .file-upload-area {
            border: 2px dashed #667eea;
            border-radius: 8px;
            padding: 40px;
            text-align: center;
            margin-bottom: 20px;
            background: #f8f9ff;
        }
        
        .file-upload-area.dragover {
            background: #e6f3ff;
            border-color: #4CAF50;
        }
        
        .file-info {
            background: #e8f4fd;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .file-info h4 {
            color: #2c5aa0;
            margin-bottom: 10px;
        }
        
        .file-info ul {
            margin-left: 20px;
            color: #555;
        }
    </style>
    <script>
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Remove active class from all tabs
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Show selected tab content
            document.getElementById(tabName).classList.add('active');
            
            // Add active class to clicked tab
            event.target.classList.add('active');
        }
        
        function handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) {
                document.getElementById('file-name').textContent = file.name;
                document.getElementById('file-size').textContent = (file.size / 1024).toFixed(2) + ' KB';
                document.getElementById('selected-file').style.display = 'block';
            }
        }
        
        function handleDragOver(event) {
            event.preventDefault();
            event.currentTarget.classList.add('dragover');
        }
        
        function handleDragLeave(event) {
            event.currentTarget.classList.remove('dragover');
        }
        
        function handleDrop(event) {
            event.preventDefault();
            event.currentTarget.classList.remove('dragover');
            
            const files = event.dataTransfer.files;
            if (files.length > 0) {
                document.getElementById('bulk_file').files = files;
                handleFileSelect({target: {files: files}});
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>Certificate Generator</h1>
            <p>DNYANDA SUSTAINABLE ENGINEERING SOLUTIONS</p>
        </header>

        <div class="form-container">
            <!-- Added tabbed interface for single and bulk generation -->
            <div class="tabs">
                <button class="tab active" onclick="showTab('single-tab')">Single Certificate</button>
                <button class="tab" onclick="showTab('bulk-tab')">Bulk Upload</button>
            </div>
            
            <!-- Single Certificate Tab -->
            <div id="single-tab" class="tab-content active">
                <h2 style="margin-bottom: 30px; color: #333; text-align: center;">Generate Single Certificate</h2>
                
                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $messageType; ?>">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-group">
                        <label for="student_name">Student Name *</label>
                        <input type="text" id="student_name" name="student_name" required 
                               value="<?php echo isset($_POST['student_name']) ? htmlspecialchars($_POST['student_name']) : ''; ?>">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="course_name">Course Name *</label>
                            <input type="text" id="course_name" name="course_name" required 
                                   value="<?php echo isset($_POST['course_name']) ? htmlspecialchars($_POST['course_name']) : 'Robotics'; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="course_hours">Course Hours *</label>
                            <input type="text" id="course_hours" name="course_hours" required 
                                   value="<?php echo isset($_POST['course_hours']) ? htmlspecialchars($_POST['course_hours']) : '42'; ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="start_date">Start Date *</label>
                            <input type="date" id="start_date" name="start_date" required 
                                   value="<?php echo isset($_POST['start_date']) ? $_POST['start_date'] : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="end_date">End Date *</label>
                            <input type="date" id="end_date" name="end_date" required 
                                   value="<?php echo isset($_POST['end_date']) ? $_POST['end_date'] : ''; ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="instructor_name">Instructor Name *</label>
                            <input type="text" id="instructor_name" name="instructor_name" required 
                                   value="<?php echo isset($_POST['instructor_name']) ? htmlspecialchars($_POST['instructor_name']) : 'Dr. Vikas Shinde'; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="instructor_title">Instructor Title *</label>
                            <input type="text" id="instructor_title" name="instructor_title" required 
                                   value="<?php echo isset($_POST['instructor_title']) ? htmlspecialchars($_POST['instructor_title']) : 'Director'; ?>">
                        </div>
                    </div>

                    <button type="submit" class="btn">Generate Certificate</button>
                </form>
            </div>
            
            <!-- Added bulk upload tab content -->
            <div id="bulk-tab" class="tab-content">
                <h2 style="margin-bottom: 30px; color: #333; text-align: center;">Bulk Certificate Generation</h2>
                
                <div class="file-info">
                    <h4>Supported File Formats:</h4>
                    <ul>
                        <li><strong>CSV:</strong> First column should contain student names</li>
                        <li><strong>TXT:</strong> Each line should contain one student name</li>
                        <li><strong>DOCX:</strong> Each paragraph/line should contain one student name</li>
                    </ul>
                </div>
                
                <form method="POST" action="bulk-process.php" enctype="multipart/form-data">
                    <div class="file-upload-area" ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)" ondrop="handleDrop(event)">
                        <h3>📁 Upload Student List</h3>
                        <p>Drag and drop your file here, or click to browse</p>
                        <input type="file" id="bulk_file" name="bulk_file" accept=".csv,.txt,.docx" onchange="handleFileSelect(event)" style="margin-top: 15px;">
                    </div>
                    
                    <div id="selected-file" style="display: none; background: #e8f5e8; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                        <strong>Selected File:</strong> <span id="file-name"></span><br>
                        <strong>Size:</strong> <span id="file-size"></span>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="bulk_course_name">Course Name *</label>
                            <input type="text" id="bulk_course_name" name="bulk_course_name" required value="Robotics">
                        </div>
                        
                        <div class="form-group">
                            <label for="bulk_course_hours">Course Hours *</label>
                            <input type="text" id="bulk_course_hours" name="bulk_course_hours" required value="42">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="bulk_start_date">Start Date *</label>
                            <input type="date" id="bulk_start_date" name="bulk_start_date" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="bulk_end_date">End Date *</label>
                            <input type="date" id="bulk_end_date" name="bulk_end_date" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="bulk_instructor_name">Instructor Name *</label>
                            <input type="text" id="bulk_instructor_name" name="bulk_instructor_name" required value="Dr. Vikas Shinde">
                        </div>
                        
                        <div class="form-group">
                            <label for="bulk_instructor_title">Instructor Title *</label>
                            <input type="text" id="bulk_instructor_title" name="bulk_instructor_title" required value="Director">
                        </div>
                    </div>

                    <button type="submit" class="btn">📤 Process Bulk Upload</button>
                </form>
            </div>

            <div class="nav-links">
                <a href="../">← Back to Home</a>
                <a href="../verify/">Verify Certificate</a>
            </div>
        </div>
    </div>
</body>
</html>
