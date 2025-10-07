<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Card - {{ $user->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .card-container {
            max-width: 400px;
            margin: 0 auto;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            color: white;
        }
        .card-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .card-title {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        .card-subtitle {
            font-size: 14px;
            margin: 5px 0 0 0;
            opacity: 0.9;
        }
        .user-photo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid white;
            margin: 0 auto 15px;
            display: block;
            background-color: #fff;
            object-fit: cover;
        }
        .user-info {
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .info-label {
            font-weight: bold;
            opacity: 0.9;
        }
        .info-value {
            text-align: right;
        }
        .qr-section {
            text-align: center;
            margin-top: 15px;
        }
        .qr-code {
            width: 100px;
            height: 100px;
            background-color: white;
            border-radius: 10px;
            padding: 10px;
            margin: 0 auto;
        }
        .card-number {
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0;
            text-align: center;
            background: rgba(255,255,255,0.2);
            padding: 8px;
            border-radius: 5px;
        }
        .validity-info {
            text-align: center;
            font-size: 12px;
            margin-top: 15px;
            opacity: 0.8;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            margin-top: 20px;
            opacity: 0.7;
        }
        .logo {
            width: 60px;
            height: 60px;
            margin: 0 auto 10px;
            display: block;
        }
    </style>
</head>
<body>
    <div class="card-container">
        <!-- Header -->
        <div class="card-header">
            <div class="logo">🏥</div>
            <h1 class="card-title">HEALTH CARD</h1>
            <p class="card-subtitle">Digital Health Management System</p>
        </div>

        <!-- User Photo -->
        @if($user->photo_path && file_exists(storage_path('app/public/' . $user->photo_path)))
            <img src="{{ storage_path('app/public/' . $user->photo_path) }}" alt="User Photo" class="user-photo">
        @else
            <div class="user-photo" style="display: flex; align-items: center; justify-content: center; font-size: 24px;">
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
                <span class="info-value">{{ ucfirst($user->gender) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Blood Group:</span>
                <span class="info-value">{{ $user->blood_group }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Mobile:</span>
                <span class="info-value">{{ $user->mobile }}</span>
            </div>
        </div>

        <!-- Card Number -->
        <div class="card-number">
            {{ $cardNumber }}
        </div>

        <!-- QR Code -->
        <div class="qr-section">
            <div class="qr-code">
                @if(file_exists($qrCodePath))
                    <img src="{{ $qrCodePath }}" alt="QR Code" style="width: 100%; height: 100%;">
                @else
                    <div style="display: flex; align-items: center; justify-content: center; height: 100%; font-size: 12px; color: #666;">
                        QR Code
                    </div>
                @endif
            </div>
        </div>

        <!-- Validity Information -->
        <div class="validity-info">
            <div><strong>Issued:</strong> {{ $issuedDate }}</div>
            <div><strong>Valid Until:</strong> {{ $expiryDate }}</div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>This card is valid for healthcare services at partner hospitals</p>
            <p>For support, contact: support@healthcard.com</p>
        </div>
    </div>
</body>
</html>