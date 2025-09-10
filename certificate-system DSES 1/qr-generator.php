<?php
// Simple QR Code generator using HTML5 Canvas and JavaScript
function generateLocalQRCode($data, $containerId) {
    return "
    <div id='{$containerId}' style='width: 120px; height: 120px; border: 2px solid #333; background: white; display: flex; align-items: center; justify-content: center;'>
        <canvas id='qr-{$containerId}' width='120' height='120'></canvas>
    </div>
    <script src='https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js'></script>
    <script>
        QRCode.toCanvas(document.getElementById('qr-{$containerId}'), '{$data}', {
            width: 120,
            height: 120,
            margin: 1,
            color: {
                dark: '#000000',
                light: '#FFFFFF'
            }
        }, function (error) {
            if (error) {
                console.error('QR Code generation failed:', error);
                document.getElementById('{$containerId}').innerHTML = '<div style=\"font-size: 10px; text-align: center; color: #666;\">QR CODE<br>Verification<br>Available</div>';
            } else {
                console.log('QR Code generated successfully!');
            }
        });
    </script>
    ";
}
?>
