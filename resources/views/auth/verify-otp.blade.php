<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - Health Card System</title>
    <style>
        .otp-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .error { color: red; font-size: 0.9em; }
        .success { color: green; font-size: 0.9em; }
        .btn-primary { width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .btn-primary:hover { background-color: #0056b3; }
        .btn-secondary { width: 100%; padding: 10px; background-color: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer; margin-top: 10px; }
        .btn-secondary:hover { background-color: #545b62; }
    </style>
</head>
<body>
    <div class="otp-container">
        <h2>Verify OTP</h2>

        @if (session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif

        <p>Please enter the 6-digit OTP sent to your email address.</p>

        <form method="POST" action="{{ route('otp.verify.submit') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email Address</label>
                <input id="email" type="email" name="email" value="{{ session('email') ?? old('email') }}" readonly>
                @error('email')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="otp">OTP Code</label>
                <input id="otp" type="text" name="otp" maxlength="6" pattern="[0-9]{6}" required autofocus>
                @error('otp')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <button type="submit" class="btn-primary">Verify OTP</button>
            </div>

            <div class="form-group">
                <a href="{{ route('register') }}" class="btn-secondary">Back to Registration</a>
            </div>
        </form>
    </div>
</body>
</html>