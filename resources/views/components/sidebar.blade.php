<nav id="sidebar">
    <div class="sidebar-header">
        <h4><i class="fas fa-heartbeat"></i> Health Card</h4>
        <p class="text-muted small">{{ auth()->user()->name ?? 'User' }}</p>
    </div>
    
    <ul class="list-unstyled components">
        @auth('web')
            <!-- User Sidebar -->
            <li class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                <a href="{{ route('user.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="{{ request()->routeIs('user.health-card') ? 'active' : '' }}">
                <a href="{{ route('user.health-card') }}">
                    <i class="fas fa-id-card"></i> My Health Card
                </a>
            </li>
            <li class="{{ request()->routeIs('user.discount-history') ? 'active' : '' }}">
                <a href="{{ route('user.discount-history') }}">
                    <i class="fas fa-history"></i> Discount History
                </a>
            </li>
            <li class="{{ request()->routeIs('user.hospitals*') ? 'active' : '' }}">
                <a href="{{ route('user.hospitals') }}">
                    <i class="fas fa-hospital"></i> Find Hospitals
                </a>
            </li>
            <li class="{{ request()->routeIs('user.notifications') ? 'active' : '' }}">
                <a href="{{ route('user.notifications') }}">
                    <i class="fas fa-bell"></i> Notifications
                    @php
                        $unreadCount = auth()->user()->notifications()->unread()->count();
                    @endphp
                    @if($unreadCount > 0)
                        <span class="badge bg-danger">{{ $unreadCount }}</span>
                    @endif
                </a>
            </li>
            <li class="{{ request()->routeIs('user.support-tickets*') ? 'active' : '' }}">
                <a href="{{ route('user.support-tickets.index') }}">
                    <i class="fas fa-ticket-alt"></i> Support
                </a>
            </li>
            <li class="{{ request()->routeIs('user.profile') ? 'active' : '' }}">
                <a href="{{ route('user.profile') }}">
                    <i class="fas fa-user-cog"></i> Profile Settings
                </a>
            </li>
        @endauth
        
        @auth('hospital')
            <!-- Hospital Sidebar -->
            <li class="{{ request()->routeIs('hospital.dashboard') ? 'active' : '' }}">
                <a href="{{ route('hospital.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="{{ request()->routeIs('hospital.verify-card') ? 'active' : '' }}">
                <a href="{{ route('hospital.verify-card') }}">
                    <i class="fas fa-qrcode"></i> Verify Card
                </a>
            </li>
            <li class="{{ request()->routeIs('hospital.services*') ? 'active' : '' }}">
                <a href="{{ route('hospital.services.index') }}">
                    <i class="fas fa-stethoscope"></i> Services
                </a>
            </li>
            <li class="{{ request()->routeIs('hospital.availments*') ? 'active' : '' }}">
                <a href="{{ route('hospital.availments') }}">
                    <i class="fas fa-receipt"></i> Availments
                </a>
            </li>
            <li class="{{ request()->routeIs('hospital.patients*') ? 'active' : '' }}">
                <a href="{{ route('hospital.patients') }}">
                    <i class="fas fa-users"></i> Patients
                </a>
            </li>
            <li class="{{ request()->routeIs('hospital.reports') ? 'active' : '' }}">
                <a href="{{ route('hospital.reports') }}">
                    <i class="fas fa-chart-bar"></i> Reports
                </a>
            </li>
            <li class="{{ request()->routeIs('hospital.profile') ? 'active' : '' }}">
                <a href="{{ route('hospital.profile') }}">
                    <i class="fas fa-cog"></i> Profile
                </a>
            </li>
        @endauth
        
        @auth('staff')
            <!-- Staff Sidebar -->
            <li class="{{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
                <a href="{{ route('staff.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="{{ request()->routeIs('staff.users*') ? 'active' : '' }}">
                <a href="{{ route('staff.users.create') }}">
                    <i class="fas fa-user-plus"></i> Register User
                </a>
            </li>
            <li class="{{ request()->routeIs('staff.hospitals*') ? 'active' : '' }}">
                <a href="{{ route('staff.hospitals.create') }}">
                    <i class="fas fa-hospital-alt"></i> Register Hospital
                </a>
            </li>
            <li class="{{ request()->routeIs('staff.verify-documents') ? 'active' : '' }}">
                <a href="{{ route('staff.verify-documents') }}">
                    <i class="fas fa-file-check"></i> Verify Documents
                </a>
            </li>
            <li class="{{ request()->routeIs('staff.reports') ? 'active' : '' }}">
                <a href="{{ route('staff.reports') }}">
                    <i class="fas fa-chart-line"></i> Reports
                </a>
            </li>
        @endauth
        
        @auth('admin')
            <!-- Admin Sidebar -->
            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <a href="{{ route('admin.users.index') }}">
                    <i class="fas fa-users"></i> Users
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.hospitals*') ? 'active' : '' }}">
                <a href="{{ route('admin.hospitals.index') }}">
                    <i class="fas fa-hospital"></i> Hospitals
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.staff*') ? 'active' : '' }}">
                <a href="{{ route('admin.staff.index') }}">
                    <i class="fas fa-user-tie"></i> Staff
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.card-approvals') ? 'active' : '' }}">
                <a href="{{ route('admin.card-approvals') }}">
                    <i class="fas fa-check-circle"></i> Card Approvals
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                <a href="{{ route('admin.analytics') }}">
                    <i class="fas fa-chart-pie"></i> Analytics
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                <a href="{{ route('admin.reports') }}">
                    <i class="fas fa-file-export"></i> Reports
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.notifications') ? 'active' : '' }}">
                <a href="{{ route('admin.notifications') }}">
                    <i class="fas fa-bullhorn"></i> Notifications
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.audit-logs') ? 'active' : '' }}">
                <a href="{{ route('admin.audit-logs') }}">
                    <i class="fas fa-clipboard-list"></i> Audit Logs
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                <a href="{{ route('admin.settings') }}">
                    <i class="fas fa-cog"></i> Settings
                </a>
            </li>
        @endauth
        
        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-link text-decoration-none w-100 text-start text-white">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </li>
    </ul>
</nav>