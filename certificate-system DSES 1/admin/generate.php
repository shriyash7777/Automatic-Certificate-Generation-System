<?php
require_once '../config/database.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$certificateId = $_GET['id'];

try {
    $pdo = getConnection();
    $stmt = $pdo->prepare("SELECT * FROM certificates WHERE id = ?");
    $stmt->execute([$certificateId]);
    $certificate = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$certificate) {
        header("Location: index.php");
        exit();
    }
    
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

$qrCodeData = $certificateId . ': ' . $certificate['student_name'] . ' - ' . $certificate['course_name'];

// Generate verification URL
$verificationUrl = BASE_URL . 'verify/?id=' . $certificateId;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate Generated - DSES</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Times New Roman', serif;
            background: #f5f5f5;
            padding: 20px;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        
        .controls {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .btn {
            background: #667eea;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            margin: 0 10px;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn:hover {
            background: #5a67d8;
        }
        
        .certificate-container {
            width: 900px;
            height: 650px;
            background: white;
            margin: 0 auto;
            position: relative;
            border: 8px solid #666;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        /* Added geometric corner decorations matching DSES template */
        .corner-decoration {
            position: absolute;
            width: 0;
            height: 0;
        }
        
        .corner-top-left {
            top: 0;
            left: 0;
            border-left: 80px solid #4CAF50;
            border-bottom: 80px solid transparent;
        }
        
        .corner-top-right {
            top: 0;
            right: 0;
            border-right: 80px solid #FF9800;
            border-bottom: 80px solid transparent;
        }
        
        .corner-bottom-left {
            bottom: 0;
            left: 0;
            border-left: 80px solid #2196F3;
            border-top: 80px solid transparent;
        }
        
        .corner-bottom-right {
            bottom: 0;
            right: 0;
            border-right: 80px solid #4CAF50;
            border-top: 80px solid transparent;
        }
        
        .certificate-content {
            padding: 40px 60px;
            height: 100%;
            position: relative;
            z-index: 2;
        }
        
        .company-header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 20px;
        }
        
        .company-name {
            font-size: 22px;
            font-weight: bold;
            color: #8B4513;
            margin-bottom: 8px;
            letter-spacing: 1px;
        }
        
        .company-subtitle {
            font-size: 16px;
            color: #4CAF50;
            font-style: italic;
            margin-bottom: 5px;
        }
        
        .company-id {
            font-size: 12px;
            color: #666;
            margin-bottom: 15px;
        }
        
        .contact-info {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: #555;
            flex-wrap: wrap;
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            margin: 2px 5px;
        }
        
        .certificate-title {
            text-align: center;
            font-size: 42px;
            font-weight: bold;
            color: #333;
            margin: 35px 0 25px 0;
            letter-spacing: 2px;
        }
        
        .certificate-text {
            text-align: center;
            font-size: 16px;
            line-height: 1.8;
            color: #333;
            margin: 15px 0;
        }
        
        .student-name {
            font-size: 32px;
            font-weight: bold;
            font-style: italic;
            color: #2C3E50;
            margin: 25px 0;
            text-decoration: underline;
        }
        
        .course-name {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin: 20px 0;
        }
        
        .bottom-section {
            position: absolute;
            bottom: 40px;
            left: 60px;
            right: 60px;
            display: flex;
            justify-content: space-between;
            align-items: end;
        }
        
        .qr-section {
            text-align: center;
        }
        
        .qr-code {
            width: 80px;
            height: 80px;
            border: 2px solid #333;
            margin-bottom: 8px;
            background: url('https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=<?php echo urlencode($qrCodeData); ?>') no-repeat center;
            background-size: contain;
        }
        
        .qr-text {
            font-size: 10px;
            color: #666;
        }
        
        .signature-section {
            text-align: center;
        }
        
        .signature-line {
            width: 180px;
            border-bottom: 2px solid #333;
            margin-bottom: 8px;
        }
        
        .signature-name {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }
        
        .signature-title {
            font-size: 14px;
            color: #666;
        }
        
        .motto {
            position: absolute;
            bottom: 15px;
            left: 60px;
            font-size: 11px;
            color: #666;
            font-style: italic;
        }
        
        .certificate-id {
            position: absolute;
            bottom: 15px;
            right: 60px;
            font-size: 10px;
            color: #666;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .controls {
                display: none;
            }
            .certificate-container {
                box-shadow: none;
                border: 2px solid #333;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="controls">
            <h2>Certificate Generated Successfully!</h2>
            <p style="margin: 10px 0;">Certificate ID: <strong><?php echo $certificateId; ?></strong></p>
            <button onclick="window.print()" class="btn">🖨️ Print Certificate</button>
            <a href="index.php" class="btn">← Generate Another</a>
            <a href="../verify/?id=<?php echo $certificateId; ?>" class="btn">🔍 Verify Certificate</a>
        </div>

        <div class="certificate-container">
            <!-- Added corner decorations for DSES design -->
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
                        <div class="contact-item">📧 dses.contact@gmail.com</div>
                        <div class="contact-item">🌐 www.dnyandases.com</div>
                        <div class="contact-item">📞 +91 7028975161</div>
                        <div class="contact-item">📍 Ravet, Pune (MS) India - 412101</div>
                    </div>
                </div>
                
                <div class="certificate-title">CERTIFICATE OF MERIT</div>
                
                <div class="certificate-text">This is to certify that</div>
                
                <div class="certificate-text student-name">
                    <?php echo htmlspecialchars($certificate['student_name']); ?>
                </div>
                
                <div class="certificate-text">
                    has successfully completed <strong><?php echo htmlspecialchars($certificate['course_hours']); ?> hours</strong> course of
                </div>
                
                <div class="certificate-text course-name">
                    <?php echo htmlspecialchars($certificate['course_name']); ?>
                </div>
                
                <div class="certificate-text">
                    From <strong><?php echo date('d M', strtotime($certificate['start_date'])); ?></strong> 
                    to <strong><?php echo date('d M Y', strtotime($certificate['end_date'])); ?></strong>
                </div>
                
                <div class="bottom-section">
                    <div class="qr-section">
                        <div class="qr-code"></div>
                        <div class="qr-text">Certificate ID</div>
                    </div>
                    
                    <div class="signature-section">
                        <div class="signature-line"></div>
                        <div class="signature-name"><?php echo htmlspecialchars($certificate['instructor_name']); ?></div>
                        <div class="signature-title"><?php echo htmlspecialchars($certificate['instructor_title']); ?></div>
                    </div>
                </div>
                
                <div class="motto">Vasudha Dnyanam Eshwaram</div>
                <div class="certificate-id">Certificate ID: <?php echo $certificateId; ?></div>
            </div>
        </div>
    </div>
</body>
</html>
