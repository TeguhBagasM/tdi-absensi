<div class="topbar bg-white border-bottom p-3 mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <button class="btn btn-link d-md-none" id="sidebarToggle">
                <i class="fas fa-bars fs-4"></i>
            </button>
            <h5 class="d-inline-block mb-0 ms-2">@yield('page-title', 'Dashboard')</h5>
        </div>
        <div class="d-flex align-items-center gap-3">
            {{-- <span class="text-muted small">{{ now()->format('d M Y') }}</span> --}}
            @auth
                <span style="background: transparent; color: {{ Auth::user()->isAdmin() ? '#ef4444' : '#3b82f6' }}; padding: 4px 8px; border-radius: 4px; font-size: 0.875rem; font-weight: 500; border: 1px solid {{ Auth::user()->isAdmin() ? '#fecaca' : '#bfdbfe' }};">
                    {{ str_replace('_', ' ', Auth::user()->role->name ?? 'User') }}
                </span>
            @endauth
        </div>
    </div>
</div>
