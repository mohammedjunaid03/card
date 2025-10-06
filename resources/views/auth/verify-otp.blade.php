<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - Health Card System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .otp-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 10px;
        }
        .subtitle {
            text-align: center;
            color: #666;
            font-size: 14px;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
            text-align: center;
            letter-spacing: 5px;
        }
        .error {
            color: red;
            font-size: 0.9em;
            margin-top: 5px;
        }
        .success {
            color: green;
            font-size: 0.9em;
            margin-bottom: 15px;
            padding: 10px;
            background: #d4edda;
            border-radius: 4px;
        }
        .btn-primary {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .info-box {
            background: #e7f3ff;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 14px;
            color: #004085;
        }
    </style>
</head>
<body>
    <div class="otp-container">
        <h2>Verify Your Email</h2>
        <p class="subtitle">Enter the 6-digit OTP sent to your email</p>
        
        @if(session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif
        
        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif
        
        <div class="info-box">
            <strong>Note:</strong> Please check your email inbox (and spam folder) for the OTP. The OTP is valid for 10 minutes.
        </div>
        
        <form method="POST" action="{{ route('otp.verify.submit') }}">
            @csrf
            
            <div class="form-group">
                <label for="otp">Enter OTP</label>
                <input 
                    id="otp" 
                    type="text" 
                    name="otp" 
                    maxlength="6" 
                    pattern="[0-9]{6}"
                    placeholder="000000"
                    required 
                    autofocus
                >
                @error('otp')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <button type="submit" class="btn-primary">Verify OTP</button>
            </div>
        </form>
        
        <div style="text-align: center; margin-top: 20px;">
            <p style="font-size: 14px; color: #666;">
                Didn't receive the OTP? 
                <a href="{{ route('register') }}" style="color: #007bff;">Register again</a>
            </p>
        </div>
    </div>
</body>
</html>
