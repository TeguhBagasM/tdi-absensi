<div class="topbar bg-white border-bottom p-3 mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <button class="btn btn-link d-md-none" id="sidebarToggle">
                <i class="fas fa-bars fs-4"></i>
            </button>
            <h5 class="d-inline-block mb-0 ms-2">@yield('page-title', 'Dashboard')</h5>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="text-muted small">{{ now()->format('d M Y') }}</span>
            @auth
                <span class="badge bg-{{ Auth::user()->isAdmin() ? 'danger' : 'primary' }}">
                    {{ str_replace('_', ' ', Auth::user()->role->name ?? 'User') }}
                </span>
            @endauth
        </div>
    </div>
</div>
