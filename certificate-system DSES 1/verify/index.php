<?php
require_once '../config/database.php';

$certificate = null;
$error = '';

if (isset($_GET['id'])) {
    $certificateId = trim($_GET['id']);
    
    try {
        $pdo = getConnection();
        $stmt = $pdo->prepare("SELECT * FROM certificates WHERE id = ?");
        $stmt->execute([$certificateId]);
        $certificate = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$certificate) {
            $error = 'Certificate not found. Please check the certificate ID.';
        }
    } catch (Exception $e) {
        $error = 'Database error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate Verification - DSES</title>
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
        
        .verification-form {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 16px;
        }
        
        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        
        .certificate-details {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .status-valid {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
        
        .status-invalid {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        
        .detail-label {
            font-weight: 600;
            color: #333;
        }
        
        .detail-value {
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Certificate Verification</h1>
            <p>DNYANDA SUSTAINABLE ENGINEERING SOLUTIONS</p>
        </div>

        <div class="verification-form">
            <h2 style="margin-bottom: 20px;">Verify Certificate</h2>
            <form method="GET">
                <div class="form-group">
                    <label for="id">Certificate ID</label>
                    <input type="text" id="id" name="id" placeholder="Enter certificate ID (e.g., CERT-2025-001234)" 
                           value="<?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : ''; ?>" required>
                </div>
                <button type="submit" class="btn">Verify Certificate</button>
                <a href="../admin/" class="btn" style="margin-left: 10px;">← Back to Admin</a>
            </form>
        </div>

        <?php if (isset($_GET['id'])): ?>
            <div class="certificate-details">
                <?php if ($certificate): ?>
                    <div class="status-valid">
                        <strong>✅ Certificate Valid</strong> - This certificate is authentic and verified.
                    </div>
                    
                    <h3 style="margin-bottom: 20px;">Certificate Details</h3>
                    
                    <div class="detail-row">
                        <span class="detail-label">Certificate ID:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($certificate['id']); ?></span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Student Name:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($certificate['student_name']); ?></span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Course:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($certificate['course_name']); ?></span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Duration:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($certificate['course_hours']); ?> hours</span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Period:</span>
                        <span class="detail-value">
                            <?php echo date('d M Y', strtotime($certificate['start_date'])); ?> to 
                            <?php echo date('d M Y', strtotime($certificate['end_date'])); ?>
                        </span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Instructor:</span>
                        <span class="detail-value">
                            <?php echo htmlspecialchars($certificate['instructor_name']); ?>, 
                            <?php echo htmlspecialchars($certificate['instructor_title']); ?>
                        </span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Issue Date:</span>
                        <span class="detail-value"><?php echo date('d M Y', strtotime($certificate['created_at'])); ?></span>
                    </div>
                    
                <?php else: ?>
                    <div class="status-invalid">
                        <strong>❌ Certificate Invalid</strong> - <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
