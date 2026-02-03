<div class="sidebar bg-primary text-white d-flex flex-column" id="sidebar">
    <div class="sidebar-header p-3 border-bottom border-secondary">
        <h4 class="mb-0">
            <i class="fas fa-building"></i> TDI Service
        </h4>
    </div>

    <nav class="nav flex-column p-3 flex-grow-1">
        @auth
            @if(Auth::user()->isAdmin())
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}"
                   class="nav-link text-white {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-gauge me-2"></i> Dashboard
                </a>

                <!-- Master Data Dropdown -->
                <div class="nav-item dropdown-group">
                    <a class="nav-link text-white d-flex justify-content-between align-items-center"
                       data-bs-toggle="collapse"
                       href="#masterDataMenu"
                       role="button"
                       aria-expanded="{{ request()->routeIs('users.*', 'divisions.*', 'job-roles.*') ? 'true' : 'false' }}"
                       aria-controls="masterDataMenu">
                        <span>
                            <i class="fas fa-database me-2"></i> Master Data
                        </span>
                        <i class="fas fa-chevron-down ms-auto"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('users.*', 'divisions.*', 'job-roles.*') ? 'show' : '' }}" id="masterDataMenu">
                        <nav class="nav flex-column ms-3">
                            <a href="{{ route('users.index') }}"
                               class="nav-link text-white {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                <i class="fas fa-users me-2"></i> Manajemen User
                            </a>
                            <a href="{{ route('divisions.index') }}"
                               class="nav-link text-white {{ request()->routeIs('divisions.*') ? 'active' : '' }}">
                                <i class="fas fa-sitemap me-2"></i> Divisi
                            </a>
                            <a href="{{ route('job-roles.index') }}"
                               class="nav-link text-white {{ request()->routeIs('job-roles.*') ? 'active' : '' }}">
                                <i class="fas fa-briefcase me-2"></i> Job Role
                            </a>
                        </nav>
                    </div>
                </div>

                <!-- Persetujuan Dropdown -->
                <div class="nav-item dropdown-group">
                    <a class="nav-link text-white d-flex justify-content-between align-items-center position-relative"
                       data-bs-toggle="collapse"
                       href="#approvalMenu"
                       role="button"
                       aria-expanded="false"
                       aria-controls="approvalMenu">
                        <span>
                            <i class="fas fa-check-circle me-2"></i> Persetujuan
                            @if(isset($pendingCount) && $pendingCount > 0)
                                <span class="position-absolute start-0 top-50 translate-middle-y badge rounded-pill bg-danger ms-1" style="font-size: 0.65rem;">
                                    {{ $pendingCount }}
                                </span>
                            @endif
                        </span>
                        <i class="fas fa-chevron-down ms-auto"></i>
                    </a>
                    <div class="collapse" id="approvalMenu">
                        <nav class="nav flex-column ms-3">
                            <a href="{{ route('users.approvals') }}"
                               class="nav-link text-white position-relative">
                                <i class="fas fa-user-check me-2"></i> Persetujuan User
                                @if(isset($pendingCount) && $pendingCount > 0)
                                    <span class="position-absolute start-100 top-50 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                                        {{ $pendingCount }}
                                    </span>
                                @endif
                            </a>
                            {{-- Persetujuan Presensi - REMOVED: No approval workflow --}}
                        </nav>
                    </div>
                </div>

                <!-- Presensi Management -->
                <div class="nav-item dropdown-group">
                    <a class="nav-link text-white d-flex justify-content-between align-items-center"
                       data-bs-toggle="collapse"
                       href="#attendanceMenu"
                       role="button"
                       aria-expanded="false"
                       aria-controls="attendanceMenu">
                        <span>
                            <i class="fas fa-calendar-check me-2"></i> Presensi
                        </span>
                        <i class="fas fa-chevron-down ms-auto"></i>
                    </a>
                    <div class="collapse" id="attendanceMenu">
                        <nav class="nav flex-column ms-3">
                            <a href="{{ route('admin.attendance.records') }}"
                               class="nav-link text-white {{ request()->routeIs('admin.attendance.records') ? 'active' : '' }}">
                                <i class="fas fa-list me-2"></i> Data Presensi
                            </a>
                            <a href="{{ route('admin.attendance.settings') }}"
                               class="nav-link text-white {{ request()->routeIs('admin.attendance.settings') ? 'active' : '' }}">
                                <i class="fas fa-sliders-h me-2"></i> Pengaturan
                            </a>
                        </nav>
                    </div>
                </div>

                <!-- Lainnya Dropdown -->
                <!-- Removed - Attendance menu moved to proper sections -->

            @else
                <!-- User Menu -->
                <a href="{{ route('home') }}"
                   class="nav-link text-white {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="fas fa-home me-2"></i> Beranda
                </a>

                <!-- Presensi User -->
                <div class="nav-item dropdown-group">
                    <a class="nav-link text-white d-flex justify-content-between align-items-center"
                       data-bs-toggle="collapse"
                       href="#userAttendanceMenu"
                       role="button"
                       aria-expanded="{{ request()->routeIs('attendance.*') ? 'true' : 'false' }}"
                       aria-controls="userAttendanceMenu">
                        <span>
                            <i class="fas fa-calendar-check me-2"></i> Presensi
                        </span>
                        <i class="fas fa-chevron-down ms-auto"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('attendance.*') ? 'show' : '' }}" id="userAttendanceMenu">
                        <nav class="nav flex-column ms-3">
                            <a href="{{ route('attendance.checkin') }}"
                               class="nav-link text-white {{ request()->routeIs('attendance.checkin') ? 'active' : '' }}">
                                <i class="fas fa-sign-in-alt me-2"></i> Check-in
                            </a>
                            <a href="{{ route('attendance.history') }}"
                               class="nav-link text-white {{ request()->routeIs('attendance.history') ? 'active' : '' }}">
                                <i class="fas fa-history me-2"></i> Riwayat
                            </a>
                        </nav>
                    </div>
                </div>
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
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i> Keluar
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

    .sidebar .dropdown-group .collapse {
        margin-top: 0.25rem;
    }

    .sidebar .dropdown-group .nav-link.text-white {
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
    }

    .sidebar .dropdown-group .nav-link.text-white:hover {
        background-color: rgba(255, 255, 255, 0.15);
    }

    .sidebar .dropdown-group .nav-link.text-white.active {
        background-color: rgba(255, 255, 255, 0.25);
        font-weight: 600;
        border-left: 3px solid #fff;
        padding-left: calc(1rem - 3px);
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
