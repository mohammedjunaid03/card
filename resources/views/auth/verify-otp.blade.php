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
            text-align: center;
        }
        .form-group { margin-bottom: 15px; text-align: left; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .error { color: red; font-size: 0.9em; }
        .success { color: green; font-size: 0.9em; }
        .btn-primary { width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; margin-bottom: 10px; }
        .btn-primary:hover { background-color: #0056b3; }
        .btn-secondary { width: 100%; padding: 10px; background-color: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .btn-secondary:hover { background-color: #545b62; }
        .alert { padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>
    <div class="otp-container">
        <h2>Verify Your Email</h2>
        <p>We've sent a 6-digit OTP to <strong>{{ $email }}</strong></p>
        
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form method="POST" action="{{ route('otp.verify.submit') }}">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            
            <div class="form-group">
                <label for="otp">Enter 6-Digit OTP</label>
                <input id="otp" type="text" name="otp" value="{{ old('otp') }}" required autofocus maxlength="6" pattern="[0-9]{6}" placeholder="123456">
                @error('otp')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <button type="submit" class="btn-primary">Verify OTP</button>
            </div>
        </form>
        
        <div style="margin-top: 20px;">
            <p>Didn't receive the OTP?</p>
            <button type="button" class="btn-secondary" onclick="resendOtp()">Resend OTP</button>
        </div>
        
        <div style="text-align: center; margin-top: 15px;">
            <p><a href="{{ route('register') }}">Back to Registration</a></p>
        </div>
    </div>

    <script>
        function resendOtp() {
            const email = '{{ $email }}';
            
            fetch('/resend-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('OTP sent successfully!');
                } else {
                    alert('Failed to send OTP: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error sending OTP. Please try again.');
            });
        }
    </script>
</body>
</html>