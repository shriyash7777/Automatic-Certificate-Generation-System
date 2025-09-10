<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate System - DSES</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            padding: 60px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 600px;
        }
        
        .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #4CAF50 0%, #2196F3 100%);
            border-radius: 50%;
            margin: 0 auto 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: white;
            font-weight: bold;
        }
        
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 2.5rem;
        }
        
        .subtitle {
            color: #4CAF50;
            font-size: 1.2rem;
            margin-bottom: 40px;
            font-style: italic;
        }
        
        .description {
            color: #666;
            margin-bottom: 40px;
            line-height: 1.6;
            font-size: 1.1rem;
        }
        
        .buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 600;
            text-decoration: none;
            transition: transform 0.2s;
            display: inline-block;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #4CAF50 0%, #2196F3 100%);
        }
        
        .features {
            margin-top: 50px;
            text-align: left;
        }
        
        .feature {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            color: #666;
        }
        
        .feature-icon {
            width: 24px;
            height: 24px;
            background: #4CAF50;
            border-radius: 50%;
            margin-right: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">DS</div>
        <h1>Certificate System</h1>
        <p class="subtitle">DNYANDA SUSTAINABLE ENGINEERING SOLUTIONS</p>
        <p class="description">
            Professional automated certificate generation system with QR code verification. 
            Generate, manage, and verify certificates with ease.
        </p>
        
        <div class="buttons">
            <a href="admin/" class="btn">🎓 Generate Certificate</a>
            <a href="verify/" class="btn btn-secondary">🔍 Verify Certificate</a>
        </div>
        
        <div class="features">
            <div class="feature">
                <div class="feature-icon">✓</div>
                <span>Professional DSES certificate design</span>
            </div>
            <div class="feature">
                <div class="feature-icon">✓</div>
                <span>QR code verification system</span>
            </div>
            <div class="feature">
                <div class="feature-icon">✓</div>
                <span>Secure database storage</span>
            </div>
            <div class="feature">
                <div class="feature-icon">✓</div>
                <span>Print-ready certificates</span>
            </div>
        </div>
    </div>
</body>
</html>
