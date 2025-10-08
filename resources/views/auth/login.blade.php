<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Health Card System</title>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .login-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 76px); /* Subtract navbar height */
            padding: 20px 0;
        }
        
        .login-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
            margin: 20px;
        }
        
        .login-content {
            padding: 60px 40px;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-header h2 {
            font-weight: 700;
            margin-bottom: 10px;
            color: #333;
        }
        
        .login-header p {
            color: #6c757d;
            font-size: 1.1rem;
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
        
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
        }
        
        .text-primary {
            color: #667eea !important;
        }
        
        @media (max-width: 768px) {
            .login-content {
                padding: 40px 20px;
            }
        }
    </style>
</head>
<body>
    @include('components.navbar')
    
    @php
        $currentRole = request('role') ?: 'user';
        $isStaffLogin = request('role') === 'staff';
        $isAdminLogin = request('role') === 'admin';
        $isHospitalLogin = request('role') === 'hospital';
        $isPatientLogin = !request('role') || request('role') === 'user';
    @endphp
    
    <div class="login-wrapper">
        <div class="login-container">
            <div class="login-content">
                <div class="login-header">
                    @if($isStaffLogin)
                        <h2>Staff & Admin Login</h2>
                        <p>Sign in to your staff, admin, or hospital account</p>
                    @elseif($isAdminLogin)
                        <h2>Admin & Staff Login</h2>
                        <p>Sign in to your admin, staff, or hospital account</p>
                    @elseif($isHospitalLogin)
                        <h2>Hospital Login</h2>
                        <p>Sign in to your hospital, staff, or admin account</p>
                    @else
                        <h2>Welcome Back</h2>
                        <p>Sign in to your account</p>
                    @endif
                </div>
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        @if($isPatientLogin)
                            {{-- Hidden field for patient/user login --}}
                            <input type="hidden" name="user_type" value="user">
                        @else
                            {{-- Show role selection for staff/admin/hospital --}}
                            <div class="mb-3">
                                <label for="user_type" class="form-label">Select Your Role *</label>
                                <select id="user_type" name="user_type" class="form-control @error('user_type') is-invalid @enderror" required>
                                    <option value="">-- Select Role --</option>
                                    @if($isStaffLogin)
                                        <option value="staff" {{ old('user_type', 'staff') == 'staff' ? 'selected' : '' }}>System Staff</option>
                                        <option value="admin" {{ old('user_type') == 'admin' ? 'selected' : '' }}>System Admin</option>
                                        <option value="hospital" {{ old('user_type') == 'hospital' ? 'selected' : '' }}>Hospital</option>
                                    @elseif($isAdminLogin)
                                        <option value="admin" {{ old('user_type', 'admin') == 'admin' ? 'selected' : '' }}>System Admin</option>
                                        <option value="staff" {{ old('user_type') == 'staff' ? 'selected' : '' }}>System Staff</option>
                                        <option value="hospital" {{ old('user_type') == 'hospital' ? 'selected' : '' }}>Hospital</option>
                                    @elseif($isHospitalLogin)
                                        <option value="hospital" {{ old('user_type', 'hospital') == 'hospital' ? 'selected' : '' }}>Hospital</option>
                                        <option value="staff" {{ old('user_type') == 'staff' ? 'selected' : '' }}>System Staff</option>
                                        <option value="admin" {{ old('user_type') == 'admin' ? 'selected' : '' }}>System Admin</option>
                                    @endif
                                </select>
                                @error('user_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                        
                        <div class="mb-3">
                            <label for="login" class="form-label">Email or Mobile *</label>
                            <input id="login" type="text" name="login" class="form-control @error('login') is-invalid @enderror" 
                                   value="{{ old('login') }}" required autofocus placeholder="Enter your email or mobile number">
                            @error('login')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password *</label>
                            <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                                   required placeholder="Enter your password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="remember" id="remember" class="form-check-input">
                            <label for="remember" class="form-check-label">Remember Me</label>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>
                        </div>
                        
                        <div class="text-center">
                            <a href="{{ route('password.request') }}" class="text-primary text-decoration-none">
                                <i class="fas fa-key me-1"></i>Forgot Password?
                            </a>
                        </div>
                        
                        @if(!$isPatientLogin)
                            <div class="text-center mt-2">
                                <a href="{{ route('login') }}" class="text-secondary text-decoration-none small">
                                    <i class="fas fa-arrow-left me-1"></i>Back to Patient Login
                                </a>
                            </div>
                        @endif
                        
                        <hr class="my-4">
                        
                        @if($isPatientLogin)
                            <!-- Staff/Admin/Hospital Login Options -->
                            <div class="text-center mb-3">
                                <p class="mb-3 text-muted">Staff & Admin Access</p>
                                <div class="d-flex flex-wrap justify-content-center gap-2">
                                    <a href="{{ route('login', ['role' => 'staff']) }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-user-tie me-1"></i>Staff Login
                                    </a>
                                    <a href="{{ route('login', ['role' => 'admin']) }}" class="btn btn-outline-warning btn-sm">
                                        <i class="fas fa-user-shield me-1"></i>Admin Login
                                    </a>
                                    <a href="{{ route('login', ['role' => 'hospital']) }}" class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-hospital me-1"></i>Hospital Login
                                    </a>
                                </div>
                            </div>
                        @endif
                        
                        <hr class="my-4">
                        
                        <div class="text-center">
                            <p class="mb-2">New User?</p>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary">
                                <i class="fas fa-user-plus me-2"></i>Register for Health Card
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>