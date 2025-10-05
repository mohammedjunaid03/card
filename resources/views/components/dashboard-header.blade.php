<div class="dashboard-header">
    <div>
        <button type="button" id="sidebarCollapse" class="btn btn-primary">
            <i class="fas fa-bars"></i>
        </button>
        <span class="ms-3 fs-5">
            @yield('title', 'Dashboard')
        </span>
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
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });
</script>
@endpush