<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Health Card System</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Sidebar Styles */
        .wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
        }
        
        /* Clean Responsive Sidebar */
        #sidebar {
            position: fixed;
            top: 0;
            left: -260px; /* Hidden by default on mobile */
            width: 250px;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transition: all 0.3s ease;
            z-index: 1050;
            box-shadow: 3px 0 10px rgba(0, 0, 0, 0.15);
            overflow-y: auto;
            padding-top: 60px;
        }
        
        /* When sidebar is open */
        #sidebar.active {
            left: 0;
        }
        
        #sidebar .sidebar-header {
            padding: 20px;
            background: rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        #sidebar ul.components {
            padding: 20px 0;
        }
        
        #sidebar ul li a {
            display: block;
            padding: 15px 20px;
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.2s;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        #sidebar ul li:last-child a {
            border-bottom: none;
        }
        
        #sidebar ul li a:hover,
        #sidebar ul li a.active {
            background: rgba(255, 255, 255, 0.2);
            border-left: 4px solid #fff;
        }
        
        #sidebar ul li a i {
            margin-right: 10px;
            width: 20px;
        }
        
        #content {
            width: 100%;
            min-height: 100vh;
            transition: all 0.3s;
            margin-left: 250px;
        }
        
        #content.expanded {
            margin-left: 0;
        }
        
        /* Header Styles */
        .dashboard-header {
            background: #fff;
            padding: 15px 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        /* Clean Hamburger Button */
        .menu-toggle {
            position: fixed;
            top: 15px;
            left: 15px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 12px;
            cursor: pointer;
            z-index: 1100;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }
        
        .menu-toggle:hover {
            background: linear-gradient(135deg, #5a6fd8, #6a4190);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }
        
        .menu-toggle i {
            font-size: 1.2rem;
            transition: transform 0.3s ease;
        }
        
        .menu-toggle:hover i {
            transform: scale(1.1);
        }
        
        /* Body scroll prevention when sidebar is open on mobile */
        body.sidebar-open {
            overflow: hidden;
            position: fixed;
            width: 100%;
        }
        
        /* Smooth transitions for all sidebar states */
        #sidebar {
            transition: margin-left 0.3s ease-in-out, transform 0.3s ease-in-out;
        }
        
        .sidebar-overlay {
            transition: opacity 0.3s ease-in-out;
        }
        
        /* Mobile and Tablet Responsive - Only for screens smaller than desktop */
        @media (max-width: 991.98px) {
            #sidebar {
                left: -260px;
                z-index: 1050;
            }
            #sidebar.active {
                left: 0;
            }
            #content {
                margin-left: 0;
                width: 100%;
            }
            .dashboard-header {
                padding: 10px 20px;
            }
            .menu-toggle {
                display: block;
                font-size: 1.4rem;
                padding: 10px 14px;
                box-shadow: 0 3px 10px rgba(102, 126, 234, 0.4);
            }
        }
        
        @media (max-width: 768px) {
            #sidebar {
                width: 100%;
                max-width: 100%;
                min-width: 100%;
                z-index: 1060;
            }
            #sidebar.hidden {
                margin-left: -100%;
            }
            #sidebar.active {
                margin-left: 0;
            }
            .dashboard-header {
                padding: 8px 15px;
            }
            .dashboard-header h4 {
                font-size: 1.1rem;
            }
            .container-fluid {
                padding: 15px !important;
            }
            
            /* Enhanced mobile sidebar */
            #sidebar .sidebar-header {
                padding: 20px;
                background: rgba(0, 0, 0, 0.1);
            }
            #sidebar ul li a {
                padding: 15px 20px;
                font-size: 1rem;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }
            #sidebar ul li:last-child a {
                border-bottom: none;
            }
        }
        
        @media (max-width: 480px) {
            .dashboard-header {
                padding: 5px 10px;
            }
            .menu-toggle {
                font-size: 1.2rem;
                padding: 6px;
            }
            .dropdown-toggle {
                font-size: 0.9rem;
            }
            .container-fluid {
                padding: 10px !important;
            }
        }
        
        /* Clean Overlay Effect */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 1040;
        }
        
        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        /* Desktop Responsive - Keep Original Desktop View */
        @media (min-width: 992px) {
            #sidebar {
                left: 0;
                position: fixed;
                width: 250px;
                height: 100vh;
                box-shadow: 2px 0 10px rgba(0,0,0,0.1);
                padding-top: 0;
            }
            
            .menu-toggle,
            .sidebar-overlay {
                display: none !important;
            }
            
            #content {
                margin-left: 250px;
                width: calc(100% - 250px);
            }
            
            .dashboard-header {
                margin-left: 0;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3><i class="fas fa-heartbeat"></i> Health Card</h3>
                <p class="mb-0">{{ Auth::guard('admin')->check() ? 'System Administrator' : (Auth::guard('hospital')->check() ? Auth::guard('hospital')->user()->name : (Auth::guard('staff')->check() ? Auth::guard('staff')->user()->name : (Auth::guard('web')->check() ? Auth::guard('web')->user()->name : 'User'))) }}</p>
            </div>

            <ul class="list-unstyled components">
                @if(Auth::guard('admin')->check())
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="fas fa-users"></i> Users
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.hospitals.index') }}" class="{{ request()->routeIs('admin.hospitals.*') ? 'active' : '' }}">
                            <i class="fas fa-hospital"></i> Hospitals
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.staff.index') }}" class="{{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">
                            <i class="fas fa-user-tie"></i> Staff
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.card-approvals') }}" class="{{ request()->routeIs('admin.card-approvals') ? 'active' : '' }}">
                            <i class="fas fa-id-card"></i> Card Approvals
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.analytics') }}" class="{{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                            <i class="fas fa-chart-line"></i> Analytics
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.reports') }}" class="{{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                            <i class="fas fa-file-alt"></i> Reports
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.notifications') }}" class="{{ request()->routeIs('admin.notifications') ? 'active' : '' }}">
                            <i class="fas fa-bell"></i> Notifications
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.audit-logs') }}" class="{{ request()->routeIs('admin.audit-logs') ? 'active' : '' }}">
                            <i class="fas fa-history"></i> Audit Logs
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.settings') }}" class="{{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                            <i class="fas fa-cog"></i> Settings
                        </a>
                    </li>
                @elseif(Auth::guard('hospital')->check())
                    <li>
                        <a href="{{ route('hospital.dashboard') }}" class="{{ request()->routeIs('hospital.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('hospital.verify-card') }}" class="{{ request()->routeIs('hospital.verify-card') ? 'active' : '' }}">
                            <i class="fas fa-qrcode"></i> Verify Card
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('hospital.services.index') }}" class="{{ request()->routeIs('hospital.services.*') ? 'active' : '' }}">
                            <i class="fas fa-stethoscope"></i> Services
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('hospital.availments.index') }}" class="{{ request()->routeIs('hospital.availments.*') ? 'active' : '' }}">
                            <i class="fas fa-receipt"></i> Availments
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('hospital.patients') }}" class="{{ request()->routeIs('hospital.patients') ? 'active' : '' }}">
                            <i class="fas fa-users"></i> Patients
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('hospital.reports') }}" class="{{ request()->routeIs('hospital.reports') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar"></i> Reports
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('hospital.profile') }}" class="{{ request()->routeIs('hospital.profile') ? 'active' : '' }}">
                            <i class="fas fa-user-circle"></i> Profile
                        </a>
                    </li>
                @elseif(Auth::guard('staff')->check())
                    <li>
                        <a href="{{ route('staff.dashboard') }}" class="{{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('staff.users.create') }}" class="{{ request()->routeIs('staff.users.*') ? 'active' : '' }}">
                            <i class="fas fa-user-plus"></i> Register User
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('staff.hospitals.create') }}" class="{{ request()->routeIs('staff.hospitals.*') ? 'active' : '' }}">
                            <i class="fas fa-hospital-alt"></i> Register Hospital
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('staff.verify-documents') }}" class="{{ request()->routeIs('staff.verify-documents') ? 'active' : '' }}">
                            <i class="fas fa-file-check"></i> Verify Documents
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('staff.reports') }}" class="{{ request()->routeIs('staff.reports') ? 'active' : '' }}">
                            <i class="fas fa-chart-line"></i> Reports
                        </a>
                    </li>
                @elseif(Auth::guard('web')->check())
                    <li>
                        <a href="{{ route('user.dashboard') }}" class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.health-card') }}" class="{{ request()->routeIs('user.health-card') ? 'active' : '' }}">
                            <i class="fas fa-id-card"></i> My Health Card
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.discount-history') }}" class="{{ request()->routeIs('user.discount-history') ? 'active' : '' }}">
                            <i class="fas fa-history"></i> Discount History
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.hospitals') }}" class="{{ request()->routeIs('user.hospitals*') ? 'active' : '' }}">
                            <i class="fas fa-hospital"></i> Find Hospitals
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.notifications') }}" class="{{ request()->routeIs('user.notifications') ? 'active' : '' }}">
                            <i class="fas fa-bell"></i> Notifications
                            @php
                                $unreadCount = auth()->user()->notifications()->unread()->count();
                            @endphp
                            @if($unreadCount > 0)
                                <span class="badge bg-danger">{{ $unreadCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.support-tickets.index') }}" class="{{ request()->routeIs('user.support-tickets*') ? 'active' : '' }}">
                            <i class="fas fa-ticket-alt"></i> Support
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.profile') }}" class="{{ request()->routeIs('user.profile') ? 'active' : '' }}">
                            <i class="fas fa-user-cog"></i> Profile Settings
                        </a>
                    </li>
                @endif
                
                <li>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
        </nav>
        
        <!-- Sidebar Overlay for Mobile -->
        <div class="sidebar-overlay"></div>

        <!-- Page Content -->
        <div id="content">
            <!-- Top Header -->
            <div class="dashboard-header">
                <button type="button" id="sidebarCollapse" class="menu-toggle">
                    <i class="fas fa-bars"></i>
                </button>
                
                <div class="d-flex align-items-center">
                    <div class="dropdown">
                        <button class="btn btn-link dropdown-toggle text-decoration-none" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle fa-2x"></i>
                            <span class="ms-2">
                                @if(Auth::guard('admin')->check())
                                    System Administrator
                                @elseif(Auth::guard('hospital')->check())
                                    {{ Auth::guard('hospital')->user()->name }}
                                @elseif(Auth::guard('staff')->check())
                                    {{ Auth::guard('staff')->user()->name }}
                                @elseif(Auth::guard('web')->check())
                                    {{ Auth::guard('web')->user()->name }}
                                @endif
                            </span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if(Auth::guard('hospital')->check())
                                <li><a class="dropdown-item" href="{{ route('hospital.profile') }}">Profile</a></li>
                            @elseif(Auth::guard('staff')->check())
                                <li><a class="dropdown-item" href="{{ route('staff.dashboard') }}">Dashboard</a></li>
                            @elseif(Auth::guard('web')->check())
                                <li><a class="dropdown-item" href="{{ route('user.profile') }}">Profile</a></li>
                            @endif
                            @if(Auth::guard('admin')->check())
                                <li><a class="dropdown-item" href="{{ route('admin.settings') }}">Settings</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="container-fluid p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </div>
    </div>
    
    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Clean Sidebar Toggle Script
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.querySelector('#sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            const button = document.querySelector('#sidebarCollapse');
            const links = document.querySelectorAll('#sidebar ul li a');

            // Toggle sidebar when hamburger button is clicked
            button.addEventListener('click', () => {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
            });

            // Close sidebar when overlay is clicked
            overlay.addEventListener('click', () => {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            });

            // Close sidebar when any navigation link is clicked
            links.forEach(link => {
                link.addEventListener('click', () => {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                });
            });
        });

        // Back Button Logout Security
        document.addEventListener('DOMContentLoaded', function() {
            // Define dashboard routes that should keep user logged in
            const dashboardRoutes = [
                '/admin/dashboard',
                '/admin/users',
                '/admin/hospitals',
                '/admin/staff',
                '/admin/card-approvals',
                '/admin/analytics',
                '/admin/reports',
                '/admin/notifications',
                '/admin/audit-logs',
                '/admin/settings',
                '/hospital/dashboard',
                '/hospital/verify-card',
                '/hospital/services',
                '/hospital/availments',
                '/hospital/patients',
                '/hospital/reports',
                '/hospital/profile',
                '/staff/dashboard',
                '/staff/users',
                '/staff/hospitals',
                '/staff/verify-documents',
                '/staff/reports',
                '/user/dashboard',
                '/user/health-card',
                '/user/discount-history',
                '/user/hospitals',
                '/user/notifications',
                '/user/support-tickets',
                '/user/profile'
            ];

            // Check if current URL is a dashboard route
            function isDashboardRoute(url) {
                return dashboardRoutes.some(route => url.includes(route));
            }

            // Logout function
            function performLogout() {
                console.log('User navigated away from dashboard - performing logout');
                
                // Create a form to submit logout request
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("logout") }}';
                form.style.display = 'none';
                
                // Add CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                document.body.appendChild(form);
                form.submit();
            }

            // Track page visibility and navigation
            let isLeavingDashboard = false;

            // Handle browser back/forward buttons
            window.addEventListener('popstate', function(event) {
                const currentUrl = window.location.pathname;
                console.log('Navigation detected:', currentUrl);
                
                if (!isDashboardRoute(currentUrl)) {
                    console.log('User navigated outside dashboard area');
                    isLeavingDashboard = true;
                    performLogout();
                }
            });

            // Handle page unload (when user navigates away)
            window.addEventListener('beforeunload', function(event) {
                const currentUrl = window.location.pathname;
                
                if (!isDashboardRoute(currentUrl) && !isLeavingDashboard) {
                    console.log('User is leaving dashboard area');
                    // Note: We can't perform logout here due to browser restrictions
                    // The popstate event will handle back button navigation
                }
            });

            // Handle direct navigation (typing URL, clicking external links)
            document.addEventListener('click', function(event) {
                const link = event.target.closest('a');
                if (link && link.href) {
                    const url = new URL(link.href);
                    if (!isDashboardRoute(url.pathname) && !url.href.includes('logout')) {
                        console.log('User clicked link outside dashboard area');
                        isLeavingDashboard = true;
                        performLogout();
                    }
                }
            });

            // Additional security: Check on page load
            const currentUrl = window.location.pathname;
            if (!isDashboardRoute(currentUrl)) {
                console.log('User accessed non-dashboard page - performing logout');
                performLogout();
            }

            console.log('Dashboard security initialized - back button logout enabled');
        });
    </script>
    
    @stack('scripts')
</body>
</html>