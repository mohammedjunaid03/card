<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Health Card System</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .forgot-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 76px); /* Subtract navbar height */
            padding: 20px 0;
        }
        
        .forgot-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
            margin: 20px;
        }
        
        .forgot-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        
        .forgot-body {
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
        
        .method-tabs {
            border-bottom: 1px solid #e9ecef;
            margin-bottom: 20px;
        }
        
        .method-tab {
            padding: 10px 20px;
            border: none;
            background: none;
            color: #6c757d;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .method-tab.active {
            color: #667eea;
            border-bottom: 2px solid #667eea;
        }
        
        .method-tab:hover {
            color: #667eea;
        }
    </style>
</head>
<body>
    @include('components.navbar')
    
    <div class="forgot-wrapper">
        <div class="forgot-container">
        <div class="forgot-header">
            <i class="fas fa-key fa-2x mb-3"></i>
            <h3 class="fw-bold">Forgot Password?</h3>
            <p class="mb-0">Don't worry, we'll help you reset it</p>
        </div>
        
        <div class="forgot-body">
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
            
            <!-- Method Selection Tabs -->
            <div class="method-tabs">
                <button class="method-tab active" onclick="showMethod('email')">
                    <i class="fas fa-envelope me-2"></i>Email
                </button>
                <button class="method-tab" onclick="showMethod('otp')">
                    <i class="fas fa-mobile-alt me-2"></i>SMS OTP
                </button>
            </div>
            
            <!-- Email Method -->
            <div id="email-method">
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="user_type_email" class="form-label">Select Your Role *</label>
                        <select id="user_type_email" name="user_type" class="form-control @error('user_type') is-invalid @enderror" required>
                            <option value="">-- Select Role --</option>
                            @php
                                $currentRole = request('role') ?: old('user_type');
                                $isPatientLogin = request('role') === 'user' || request()->is('login') && !request('role');
                                $isStaffLogin = request('role') === 'staff';
                            @endphp
                            
                            @if($isPatientLogin)
                                {{-- Patient login - only show patient option --}}
                                <option value="user" {{ $currentRole == 'user' ? 'selected' : '' }}>Patient/User</option>
                            @elseif($isStaffLogin)
                                {{-- Staff login - show all except patient --}}
                                <option value="hospital" {{ $currentRole == 'hospital' ? 'selected' : '' }}>Hospital Staff</option>
                                <option value="staff" {{ $currentRole == 'staff' ? 'selected' : '' }}>System Staff</option>
                                <option value="admin" {{ $currentRole == 'admin' ? 'selected' : '' }}>System Admin</option>
                            @else
                                {{-- Default - show all options --}}
                                <option value="user" {{ $currentRole == 'user' ? 'selected' : '' }}>Patient/User</option>
                                <option value="hospital" {{ $currentRole == 'hospital' ? 'selected' : '' }}>Hospital Staff</option>
                                <option value="staff" {{ $currentRole == 'staff' ? 'selected' : '' }}>System Staff</option>
                                <option value="admin" {{ $currentRole == 'admin' ? 'selected' : '' }}>System Admin</option>
                            @endif
                        </select>
                        @error('user_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address *</label>
                        <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}" required placeholder="Enter your email address">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-2"></i>Send Reset Link
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- OTP Method -->
            <div id="otp-method" style="display: none;">
                <form method="POST" action="{{ route('password.otp') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="user_type_otp" class="form-label">Select Your Role *</label>
                        <select id="user_type_otp" name="user_type" class="form-control @error('user_type') is-invalid @enderror" required>
                            <option value="">-- Select Role --</option>
                            @php
                                $currentRole = request('role') ?: old('user_type');
                                $isPatientLogin = request('role') === 'user' || request()->is('login') && !request('role');
                                $isStaffLogin = request('role') === 'staff';
                            @endphp
                            
                            @if($isPatientLogin)
                                {{-- Patient login - only show patient option --}}
                                <option value="user" {{ $currentRole == 'user' ? 'selected' : '' }}>Patient/User</option>
                            @elseif($isStaffLogin)
                                {{-- Staff login - show all except patient --}}
                                <option value="hospital" {{ $currentRole == 'hospital' ? 'selected' : '' }}>Hospital Staff</option>
                                <option value="staff" {{ $currentRole == 'staff' ? 'selected' : '' }}>System Staff</option>
                                <option value="admin" {{ $currentRole == 'admin' ? 'selected' : '' }}>System Admin</option>
                            @else
                                {{-- Default - show all options --}}
                                <option value="user" {{ $currentRole == 'user' ? 'selected' : '' }}>Patient/User</option>
                                <option value="hospital" {{ $currentRole == 'hospital' ? 'selected' : '' }}>Hospital Staff</option>
                                <option value="staff" {{ $currentRole == 'staff' ? 'selected' : '' }}>System Staff</option>
                                <option value="admin" {{ $currentRole == 'admin' ? 'selected' : '' }}>System Admin</option>
                            @endif
                        </select>
                        @error('user_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="mobile" class="form-label">Mobile Number *</label>
                        <input id="mobile" type="text" name="mobile" class="form-control @error('mobile') is-invalid @enderror" 
                               value="{{ old('mobile') }}" required placeholder="Enter your mobile number" maxlength="10">
                        @error('mobile')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-sms me-2"></i>Send OTP
                        </button>
                    </div>
                </form>
            </div>
            
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
        function showMethod(method) {
            // Hide all methods
            document.getElementById('email-method').style.display = 'none';
            document.getElementById('otp-method').style.display = 'none';
            
            // Remove active class from all tabs
            document.querySelectorAll('.method-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Show selected method
            document.getElementById(method + '-method').style.display = 'block';
            
            // Add active class to clicked tab
            event.target.classList.add('active');
        }
        
    </script>
</body>
</html>