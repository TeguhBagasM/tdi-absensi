<div class="sidebar bg-dark text-white d-flex flex-column" id="sidebar">
    <div class="sidebar-header p-3 border-bottom border-secondary">
        <h4 class="mb-0">
            <i class="fas fa-building"></i> TDI Service
        </h4>
    </div>

    <nav class="nav flex-column p-3 flex-grow-1">
        @auth
            @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}"
                   class="nav-link text-white {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-gauge me-2"></i> Dashboard
                </a>
                <a href="{{ route('users.index') }}"
                   class="nav-link text-white {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="fas fa-users me-2"></i> Users
                </a>
                <a href="#" class="nav-link text-white">
                    <i class="fas fa-calendar-check me-2"></i> Attendance
                </a>
                <a href="#" class="nav-link text-white">
                    <i class="fas fa-file-pdf me-2"></i> Reports
                </a>
            @else
                <a href="{{ route('home') }}"
                   class="nav-link text-white {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="fas fa-home me-2"></i> Home
                </a>
                <a href="#" class="nav-link text-white">
                    <i class="fas fa-calendar-check me-2"></i> My Attendance
                </a>
            @endif
        @endauth
    </nav>

    <div class="sidebar-footer p-3 border-top border-secondary">
        @auth
            <div class="dropdown dropup">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                   id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle me-2 fs-5"></i>
                    <span>{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        @endauth
    </div>
</div>

<style>
    .sidebar {
        width: 250px;
        min-height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
        transition: all 0.3s;
    }

    .sidebar .nav-link {
        padding: 0.75rem 1rem;
        border-radius: 0.25rem;
        margin-bottom: 0.25rem;
        transition: all 0.2s;
    }

    .sidebar .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }

    .sidebar .nav-link.active {
        background-color: #0d6efd;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .sidebar {
            margin-left: -250px;
        }

        .sidebar.show {
            margin-left: 0;
        }

        .main-content {
            margin-left: 0 !important;
        }
    }
</style>
