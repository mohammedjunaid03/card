<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Card - {{ $user->name }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .card-container {
            max-width: 400px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .card-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        
        .card-header p {
            margin: 5px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        
        .card-body {
            padding: 25px;
        }
        
        .user-photo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid #667eea;
            margin: 0 auto 20px;
            display: block;
            object-fit: cover;
        }
        
        .user-info {
            margin-bottom: 20px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 5px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .info-label {
            font-weight: bold;
            color: #333;
            font-size: 12px;
        }
        
        .info-value {
            color: #666;
            font-size: 12px;
            text-align: right;
        }
        
        .card-number {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
        }
        
        .card-number h3 {
            margin: 0;
            color: #667eea;
            font-size: 18px;
            letter-spacing: 2px;
        }
        
        .card-number p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 11px;
        }
        
        .qr-code {
            text-align: center;
            margin: 20px 0;
        }
        
        .qr-code img {
            width: 120px;
            height: 120px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
        }
        
        .validity-info {
            background: #e8f5e8;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
        }
        
        .validity-info h4 {
            margin: 0 0 5px 0;
            color: #2d5a2d;
            font-size: 14px;
        }
        
        .validity-info p {
            margin: 0;
            color: #4a7c4a;
            font-size: 12px;
        }
        
        .footer {
            background: #f8f9fa;
            padding: 15px;
            text-align: center;
            border-top: 1px solid #e0e0e0;
        }
        
        .footer p {
            margin: 0;
            color: #666;
            font-size: 10px;
        }
        
        .footer .website {
            color: #667eea;
            font-weight: bold;
        }
        
        .security-note {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 10px;
            border-radius: 5px;
            margin: 15px 0;
            text-align: center;
        }
        
        .security-note p {
            margin: 0;
            color: #856404;
            font-size: 10px;
        }
        
        .logo {
            width: 40px;
            height: 40px;
            margin: 0 auto 10px;
            display: block;
        }
    </style>
</head>
<body>
    <div class="card-container">
        <!-- Card Header -->
        <div class="card-header">
            <div class="logo">🏥</div>
            <h1>Health Card</h1>
            <p>Digital Healthcare Access Card</p>
        </div>
        
        <!-- Card Body -->
        <div class="card-body">
            <!-- User Photo -->
            @if($user->photo_path && file_exists(storage_path('app/public/' . $user->photo_path)))
                <img src="{{ storage_path('app/public/' . $user->photo_path) }}" alt="User Photo" class="user-photo">
            @else
                <div class="user-photo" style="background: #f0f0f0; display: flex; align-items: center; justify-content: center; color: #999; font-size: 24px;">
                    👤
                </div>
            @endif
            
            <!-- User Information -->
            <div class="user-info">
                <div class="info-row">
                    <span class="info-label">Name:</span>
                    <span class="info-value">{{ $user->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Age:</span>
                    <span class="info-value">{{ $user->age }} years</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Gender:</span>
                    <span class="info-value">{{ $user->gender }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Blood Group:</span>
                    <span class="info-value">{{ $user->blood_group }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Mobile:</span>
                    <span class="info-value">{{ $user->mobile }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $user->email }}</span>
                </div>
            </div>
            
            <!-- Card Number -->
            <div class="card-number">
                <h3>{{ $cardNumber }}</h3>
                <p>Health Card Number</p>
            </div>
            
            <!-- QR Code -->
            <div class="qr-code">
                <img src="{{ $qrCodePath }}" alt="QR Code">
                <p style="margin: 10px 0 0 0; color: #666; font-size: 10px;">Scan for verification</p>
            </div>
            
            <!-- Validity Information -->
            <div class="validity-info">
                <h4>Card Validity</h4>
                <p>Issued: {{ $issuedDate }}</p>
                <p>Valid Until: {{ $expiryDate }}</p>
            </div>
            
            <!-- Security Note -->
            <div class="security-note">
                <p><strong>Security Note:</strong> This card is digitally signed and tamper-proof. Report any misuse immediately.</p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>For support, visit <span class="website">www.healthcardsystem.com</span></p>
            <p>Emergency: +91-800-123-4567 | Email: support@healthcardsystem.com</p>
            <p>This card is valid only at authorized partner hospitals</p>
        </div>
    </div>
</body>
</html>
