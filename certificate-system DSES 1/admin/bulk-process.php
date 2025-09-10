<?php
require_once '../config/database.php';

$message = '';
$messageType = '';
$processedStudents = [];
$errors = [];

if ($_POST && isset($_FILES['bulk_file'])) {
    $courseName = trim($_POST['bulk_course_name']);
    $courseHours = trim($_POST['bulk_course_hours']);
    $startDate = $_POST['bulk_start_date'];
    $endDate = $_POST['bulk_end_date'];
    $instructorName = trim($_POST['bulk_instructor_name']);
    $instructorTitle = trim($_POST['bulk_instructor_title']);
    
    if (empty($courseName) || empty($courseHours) || empty($startDate) || empty($endDate) || empty($instructorName) || empty($instructorTitle)) {
        $message = 'All fields are required.';
        $messageType = 'error';
    } else {
        $uploadedFile = $_FILES['bulk_file'];
        
        if ($uploadedFile['error'] !== UPLOAD_ERR_OK) {
            $message = 'File upload failed.';
            $messageType = 'error';
        } else {
            $fileName = $uploadedFile['name'];
            $fileTmpPath = $uploadedFile['tmp_name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            
            $studentNames = [];
            
            // Parse different file formats
            try {
                switch ($fileExtension) {
                    case 'csv':
                        if (($handle = fopen($fileTmpPath, 'r')) !== FALSE) {
                            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                                if (!empty(trim($data[0]))) {
                                    $studentNames[] = trim($data[0]);
                                }
                            }
                            fclose($handle);
                        }
                        break;
                        
                    case 'txt':
                        $content = file_get_contents($fileTmpPath);
                        $lines = explode("\n", $content);
                        foreach ($lines as $line) {
                            $line = trim($line);
                            if (!empty($line)) {
                                $studentNames[] = $line;
                            }
                        }
                        break;
                        
                    case 'docx':
                        // Simple DOCX parsing (requires zip extension)
                        if (class_exists('ZipArchive')) {
                            $zip = new ZipArchive();
                            if ($zip->open($fileTmpPath) === TRUE) {
                                $content = $zip->getFromName('word/document.xml');
                                $zip->close();
                                
                                // Extract text from XML
                                $content = preg_replace('/<[^>]+>/', ' ', $content);
                                $content = html_entity_decode($content);
                                $lines = explode("\n", $content);
                                
                                foreach ($lines as $line) {
                                    $line = trim($line);
                                    if (!empty($line) && strlen($line) > 2) {
                                        $studentNames[] = $line;
                                    }
                                }
                            } else {
                                throw new Exception('Could not open DOCX file');
                            }
                        } else {
                            throw new Exception('DOCX support requires ZIP extension');
                        }
                        break;
                        
                    default:
                        throw new Exception('Unsupported file format');
                }
                
                if (empty($studentNames)) {
                    throw new Exception('No student names found in the file');
                }
                
                // Process students and create certificates
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
                
                foreach ($studentNames as $studentName) {
                    try {
                        // Generate unique certificate ID
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
                        
                        $processedStudents[] = [
                            'name' => $studentName,
                            'id' => $certificateId,
                            'status' => 'success'
                        ];
                        
                    } catch (Exception $e) {
                        $errors[] = "Error processing {$studentName}: " . $e->getMessage();
                        $processedStudents[] = [
                            'name' => $studentName,
                            'id' => null,
                            'status' => 'error',
                            'error' => $e->getMessage()
                        ];
                    }
                }
                
                $message = count($processedStudents) . ' students processed successfully!';
                $messageType = 'success';
                
            } catch (Exception $e) {
                $message = 'Error processing file: ' . $e->getMessage();
                $messageType = 'error';
            }
        }
    }
} else {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Processing Results - DSES</title>
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
            max-width: 1000px;
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
        
        .results-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
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
        
        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .results-table th,
        .results-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .results-table th {
            background: #f8f9fa;
            font-weight: 600;
        }
        
        .status-success {
            color: #28a745;
            font-weight: 600;
        }
        
        .status-error {
            color: #dc3545;
            font-weight: 600;
        }
        
        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 10px 5px;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: #6c757d;
        }
        
        .actions {
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>Bulk Processing Results</h1>
            <p>DNYANDA SUSTAINABLE ENGINEERING SOLUTIONS</p>
        </header>

        <div class="results-container">
            <?php if ($message): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <strong>Errors encountered:</strong>
                    <ul style="margin-top: 10px;">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($processedStudents)): ?>
                <h3>Processing Results:</h3>
                <table class="results-table">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Certificate ID</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($processedStudents as $student): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($student['name']); ?></td>
                                <td><?php echo $student['id'] ? htmlspecialchars($student['id']) : 'N/A'; ?></td>
                                <td class="status-<?php echo $student['status']; ?>">
                                    <?php echo ucfirst($student['status']); ?>
                                    <?php if (isset($student['error'])): ?>
                                        <br><small><?php echo htmlspecialchars($student['error']); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($student['status'] === 'success'): ?>
                                        <a href="generate.php?id=<?php echo $student['id']; ?>" class="btn" style="padding: 6px 12px; font-size: 14px;">View Certificate</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
            
            <div class="actions">
                <a href="index.php" class="btn">← Back to Admin</a>
                <?php if (!empty($processedStudents)): ?>
                    <a href="generate-bulk-zip.php?batch=<?php echo time(); ?>" class="btn">📦 Download All Certificates</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
