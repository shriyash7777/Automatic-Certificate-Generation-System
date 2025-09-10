<?php
// Example: How to integrate the DSES design into your PHP system
// This shows the HTML/CSS structure you can use in your generate.php

function generateDSESCertificate($studentData) {
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            .certificate-container {
                width: 800px;
                height: 600px;
                margin: 0 auto;
                background: white;
                position: relative;
                font-family: Arial, sans-serif;
                border: 8px solid #666;
            }
            
            /* Geometric border decorations */
            .corner-decoration {
                position: absolute;
                width: 120px;
                height: 120px;
            }
            
            .corner-top-left {
                top: 0;
                left: 0;
                background: linear-gradient(135deg, #4CAF50 0%, #FFA726 50%, #2196F3 100%);
                clip-path: polygon(0 0, 100% 0, 0 100%);
            }
            
            .corner-top-right {
                top: 0;
                right: 0;
                background: linear-gradient(225deg, #4CAF50 0%, #FFA726 50%, #2196F3 100%);
                clip-path: polygon(100% 0, 100% 100%, 0 0);
            }
            
            .corner-bottom-left {
                bottom: 0;
                left: 0;
                background: linear-gradient(45deg, #4CAF50 0%, #FFA726 50%, #2196F3 100%);
                clip-path: polygon(0 0, 100% 100%, 0 100%);
            }
            
            .corner-bottom-right {
                bottom: 0;
                right: 0;
                background: linear-gradient(315deg, #4CAF50 0%, #FFA726 50%, #2196F3 100%);
                clip-path: polygon(100% 0, 100% 100%, 0 100%);
            }
            
            .company-header {
                text-align: center;
                padding: 40px 60px 20px;
            }
            
            .company-name {
                font-size: 18px;
                font-weight: bold;
                color: #8B4513;
                margin-bottom: 8px;
            }
            
            .company-subtitle {
                font-size: 14px;
                color: #4CAF50;
                margin-bottom: 5px;
            }
            
            .certificate-title {
                text-align: center;
                font-size: 32px;
                font-weight: bold;
                color: #333;
                margin: 30px 0;
            }
            
            .student-name {
                text-align: center;
                font-size: 24px;
                font-weight: bold;
                font-style: italic;
                color: #2C3E50;
                margin: 20px 0;
            }
            
            .qr-code {
                position: absolute;
                bottom: 60px;
                left: 60px;
                width: 80px;
                height: 80px;
            }
            
            .signature-area {
                position: absolute;
                bottom: 60px;
                right: 60px;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class="certificate-container">
            <!-- Corner decorations -->
            <div class="corner-decoration corner-top-left"></div>
            <div class="corner-decoration corner-top-right"></div>
            <div class="corner-decoration corner-bottom-left"></div>
            <div class="corner-decoration corner-bottom-right"></div>
            
            <!-- Company header -->
            <div class="company-header">
                <div class="company-name">DNYANDA SUSTAINABLE ENGINEERING SOLUTIONS PRIVATE LIMITED</div>
                <div class="company-subtitle">A Sustainable Development Venture</div>
                <div style="font-size: 10px; color: #666;">UDYAM-MH-26-0420933</div>
            </div>
            
            <!-- Certificate content -->
            <div class="certificate-title">CERTIFICATE OF MERIT</div>
            
            <div style="text-align: center; margin: 20px 0;">
                <div>This is to certify that</div>
                <div class="student-name">' . htmlspecialchars($studentData['name']) . '</div>
                <div>has successfully completed ' . htmlspecialchars($studentData['hours']) . ' hours course of</div>
                <div style="font-size: 20px; font-weight: bold; margin: 15px 0;">' . htmlspecialchars($studentData['course']) . '</div>
                <div>From ' . date('d M', strtotime($studentData['start_date'])) . ' to ' . date('d M Y', strtotime($studentData['end_date'])) . '</div>
            </div>
            
            <!-- QR Code -->
            <div class="qr-code">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=' . urlencode($studentData['verification_url']) . '" alt="QR Code">
            </div>
            
            <!-- Signature -->
            <div class="signature-area">
                <div style="border-bottom: 1px solid #333; width: 150px; margin-bottom: 5px;"></div>
                <div style="font-weight: bold;">Dr. Vikas Shinde</div>
                <div style="font-size: 12px;">Director</div>
            </div>
        </div>
    </body>
    </html>';
    
    return $html;
}

// Usage in your generate.php:
// $certificateHtml = generateDSESCertificate($certificateData);
// echo $certificateHtml;
?>
