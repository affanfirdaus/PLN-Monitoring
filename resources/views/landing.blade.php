<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PLN - Sistem Monitoring Pasang Baru & Tambah Daya</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="app" id="appRoot">
        <!-- HEADER / TOPBAR -->
        <header class="topbar">
            <div class="topbar-left">
                <div class="logo-wrapper">
                    <!-- LOGO FIXED -->
                    <img src="{{ asset('images/pln-logo.png') }}" alt="PLN Logo" class="logo-pln">
                </div>
                <!-- REMOVED EXTRA TEXT "PLN" NEXT TO LOGO to match clean look if needed, kept if user insists, but prompt said "logo pln itu tampilkan gambar... logo-pln.png", assumed replacing the old block or just fixing src. keeping structure clean. -->
                <div class="brand-pln">PLN</div>
                <div class="divider-vertical"></div>
                <h1 class="system-title">Sistem Monitoring <span style="font-weight:400; color:#6B7793;">Pasang Baru & Tambah Daya</span></h1>
            </div>

            <div class="topbar-right">
                <div class="lang-switcher">ID | EN</div>
                <div class="user-avatar">
                   <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                </div>
                <svg class="chevron-down" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#6B7793" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </div>
        </header>

        <!-- SIDEBAR -->
        <aside class="sidebar" id="sidebar">
            <nav class="sidebar-nav">
                <a href="#" class="menu-item active">
                    <div class="menu-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                    </div>
                    <span>Dashboard</span>
                </a>

                <a href="#" class="menu-item">
                    <div class="menu-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </div>
                    <span>Monitoring</span>
                </a>

                <a href="#" class="menu-item">
                    <div class="menu-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
                    </div>
                    <span>Pembayaran</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="headset-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 18v-6a9 9 0 0 1 18 0v6"></path><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"></path></svg>
                </div>
                <span>PLN Contact Center 123</span>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="content">
            <div class="content-bg">
                
                <!-- TOMBOL TOGGLE WRAPPER -->
                <div class="sidebar-toggle-wrapper">
                    <!-- Close Button (Saat sidebar open) -->
                    <button id="sidebarClose" class="sidebar-fab sidebar-fab-close" type="button">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    </button>
                    <!-- Open Button (Saat sidebar closed) -->
                    <button id="sidebarOpen" class="sidebar-fab sidebar-fab-open" type="button">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </button>
                </div>

                <!-- LEFT AREA (Welcome + Services) -->
                <div class="left-section">
                    <h2 class="welcome-text">Selamat datang, silahkan login</h2>
                    
                    <!-- GLASS CONTAINER: PERMOHONAN LAYANAN -->
                    <div class="glass-container service-glass-panel">
                        <h3 class="glass-title service-heading-red">Permohonan pelayanan</h3>
                        <div class="service-grid">
                            <!-- Card 1 -->
                            <div class="service-card large-card">
                                <div class="service-icon-circle">
                                     <img src="{{ asset('images/icon-tambah-daya.png') }}" alt="Icon" onerror="this.style.display='none'"> 
                                </div>
                                <div class="service-info">
                                    <div class="s-title">Tambah Daya</div>
                                    <div class="s-sub">Tambah Daya</div>
                                </div>
                            </div>
                            <!-- Card 2 -->
                            <div class="service-card large-card">
                                <div class="service-icon-circle">
                                     <img src="{{ asset('images/icon-pasang-baru.png') }}" alt="Icon" onerror="this.style.display='none'"> 
                                </div>
                                <div class="service-info">
                                    <div class="s-title">Pasang Baru</div>
                                    <div class="s-sub">Pasang Baru</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT AREA (Login Cards) -->
                <div class="right-section-fixed">
                    <!-- GLASS CONTAINER: LOGIN -->
                    <div class="glass-container actor-glass-panel">
                        <h3 class="glass-title login-heading-orange">Login sebagai apa?</h3>
                        <div class="login-grid">
                            <!-- Card Pelanggan -->
                            <div class="login-card">
                                <div class="lc-header">Login sebagai Pelanggan</div>
                                <div class="lc-illustration">
                                    <img src="{{ asset('images/ill-pelanggan.png') }}" alt="Pelanggan" onerror="this.style.display='none'">
                                </div>
                                <a href="/pelanggan/login" class="btn-login btn-blue">Masuk Pelanggan</a>
                            </div>
                            
                            <!-- Card Pegawai -->
                            <div class="login-card">
                                <div class="lc-header">Login sebagai Pegawai</div>
                                <div class="lc-illustration">
                                    <img src="{{ asset('images/ill-pegawai.png') }}" alt="Pegawai" onerror="this.style.display='none'">
                                </div>
                                <a href="/pegawai/login" class="btn-login btn-orange">Masuk Pegawai</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</body>
</html>