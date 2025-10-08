<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Health Card System</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .reset-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 76px); /* Subtract navbar height */
            padding: 20px 0;
        }
        
        .reset-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
            margin: 20px;
        }
        
        .reset-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        
        .reset-body {
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
        
        .password-strength {
            margin-top: 5px;
            font-size: 0.875rem;
        }
        
        .strength-weak { color: #dc3545; }
        .strength-medium { color: #ffc107; }
        .strength-strong { color: #28a745; }
    </style>
</head>
<body>
    @include('components.navbar')
    
    <div class="reset-wrapper">
        <div class="reset-container">
        <div class="reset-header">
            <i class="fas fa-lock fa-2x mb-3"></i>
            <h3 class="fw-bold">Reset Password</h3>
            <p class="mb-0">Enter your new password</p>
        </div>
        
        <div class="reset-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="user_type" value="{{ request('user_type', 'user') }}">
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address *</label>
                    <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                           value="{{ $email ?? old('email') }}" required readonly>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">New Password *</label>
                    <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                           required placeholder="Enter your new password" onkeyup="checkPasswordStrength()">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div id="password-strength" class="password-strength"></div>
                </div>
                
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm New Password *</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" 
                           required placeholder="Confirm your new password">
                </div>
                
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Reset Password
                    </button>
                </div>
            </form>
            
            <div class="text-center">
                <a href="{{ route('login') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Login
                </a>
            </div>
        </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthDiv = document.getElementById('password-strength');
            
            if (password.length === 0) {
                strengthDiv.innerHTML = '';
                return;
            }
            
            let strength = 0;
            let feedback = [];
            
            if (password.length >= 8) strength++;
            else feedback.push('at least 8 characters');
            
            if (/[a-z]/.test(password)) strength++;
            else feedback.push('lowercase letter');
            
            if (/[A-Z]/.test(password)) strength++;
            else feedback.push('uppercase letter');
            
            if (/[0-9]/.test(password)) strength++;
            else feedback.push('number');
            
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            else feedback.push('special character');
            
            let strengthText = '';
            let strengthClass = '';
            
            if (strength < 3) {
                strengthText = 'Weak';
                strengthClass = 'strength-weak';
            } else if (strength < 5) {
                strengthText = 'Medium';
                strengthClass = 'strength-medium';
            } else {
                strengthText = 'Strong';
                strengthClass = 'strength-strong';
            }
            
            if (feedback.length > 0) {
                strengthText += ' - Add: ' + feedback.join(', ');
            }
            
            strengthDiv.innerHTML = `<span class="${strengthClass}">${strengthText}</span>`;
        }
    </script>
</body>
</html>