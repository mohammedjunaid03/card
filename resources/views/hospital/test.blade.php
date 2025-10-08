<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Test - Health Card System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Hospital Dashboard Test</h3>
                    </div>
                    <div class="card-body">
                        @if(Auth::guard('hospital')->check())
                            <h4>Welcome, {{ Auth::guard('hospital')->user()->name }}!</h4>
                            <p>Email: {{ Auth::guard('hospital')->user()->email }}</p>
                            <p>Status: {{ Auth::guard('hospital')->user()->status }}</p>
                            <p>Approved At: {{ Auth::guard('hospital')->user()->approved_at }}</p>
                            
                            <a href="{{ route('hospital.dashboard') }}" class="btn btn-primary">Go to Full Dashboard</a>
                            <a href="{{ route('logout') }}" class="btn btn-secondary">Logout</a>
                        @else
                            <h4>Hospital Test Page</h4>
                            <p>This is a test page to verify the hospital routes are working.</p>
                            <p>You are not currently logged in as a hospital.</p>
                            
                            <a href="{{ route('login', ['role' => 'hospital']) }}" class="btn btn-primary">Hospital Login</a>
                            <a href="{{ route('hospital.debug') }}" class="btn btn-info">Check Auth Status</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
