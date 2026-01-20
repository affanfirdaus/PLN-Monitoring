<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Sistem Monitoring PLN' }}</title>

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body>
<div class="app" id="appRoot">

    <!-- TOPBAR -->
    <header class="topbar">
        <div class="topbar-left">
            <button id="sidebarToggle" class="sidebar-toggle" type="button" aria-label="Toggle sidebar">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>

            <img class="logo-pln" src="{{ asset('images/pln-logo2.png') }}" alt="PLN">
            <div class="brand-pln">PLN</div>
            <div class="system-title">Sistem Monitoring Pasang Baru & Tambah Daya</div>
        </div>

        <div class="topbar-right">
            <div class="lang-switcher">ID | EN</div>
            <div style="display:flex;align-items:center;">
                <div class="user-avatar">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none">
                        <path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Zm0 2c-4.4 0-8 2.2-8 5v1h16v-1c0-2.8-3.6-5-8-5Z" fill="currentColor"/>
                    </svg>
                </div>
                <div class="chevron-down">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none">
                        <path d="m6 9 6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>
        </div>
    </header>

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <nav class="sidebar-nav">
            <a class="menu-item active" href="#">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M3 10.5 12 3l9 7.5V21a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1V10.5Z"
                          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <a class="menu-item" href="#">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M21 21l-4.3-4.3M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z"
                          stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <span>Monitoring</span>
            </a>

            <a class="menu-item" href="#">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M3 7h18M3 11h18M7 15h10M5 21h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2Z"
                          stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <span>Pembayaran</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <svg viewBox="0 0 24 24" fill="none">
                <path d="M4 12a8 8 0 0 1 16 0v6a2 2 0 0 1-2 2h-1v-6h3"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M4 18h3v-6H4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span>PLN Contact Center 123</span>
        </div>
    </aside>

    <!-- MAIN -->
    <main class="content">
        <div class="content-bg">
            <div class="content-panel">
                @yield('content')
            </div>
        </div>
    </main>

</div>

<!-- JS toggle (kalau lu belum pake resources/js/app.js) -->
<script>
(function () {
  const app = document.getElementById('appRoot');
  const btn = document.getElementById('sidebarToggle');
  if (!app || !btn) return;

  const saved = localStorage.getItem('sidebarCollapsed');
  if (saved === '1') app.classList.add('sidebar-collapsed');

  btn.addEventListener('click', function () {
    app.classList.toggle('sidebar-collapsed');
    localStorage.setItem('sidebarCollapsed', app.classList.contains('sidebar-collapsed') ? '1' : '0');
  });
})();
</script>

</body>
</html>
