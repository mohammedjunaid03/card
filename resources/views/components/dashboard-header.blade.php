<div class="dashboard-header">
    <div class="d-flex align-items-center">
        <button type="button" id="sidebarToggle" class="btn btn-outline-secondary me-3">
            <i class="fas fa-bars"></i>
        </button>
        <h4 class="mb-0">
            @yield('title', 'Dashboard')
        </h4>
    </div>
    
    <div class="d-flex align-items-center">
        @auth('web')
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    @if(auth()->user()->photo_path)
                        <img src="{{ asset('storage/' . auth()->user()->photo_path) }}" 
                             class="rounded-circle" 
                             style="width: 30px; height: 30px; object-fit: cover;"
                             alt="Profile">
                    @else
                        <i class="fas fa-user-circle fa-lg"></i>
                    @endif
                    {{ auth()->user()->name }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('user.profile') }}">Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        @endauth
        
        @auth('hospital')
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-hospital fa-lg"></i>
                    {{ auth()->guard('hospital')->user()->name }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('hospital.profile') }}">Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        @endauth
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');
    
    // Check if sidebar state is stored in localStorage
    const sidebarHidden = localStorage.getItem('sidebarHidden') === 'true';
    
    if (sidebarHidden) {
        sidebar.classList.add('hidden');
        content.classList.add('full-width');
        sidebarToggle.innerHTML = '<i class="fas fa-bars"></i>';
    }
    
    sidebarToggle.addEventListener('click', function() {
        const isHidden = sidebar.classList.contains('hidden');
        
        if (isHidden) {
            // Show sidebar
            sidebar.classList.remove('hidden');
            content.classList.remove('full-width');
            sidebarToggle.innerHTML = '<i class="fas fa-bars"></i>';
            localStorage.setItem('sidebarHidden', 'false');
        } else {
            // Hide sidebar
            sidebar.classList.add('hidden');
            content.classList.add('full-width');
            sidebarToggle.innerHTML = '<i class="fas fa-bars"></i>';
            localStorage.setItem('sidebarHidden', 'true');
        }
    });
});
</script>
@endpush