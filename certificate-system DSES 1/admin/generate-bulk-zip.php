<?php
require_once '../config/database.php';

// This is a simplified version - in production you'd want to track batches properly
if (!isset($_GET['batch'])) {
    header("Location: index.php");
    exit();
}

try {
    $pdo = getConnection();
    
    // Get recent certificates (last 100 for demo purposes)
    $stmt = $pdo->prepare("SELECT * FROM certificates ORDER BY created_at DESC LIMIT 100");
    $stmt->execute();
    $certificates = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($certificates)) {
        header("Location: index.php");
        exit();
    }
    
    // Create temporary directory for certificates
    $tempDir = sys_get_temp_dir() . '/certificates_' . time();
    mkdir($tempDir, 0777, true);
    
    // Generate HTML certificates for each student
    foreach ($certificates as $certificate) {
        $qrCodeData = $certificate['id'] . ': ' . $certificate['student_name'] . ' - ' . $certificate['course_name'];
        
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Certificate - ' . htmlspecialchars($certificate['student_name']) . '</title>
    <style>
        body { font-family: "Times New Roman", serif; margin: 0; padding: 20px; }
        .certificate-container { width: 900px; height: 650px; background: white; margin: 0 auto; position: relative; border: 8px solid #666; }
        .corner-decoration { position: absolute; width: 0; height: 0; }
        .corner-top-left { top: 0; left: 0; border-left: 80px solid #4CAF50; border-bottom: 80px solid transparent; }
        .corner-top-right { top: 0; right: 0; border-right: 80px solid #FF9800; border-bottom: 80px solid transparent; }
        .corner-bottom-left { bottom: 0; left: 0; border-left: 80px solid #2196F3; border-top: 80px solid transparent; }
        .corner-bottom-right { bottom: 0; right: 0; border-right: 80px solid #4CAF50; border-top: 80px solid transparent; }
        .certificate-content { padding: 40px 60px; height: 100%; position: relative; z-index: 2; }
        .company-header { text-align: center; margin-bottom: 25px; border-bottom: 2px solid #ddd; padding-bottom: 20px; }
        .company-name { font-size: 22px; font-weight: bold; color: #8B4513; margin-bottom: 8px; letter-spacing: 1px; }
        .company-subtitle { font-size: 16px; color: #4CAF50; font-style: italic; margin-bottom: 5px; }
        .company-id { font-size: 12px; color: #666; margin-bottom: 15px; }
        .contact-info { display: flex; justify-content: space-between; font-size: 11px; color: #555; flex-wrap: wrap; }
        .certificate-title { text-align: center; font-size: 42px; font-weight: bold; color: #333; margin: 35px 0 25px 0; letter-spacing: 2px; }
        .certificate-text { text-align: center; font-size: 16px; line-height: 1.8; color: #333; margin: 15px 0; }
        .student-name { font-size: 32px; font-weight: bold; font-style: italic; color: #2C3E50; margin: 25px 0; text-decoration: underline; }
        .course-name { font-size: 28px; font-weight: bold; color: #333; margin: 20px 0; }
        .bottom-section { position: absolute; bottom: 40px; left: 60px; right: 60px; display: flex; justify-content: space-between; align-items: end; }
        .qr-section { text-align: center; }
        .qr-code { width: 80px; height: 80px; border: 2px solid #333; margin-bottom: 8px; background: url(\'https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=' . urlencode($qrCodeData) . '\') no-repeat center; background-size: contain; }
        .qr-text { font-size: 10px; color: #666; }
        .signature-section { text-align: center; }
        .signature-line { width: 180px; border-bottom: 2px solid #333; margin-bottom: 8px; }
        .signature-name { font-size: 16px; font-weight: bold; color: #333; }
        .signature-title { font-size: 14px; color: #666; }
        .motto { position: absolute; bottom: 15px; left: 60px; font-size: 11px; color: #666; font-style: italic; }
        .certificate-id { position: absolute; bottom: 15px; right: 60px; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="corner-decoration corner-top-left"></div>
        <div class="corner-decoration corner-top-right"></div>
        <div class="corner-decoration corner-bottom-left"></div>
        <div class="corner-decoration corner-bottom-right"></div>
        
        <div class="certificate-content">
            <div class="company-header">
                <div class="company-name">DNYANDA SUSTAINABLE ENGINEERING SOLUTIONS PRIVATE LIMITED</div>
                <div class="company-subtitle">A Sustainable Development Venture</div>
                <div class="company-id">UDYAM-MH-26-0420933</div>
                
                <div class="contact-info">
                    <div>📧 dses.contact@gmail.com</div>
                    <div>🌐 www.dnyandases.com</div>
                    <div>📞 +91 7028975161</div>
                    <div>📍 Ravet, Pune (MS) India - 412101</div>
                </div>
            </div>
            
            <div class="certificate-title">CERTIFICATE OF MERIT</div>
            
            <div class="certificate-text">This is to certify that</div>
            
            <div class="certificate-text student-name">' . htmlspecialchars($certificate['student_name']) . '</div>
            
            <div class="certificate-text">has successfully completed <strong>' . htmlspecialchars($certificate['course_hours']) . ' hours</strong> course of</div>
            
            <div class="certificate-text course-name">' . htmlspecialchars($certificate['course_name']) . '</div>
            
            <div class="certificate-text">From <strong>' . date('d M', strtotime($certificate['start_date'])) . '</strong> to <strong>' . date('d M Y', strtotime($certificate['end_date'])) . '</strong></div>
            
            <div class="bottom-section">
                <div class="qr-section">
                    <div class="qr-code"></div>
                    <div class="qr-text">Certificate ID</div>
                </div>
                
                <div class="signature-section">
                    <div class="signature-line"></div>
                    <div class="signature-name">' . htmlspecialchars($certificate['instructor_name']) . '</div>
                    <div class="signature-title">' . htmlspecialchars($certificate['instructor_title']) . '</div>
                </div>
            </div>
            
            <div class="motto">Vasudha Dnyanam Eshwaram</div>
            <div class="certificate-id">Certificate ID: ' . $certificate['id'] . '</div>
        </div>
    </div>
</body>
</html>';
        
        $fileName = $tempDir . '/' . $certificate['id'] . '_' . preg_replace('/[^a-zA-Z0-9]/', '_', $certificate['student_name']) . '.html';
        file_put_contents($fileName, $html);
    }
    
    // Create ZIP file
    $zipFileName = 'certificates_bulk_' . date('Y-m-d_H-i-s') . '.zip';
    $zipPath = $tempDir . '/' . $zipFileName;
    
    $zip = new ZipArchive();
    if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
        $files = glob($tempDir . '/*.html');
        foreach ($files as $file) {
            $zip->addFile($file, basename($file));
        }
        $zip->close();
        
        // Download the ZIP file
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
        header('Content-Length: ' . filesize($zipPath));
        readfile($zipPath);
        
        // Clean up temporary files
        array_map('unlink', glob($tempDir . '/*'));
        rmdir($tempDir);
        
    } else {
        echo 'Error creating ZIP file';
    }
    
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
