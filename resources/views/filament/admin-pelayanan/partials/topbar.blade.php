<div class="pln-custom-topbar">
    <div class="pln-custom-topbar__inner">
        {{-- Kiri: Logo PLN --}}
        <div class="pln-custom-topbar__left">
            <div class="pln-custom-topbar__logo">
                <img src="{{ asset('images/pln-logo2.png') }}" alt="PLN Logo" class="pln-custom-topbar__logo-img">
            </div>
            <div class="pln-custom-topbar__brand">
                <span class="pln-custom-topbar__brand-text">Sistem Monitoring Layanan</span>
                <span class="pln-custom-topbar__brand-divider">|</span>
                <span class="pln-custom-topbar__brand-panel">Panel: Admin Layanan</span>
            </div>
        </div>

        {{-- Kanan: Bell + Avatar + Logout --}}
        <div class="pln-custom-topbar__right">
            {{-- Bell Notification --}}
            <div class="pln-custom-topbar__bell">
                <svg class="pln-custom-topbar__bell-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <span class="pln-custom-topbar__bell-badge">3</span>
            </div>

            {{-- Avatar Dropdown --}}
            <div class="pln-custom-topbar__user">
                <div class="pln-custom-topbar__avatar">
                    <svg class="pln-custom-topbar__avatar-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <span class="pln-custom-topbar__user-name">Admin</span>
            </div>

            {{-- Logout Button --}}
            <form method="POST" action="{{ route('filament.admin-pelayanan.auth.logout') }}" class="pln-custom-topbar__logout-form">
                @csrf
                <button type="submit" class="pln-custom-topbar__logout-btn">
                    <svg class="pln-custom-topbar__logout-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>
</div>
