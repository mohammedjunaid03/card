<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - Health Card System</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .otp-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 76px); /* Subtract navbar height */
            padding: 20px 0;
        }
        
        .otp-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
            margin: 20px;
        }
        
        .otp-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        
        .otp-body {
            padding: 40px;
        }
        
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-outline-primary {
            border: 2px solid #667eea;
            color: #667eea;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-outline-primary:hover {
            background: #667eea;
            color: white;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
        }
        
        .text-primary {
            color: #667eea !important;
        }
        
        .otp-input {
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
            letter-spacing: 0.5rem;
        }
        
        .countdown {
            color: #6c757d;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    @include('components.navbar')
    
    <div class="otp-wrapper">
        <div class="otp-container">
        <div class="otp-header">
            <i class="fas fa-mobile-alt fa-2x mb-3"></i>
            <h3 class="fw-bold">Verify OTP</h3>
            <p class="mb-0">Enter the 6-digit code sent to your mobile</p>
        </div>
        
        <div class="otp-body">
            @if (session('status'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                </div>
            @endif
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            @if(session('registration_otp'))
                {{-- Registration OTP Verification --}}
                <form method="POST" action="{{ route('otp.verify.submit') }}">
                    @csrf
            @else
                {{-- Password Reset OTP Verification --}}
                <form method="POST" action="{{ route('password.verify-otp.submit') }}">
                    @csrf
                    <input type="hidden" name="mobile" value="{{ $mobile }}">
                    <input type="hidden" name="user_type" value="{{ $user_type }}">
            @endif
                
                <div class="mb-3">
                    <label for="otp" class="form-label">Enter OTP *</label>
                    <input id="otp" type="text" name="otp" class="form-control otp-input @error('otp') is-invalid @enderror" 
                           value="{{ old('otp') }}" required placeholder="000000" maxlength="6" pattern="[0-9]{6}">
                    @error('otp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check me-2"></i>Verify OTP
                    </button>
                </div>
            </form>
            
            <div class="text-center mb-3">
                <p class="countdown" id="countdown">Resend OTP in <span id="timer">60</span> seconds</p>
                @if(session('registration_otp'))
                    {{-- Registration OTP Resend --}}
                    <form method="POST" action="{{ route('otp.resend') }}" id="resend-form" style="display: none;">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fas fa-redo me-2"></i>Resend OTP
                        </button>
                    </form>
                @else
                    {{-- Password Reset OTP Resend --}}
                    <form method="POST" action="{{ route('password.resend-otp') }}" id="resend-form" style="display: none;">
                        @csrf
                        <input type="hidden" name="mobile" value="{{ $mobile }}">
                        <input type="hidden" name="user_type" value="{{ $user_type }}">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fas fa-redo me-2"></i>Resend OTP
                        </button>
                    </form>
                @endif
            </div>
            
        </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        // Countdown timer for resend OTP
        let timeLeft = 60;
        const timer = document.getElementById('timer');
        const countdown = document.getElementById('countdown');
        const resendForm = document.getElementById('resend-form');
        
        const countdownInterval = setInterval(() => {
            timeLeft--;
            timer.textContent = timeLeft;
            
            if (timeLeft <= 0) {
                clearInterval(countdownInterval);
                countdown.style.display = 'none';
                resendForm.style.display = 'block';
            }
        }, 1000);
        
        // Auto-focus on OTP input
        document.getElementById('otp').focus();
        
        // Format OTP input (numbers only)
        document.getElementById('otp').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
        
    </script>
</body>
</html>