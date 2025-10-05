<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Health Card System</title>
    <style>
        .login-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .error { color: red; font-size: 0.9em; }
        .btn-primary { width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .btn-primary:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>System Login</h2>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group">
                <label for="user_type">Select Your Role</label>
                <select id="user_type" name="user_type" required>
                    <option value="">-- Select --</option>
                    <option value="user" {{ old('user_type') == 'user' ? 'selected' : '' }}>Patient/User</option>
                    <option value="hospital" {{ old('user_type') == 'hospital' ? 'selected' : '' }}>Hospital Staff</option>
                    <option value="staff" {{ old('user_type') == 'staff' ? 'selected' : '' }}>System Staff</option>
                    <option value="admin" {{ old('user_type') == 'admin' ? 'selected' : '' }}>System Admin</option>
                </select>
                @error('user_type')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="login">Email or Mobile</label>
                <input id="login" type="text" name="login" value="{{ old('login') }}" required autofocus>
                @error('login')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required>
                @error('password')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group" style="display: flex; align-items: center;">
                <input type="checkbox" name="remember" id="remember" style="width: auto; margin-right: 5px;">
                <label for="remember" style="font-weight: normal; margin: 0;">Remember Me</label>
            </div>

            <div class="form-group">
                <button type="submit" class="btn-primary">Login</button>
            </div>
            
            <div style="text-align: center; margin-top: 15px;">
                <p>New User? <a href="{{ route('register') }}">Register for Health Card</a></p>
            </div>
        </form>
    </div>
</body>
</html>