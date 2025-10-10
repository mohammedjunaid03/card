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
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 50%, #cbd5e1 100%);
            min-height: 100vh;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            padding-top: 80px;
            position: relative;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="medical-pattern" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="%23e2e8f0" opacity="0.3"/></pattern></defs><rect width="100" height="100" fill="url(%23medical-pattern)"/></svg>');
            opacity: 0.4;
            z-index: -1;
        }
        
        .login-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 80px);
            padding: 40px 20px;
        }
        
        .login-container {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 28px;
            box-shadow: 0 32px 64px rgba(0, 0, 0, 0.12), 0 0 0 1px rgba(255, 255, 255, 0.05);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
            margin: 20px;
            position: relative;
            transition: all 0.3s ease;
        }
        
        .login-container:hover {
            transform: translateY(-2px);
            box-shadow: 0 40px 80px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(255, 255, 255, 0.1);
        }
        
        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #2563eb 0%, #0891b2 50%, #06b6d4 100%);
        }
        
        .login-container::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.02) 0%, rgba(8, 145, 178, 0.02) 100%);
            pointer-events: none;
        }
        
        .login-content { 
            padding: 50px 40px 40px; 
        }
        
        .login-header { 
            text-align: center; 
            margin-bottom: 40px; 
        }
        
        .login-header h2 { 
            font-weight: 700; 
            color: #1a202c; 
            font-size: 2rem;
            margin-bottom: 8px;
        }
        
        .login-header p { 
            color: #718096; 
            font-size: 1rem; 
            margin: 0;
        }
        
        .form-label {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }
        
        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            padding: 16px 18px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
            font-weight: 500;
        }
        
        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
            background: white;
            transform: translateY(-1px);
        }
        
        .form-control:hover {
            border-color: #cbd5e1;
            background: white;
        }
        
        .form-control::placeholder {
            color: #a0aec0;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #2563eb 0%, #0891b2 100%);
            border: none;
            border-radius: 14px;
            padding: 16px 28px;
            font-weight: 700;
            font-size: 1.05rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.6s;
        }
        
        .btn-primary:hover::before {
            left: 100%;
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(37, 99, 235, 0.4);
            background: linear-gradient(135deg, #1d4ed8 0%, #0e7490 100%);
        }
        
        .btn-primary:active {
            transform: translateY(-1px);
        }
        
        .btn-outline-primary {
            border: 2px solid #2563eb;
            color: #2563eb;
            border-radius: 12px;
            padding: 10px 18px;
            font-weight: 600;
            transition: all 0.3s ease;
            background: transparent;
            font-size: 0.9rem;
        }
        
        .btn-outline-primary:hover {
            background: #2563eb;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
        }
        
        .btn-outline-primary.active {
            background: #2563eb;
            color: white;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
        }
        
        .form-check-input:checked {
            background-color: #2563eb;
            border-color: #2563eb;
        }
        
        .form-check-input:focus {
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        
        .form-check-label {
            color: #4a5568;
            font-weight: 500;
            font-size: 0.95rem;
        }
        
        .alert { 
            border-radius: 12px; 
            border: none; 
            padding: 16px 20px;
        }
        
        .text-primary { 
            color: #2563eb !important; 
        }
        
        .back-btn {
            background: rgba(37, 99, 235, 0.08);
            border: 1px solid rgba(37, 99, 235, 0.2);
            color: #2563eb;
            border-radius: 12px;
            padding: 10px 18px;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .back-btn:hover {
            background: rgba(37, 99, 235, 0.12);
            color: #1d4ed8;
            transform: translateX(-3px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }
        
        .role-switcher {
            background: rgba(37, 99, 235, 0.03);
            border: 1px solid rgba(37, 99, 235, 0.1);
            border-radius: 16px;
            padding: 24px;
            margin-top: 32px;
            position: relative;
        }
        
        .role-switcher::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, #2563eb 0%, #0891b2 100%);
            border-radius: 16px 16px 0 0;
        }
        
        .role-switcher .btn-group {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            gap: 2px;
        }
        
        .additional-links {
            margin-top: 24px;
        }
        
        .additional-links .btn {
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }
        
        .additional-links .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
        
        .btn-outline-success {
            border: 2px solid #10b981;
            color: #10b981;
        }
        
        .btn-outline-success:hover {
            background: #10b981;
            color: white;
        }
        
        .btn-outline-info {
            border: 2px solid #06b6d4;
            color: #06b6d4;
        }
        
        .btn-outline-info:hover {
            background: #06b6d4;
            color: white;
        }
        
        /* Enhanced Animations */
        .login-container {
            animation: slideInUp 0.6s ease-out;
        }
        
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .form-control {
            animation: fadeInUp 0.4s ease-out;
            animation-fill-mode: both;
        }
        
        .form-control:nth-child(1) { animation-delay: 0.1s; }
        .form-control:nth-child(2) { animation-delay: 0.2s; }
        .form-control:nth-child(3) { animation-delay: 0.3s; }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @media (max-width: 768px) {
            .login-content { 
                padding: 40px 24px 30px; 
            }
            
            .login-header h2 {
                font-size: 1.75rem;
            }
            
            .login-container {
                margin: 10px;
                border-radius: 24px;
            }
            
            .role-switcher {
                padding: 20px;
                margin-top: 24px;
            }
        }
        
        @media (max-width: 480px) {
            .login-content { 
                padding: 30px 20px 25px; 
            }
            
            .login-header h2 {
                font-size: 1.5rem;
            }
            
            .login-container {
                border-radius: 20px;
            }
            
            .btn-primary {
                padding: 14px 24px;
                font-size: 1rem;
            }
            
            .form-control {
                padding: 14px 16px;
            }
        }
    </style>
</head>
<body>
    @include('components.navbar')

    @php
        $role = request('role') ?: 'user';
    @endphp

    <div class="login-wrapper">
        <div class="login-container">
            <div class="login-content">
                <div class="login-header">
                    <div class="d-flex justify-content-start mb-4">
                        <a href="{{ route('home') }}" class="back-btn">
                            <i class="fas fa-arrow-left me-2"></i> Back to Home
                        </a>
                    </div>
                    <h2>
                        @if($role === 'hospital')
                            <i class="fas fa-hospital me-2 text-primary"></i>Hospital Login
                        @elseif($role === 'staff')
                            <i class="fas fa-user-md me-2 text-primary"></i>Staff Login
                        @elseif($role === 'admin')
                            <i class="fas fa-user-shield me-2 text-primary"></i>Admin Login
                        @else
                            <i class="fas fa-user me-2 text-primary"></i>User Login
                        @endif
                    </h2>
                    <p>Sign in to your {{ $role }} account</p>
                </div>

                {{-- Alerts --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Login Form --}}
                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf
                    <input type="hidden" name="user_type" value="{{ $role }}">

                    <div class="mb-3">
                        <label for="login" class="form-label">Email or Mobile</label>
                        <input type="text"
                               id="login"
                               name="login"
                               class="form-control @error('login') is-invalid @enderror"
                               value="{{ old('login') }}"
                               required
                               autofocus
                               placeholder="Enter your email or mobile number">
                        @error('login')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password"
                               id="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               required
                               placeholder="Enter your password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if($role === 'user')
                        <div class="mb-3">
                            <label for="name_or_dob" class="form-label">Name or Date of Birth (if multiple accounts)</label>
                            <input type="text"
                                   id="name_or_dob"
                                   name="name_or_dob"
                                   class="form-control @error('name_or_dob') is-invalid @enderror"
                                   placeholder="Enter your name or date of birth (YYYY-MM-DD)">
                            <small class="form-text text-muted">Required if multiple people use the same email/phone</small>
                            @error('name_or_dob')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif

                    <div class="mb-3 form-check">
                        <input type="checkbox" id="remember" name="remember" class="form-check-input">
                        <label for="remember" class="form-check-label">Remember Me</label>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </button>
                    </div>

                    <div class="text-center">
                        <a href="{{ route('password.request') }}" class="text-decoration-none text-primary">
                            Forgot Password?
                        </a>
                    </div>

                    @if($role === 'user')
                        <hr>
                        <div class="text-center">
                            <p class="mb-0">Don't have an account?
                                <a href="{{ route('register') }}" class="text-decoration-none text-primary">Register here</a>
                            </p>
                        </div>
                    @endif
                </form>

                {{-- Role Switcher --}}
                <div class="role-switcher">
                    <div class="text-center mb-3">
                        <small class="text-muted fw-semibold">Switch Login Type:</small>
                    </div>
                    <div class="btn-group w-100" role="group">
                        @if($role === 'staff')
                            {{-- For staff login, show only staff, hospital, and admin --}}
                            <a href="{{ route('login', ['role' => 'hospital']) }}" class="btn btn-outline-primary {{ $role === 'hospital' ? 'active' : '' }}">
                                <i class="fas fa-hospital me-1"></i>Hospital
                            </a>
                            <a href="{{ route('login', ['role' => 'staff']) }}" class="btn btn-outline-primary {{ $role === 'staff' ? 'active' : '' }}">
                                <i class="fas fa-user-md me-1"></i>Staff
                            </a>
                            <a href="{{ route('login', ['role' => 'admin']) }}" class="btn btn-outline-primary {{ $role === 'admin' ? 'active' : '' }}">
                                <i class="fas fa-user-shield me-1"></i>Admin
                            </a>
                        @elseif($role === 'user')
                            {{-- For user login, show only user --}}
                            <a href="{{ route('login', ['role' => 'user']) }}" class="btn btn-outline-primary {{ $role === 'user' ? 'active' : '' }}">
                                <i class="fas fa-user me-1"></i>User
                            </a>
                        @elseif($role === 'hospital')
                            {{-- For hospital login, show only hospital, staff, and admin --}}
                            <a href="{{ route('login', ['role' => 'hospital']) }}" class="btn btn-outline-primary {{ $role === 'hospital' ? 'active' : '' }}">
                                <i class="fas fa-hospital me-1"></i>Hospital
                            </a>
                            <a href="{{ route('login', ['role' => 'staff']) }}" class="btn btn-outline-primary {{ $role === 'staff' ? 'active' : '' }}">
                                <i class="fas fa-user-md me-1"></i>Staff
                            </a>
                            <a href="{{ route('login', ['role' => 'admin']) }}" class="btn btn-outline-primary {{ $role === 'admin' ? 'active' : '' }}">
                                <i class="fas fa-user-shield me-1"></i>Admin
                            </a>
                        @elseif($role === 'admin')
                            {{-- For admin login, show only hospital, staff, and admin --}}
                            <a href="{{ route('login', ['role' => 'hospital']) }}" class="btn btn-outline-primary {{ $role === 'hospital' ? 'active' : '' }}">
                                <i class="fas fa-hospital me-1"></i>Hospital
                            </a>
                            <a href="{{ route('login', ['role' => 'staff']) }}" class="btn btn-outline-primary {{ $role === 'staff' ? 'active' : '' }}">
                                <i class="fas fa-user-md me-1"></i>Staff
                            </a>
                            <a href="{{ route('login', ['role' => 'admin']) }}" class="btn btn-outline-primary {{ $role === 'admin' ? 'active' : '' }}">
                                <i class="fas fa-user-shield me-1"></i>Admin
                            </a>
                        @else
                            {{-- Default fallback, show all options --}}
                            <a href="{{ route('login', ['role' => 'user']) }}" class="btn btn-outline-primary {{ $role === 'user' ? 'active' : '' }}">
                                <i class="fas fa-user me-1"></i>User
                            </a>
                            <a href="{{ route('login', ['role' => 'hospital']) }}" class="btn btn-outline-primary {{ $role === 'hospital' ? 'active' : '' }}">
                                <i class="fas fa-hospital me-1"></i>Hospital
                            </a>
                            <a href="{{ route('login', ['role' => 'staff']) }}" class="btn btn-outline-primary {{ $role === 'staff' ? 'active' : '' }}">
                                <i class="fas fa-user-md me-1"></i>Staff
                            </a>
                            <a href="{{ route('login', ['role' => 'admin']) }}" class="btn btn-outline-primary {{ $role === 'admin' ? 'active' : '' }}">
                                <i class="fas fa-user-shield me-1"></i>Admin
                            </a>
                        @endif
                    </div>
                    
                    @if($role === 'staff' || $role === 'hospital' || $role === 'admin')
                        {{-- Add Patient Login link for staff, hospital, and admin --}}
                        <div class="additional-links text-center">
                            <a href="{{ route('login', ['role' => 'user']) }}" class="btn btn-outline-success">
                                <i class="fas fa-user-injured me-2"></i> Patient Login
                            </a>
                        </div>
                    @elseif($role === 'user')
                        {{-- Add Hospital Login link for user --}}
                        <div class="additional-links text-center">
                            <a href="{{ route('login', ['role' => 'hospital']) }}" class="btn btn-outline-info">
                                <i class="fas fa-hospital me-2"></i> Hospital Login
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script>
    // Refresh CSRF token every 10 minutes
    setInterval(() => {
        fetch('{{ route("login") }}', { method: 'HEAD', credentials: 'same-origin' })
            .then(() => console.log('CSRF token refreshed'));
    }, 600000);

    // Prevent double submission
    document.getElementById('loginForm').addEventListener('submit', function() {
        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging in...';
    });
    </script>
</body>
</html>
