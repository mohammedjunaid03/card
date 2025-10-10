<nav class="navbar navbar-expand-lg modern-navbar shadow-sm fixed-top">
    <div class="container-fluid px-3 px-md-4">
        <!-- Brand -->
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <img src="{{ asset('logo.png') }}" alt="KCC HealthCard" height="38" class="navbar-logo me-2">
            <span class="brand-text fw-bold">HealthCard</span>
        </a>

        <!-- Toggler -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <i class="fas fa-bars text-primary fs-4"></i>
        </button>

        <!-- Nav Links -->
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav align-items-lg-center">
                <li class="nav-item"><a class="nav-link modern-nav-link" href="{{ route('home') }}"><i class="fas fa-home me-1"></i>Home</a></li>
                <li class="nav-item"><a class="nav-link modern-nav-link" href="{{ route('about') }}"><i class="fas fa-info-circle me-1"></i>About</a></li>
                <li class="nav-item"><a class="nav-link modern-nav-link" href="{{ route('how-it-works') }}"><i class="fas fa-cogs me-1"></i>How It Works</a></li>
                <li class="nav-item"><a class="nav-link modern-nav-link" href="{{ route('hospital-network') }}"><i class="fas fa-hospital me-1"></i>Hospitals</a></li>
                <li class="nav-item"><a class="nav-link modern-nav-link" href="{{ route('contact') }}"><i class="fas fa-envelope me-1"></i>Contact</a></li>

                @guest
                    <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                        <a class="btn modern-cta-btn px-3 py-2 fw-semibold" href="{{ route('register') }}">
                            <i class="fas fa-id-card me-2"></i>Get Health Card
                        </a>
                    </li>
                @else
                    <li class="nav-item dropdown ms-lg-3 mt-2 mt-lg-0">
                        <a class="nav-link dropdown-toggle modern-nav-link d-flex align-items-center" href="#" id="userDropdown" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-2 fs-5"></i>{{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu modern-dropdown shadow-sm border-0">
                            <li><a class="dropdown-item" href="{{ route('user.dashboard') }}"><i class="fas fa-tachometer-alt me-2 text-primary"></i>Dashboard</a></li>
                            <li><a class="dropdown-item" href="{{ route('user.profile') }}"><i class="fas fa-user-edit me-2 text-primary"></i>Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger fw-semibold">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
