<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PLN UP3 Kudus - Sistem Monitoring</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <meta name="is-auth" content="{{ Auth::check() ? '1' : '0' }}">
    <meta name="auth-role" content="{{ Auth::check() ? Auth::user()->role : '' }}">
    <style>
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        .animate-shake {
            animation: shake 0.5s ease-in-out;
        }
        .animate-fade-in-down {
            animation: fadeInDown 0.5s ease-out;
        }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="font-sans text-slate-800 antialiased bg-white selection:bg-blue-100 selection:text-blue-900">

    <!-- A. TOPBAR (Fixed Header) -->
    <header class="fixed top-0 inset-x-0 h-20 bg-white border-b border-slate-100 z-50 flex items-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07)]">
        <!-- Changed to w-full px-4 for left alignment -->
        <div class="w-full px-4 md:px-6 flex items-center h-full">
            
            <!-- Left Group: Logo + Nav -->
            <div class="flex items-center gap-8 md:gap-10">
                <!-- Logo + App Name -->
                <div class="flex items-center gap-3">
                    <div class="h-10 w-auto">
                        <img src="{{ asset('images/pln-logo2.png') }}" alt="PLN Logo" class="h-full object-contain">
                    </div>
                    <div class="flex flex-col leading-none">
                        <span class="font-bold text-lg tracking-tight" style="color: #0099ff;">PLN</span>
                        <span class="text-yellow-600 font-bold text-base tracking-wide">UP3 KUDUS</span>
                    </div>
                </div>

                <!-- Navigation Menu (Desktop) -->
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('landing') }}" class="text-[#2F5AA8] font-semibold border-b-2 border-[#2F5AA8] pb-1">Dashboard</a>
                    <a href="#" onclick="handleProtectedLink(event)" data-route="monitoring" class="nav-protected text-slate-600 font-medium hover:text-[#2F5AA8] transition-colors">Monitoring</a>
                    <a href="#" onclick="handleProtectedLink(event)" data-route="pembayaran" class="nav-protected text-slate-600 font-medium hover:text-[#2F5AA8] transition-colors">Pembayaran</a>
                </nav>
            </div>

            <!-- Right Group: User/Profile (Pushed to right) -->
            <!-- Right Group: User/Profile (Pushed to right) -->
            <div class="ml-auto flex items-center gap-3 relative">
                @guest
                    <!-- Guest: Login Dropdown (Icon Only) -->
                    <div id="guestDropdownToggle" class="flex items-center gap-2 cursor-pointer hover:bg-slate-50 p-2 rounded-full transition-colors border border-transparent hover:border-slate-100">
                        <div class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 border border-slate-200 shadow-sm">
                            <i class="fas fa-user text-sm"></i>
                        </div>
                        <i class="fas fa-chevron-down text-xs text-slate-400"></i>
                    </div>

                    <!-- Dropdown Menu Guest -->
                    <div id="guestDropdownMenu" class="hidden absolute top-full right-0 mt-2 w-56 bg-white border border-slate-100 rounded-xl shadow-[0_4px_20px_-5px_rgba(0,0,0,0.1)] p-1 z-50">
                        <div class="px-4 py-3 border-b border-slate-50">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Akses Akun</p>
                        </div>
                        <a href="{{ route('pelanggan.login') }}" class="block px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-[#2F5AA8] rounded-lg transition-colors">
                            <i class="fas fa-users mr-3 text-slate-400"></i> Login Pelanggan
                        </a>
                        <a href="{{ route('pegawai.login') }}" class="block px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-[#2F5AA8] rounded-lg transition-colors">
                            <i class="fas fa-user-tie mr-3 text-slate-400"></i> Login Pegawai
                        </a>
                        <div class="border-t border-slate-50 my-1"></div>
                        <a href="{{ route('pelanggan.register') }}" class="block px-4 py-2.5 text-sm text-[#2F5AA8] hover:bg-blue-50 font-semibold rounded-lg transition-colors">
                            Daftar Pelanggan
                        </a>
                    </div>
                @else
                    @if(Auth::user()->role === 'pelanggan')
                        <!-- Authenticated Pelanggan -->
                        <div id="userDropdownToggle" class="flex items-center gap-3 cursor-pointer hover:bg-slate-50 p-2 rounded-full transition-colors border border-transparent hover:border-slate-100">
                            <!-- Name -->
                            <span class="hidden sm:block text-sm font-semibold text-slate-700">{{ Auth::user()->name }}</span>
                            
                            <!-- Avatar -->
                            <div class="w-9 h-9 rounded-full bg-slate-200 overflow-hidden flex items-center justify-center text-slate-500 border border-slate-200">
                                @if(Auth::user()->profile_photo)
                                    <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="Avatar" class="w-full h-full object-cover">
                                @else
                                    <span class="text-xs font-bold">{{ substr(Auth::user()->name, 0, 2) }}</span>
                                @endif
                            </div>
                            
                            <!-- Chevron -->
                            <i class="fas fa-chevron-down text-xs text-slate-400"></i>
                        </div>

                        <!-- Dropdown Menu -->
                        <div id="userDropdownMenu" class="hidden absolute top-full right-0 mt-2 w-56 bg-white border border-slate-100 rounded-xl shadow-[0_4px_20px_-5px_rgba(0,0,0,0.1)] p-1 z-50">
                            <div class="px-4 py-3 border-b border-slate-50">
                                <p class="text-sm font-bold text-slate-800">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('pelanggan.profile') }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-[#2F5AA8] rounded-lg transition-colors">
                                <i class="fas fa-user-circle mr-2"></i> Lihat Profil Saya
                            </a>
                            <form action="{{ route('pelanggan.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- Authenticated Pegawai or Other Rules - Keep Default/Minimal -->
                        <div class="flex items-center gap-2 cursor-pointer hover:bg-slate-50 p-2 rounded-lg transition-colors">
                            <div class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 border border-slate-200">
                                <i class="fas fa-user text-sm"></i>
                            </div>
                        </div>
                    @endif
                @endguest
            </div>
        </div>
    </header>

    <!-- Main Content Wrapper to prevent content hiding behind fixed header -->
    <main class="pt-20">
        
        <!-- B. HERO SECTION -->
        <section class="relative w-full overflow-hidden bg-slate-50 border-b border-slate-200 min-h-[360px] bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset('images/Hero-section.png') }}');">
            
            <!-- White Gradient Overlay for Text Readability -->
            <div class="absolute inset-0 bg-gradient-to-r from-white/95 via-white/70 to-white/10 z-0 backdrop-blur-[1px]"></div>
            
            <!-- Container: max-w-[1400px] + px-4 for left bias -->
            <div class="max-w-[1400px] mx-auto px-4 md:px-6 py-16 relative z-10">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
                    
                    <!-- Left: Text Content -->
                    <div class="flex flex-col items-start text-left space-y-6 max-w-xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-100/80 border border-blue-200 text-blue-800 text-xs font-bold uppercase tracking-wider backdrop-blur-sm">
                            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                            Sistem Monitoring Layanan
                        </div>
                        <h1 class="text-4xl md:text-5xl lg:text-5xl font-extrabold text-slate-900 leading-tight">
                            Layanan Listrik <br>
                            <span class="text-[#2F5AA8]">Mudah & Cepat</span>
                        </h1>
                        <p class="text-lg text-slate-700 font-medium max-w-lg leading-relaxed">
                            Pantau proses pasang baru dan tambah daya listrik Anda secara realtime, transparan, dan terpercaya di PLN UP3 Kudus.
                        </p>
                        
                        <!-- Buttons -->
                        <!-- Buttons -->
                        <!-- Buttons: Logic Updated Based on Role -->
                        @if(Auth::guest())
                        <div id="heroLoginActions" class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto pt-4">
                            <!-- Login Pegawai (Primary) -->
                            <a id="btnLoginPegawai" href="{{ route('pegawai.login') }}" class="inline-flex justify-center items-center px-8 py-3.5 rounded-xl bg-[#2F5AA8] text-white font-semibold text-sm hover:bg-[#274C8E] transition-all shadow-lg shadow-blue-900/20 hover:shadow-blue-900/30 focus:ring-4 focus:ring-blue-100">
                                Login Pegawai
                            </a>
                            <!-- Login Pelanggan (Secondary) -->
                            <a id="btnLoginPelanggan" href="{{ route('pelanggan.login') }}" class="inline-flex justify-center items-center px-8 py-3.5 rounded-xl bg-white border border-slate-200 text-slate-700 font-semibold text-sm hover:bg-slate-50 transition-all shadow-sm hover:border-slate-300 focus:ring-4 focus:ring-slate-100">
                                Login Pelanggan
                            </a>
                        </div>
                        @else
                        <!-- User is Logged In -->
                        <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto pt-4">
                            @if(Auth::user()->role === 'pelanggan')
                                <a href="{{ route('pelanggan.dashboard') }}" class="inline-flex justify-center items-center px-8 py-3.5 rounded-xl bg-[#2F5AA8] text-white font-semibold text-sm hover:bg-[#274C8E] transition-all shadow-lg shadow-blue-900/20">
                                    <i class="fas fa-columns mr-2"></i> Ke Dashboard Pelanggan
                                </a>
                            @else
                                <!-- Only Show Pegawai Dashboard Button if Role is Internal -->
                                @php
                                    $roleConfig = config('internal_roles');
                                    $dashboardLink = isset($roleConfig[Auth::user()->role]) ? $roleConfig[Auth::user()->role]['path'] : '/internal/dashboard';
                                @endphp
                                <a href="{{ url($dashboardLink) }}" class="inline-flex justify-center items-center px-8 py-3.5 rounded-xl bg-slate-800 text-white font-semibold text-sm hover:bg-slate-700 transition-all shadow-lg">
                                    <i class="fas fa-user-shield mr-2"></i> Ke Dashboard Pegawai
                                </a>
                            @endif
                        </div>
                        <div class="mt-2 text-sm text-slate-500 font-medium">
                            Anda sedang login sebagai <span class="text-slate-800 font-bold">{{ Auth::user()->name }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- Right Column: Slider Card -->
                    <div class="w-full max-w-lg ml-auto md:mr-10 relative z-20">
                        <div class="bg-white/60 backdrop-blur-md rounded-2xl border border-white/60 shadow-lg p-3">
                            <!-- Slider Window -->
                            <div class="relative w-full aspect-[16/9] overflow-hidden rounded-xl bg-slate-200">
                                <!-- Track -->
                                <div id="heroSlideTrack" class="flex w-full h-full transition-transform duration-700 ease-in-out">
                                    <!-- Slide 1 -->
                                    <div class="w-full h-full flex-shrink-0 relative">
                                        <img src="{{ asset('images/pln.png') }}" class="w-full h-full object-cover" alt="PLN Slide 1" onerror="this.src='{{ asset('images/pln.png') }}'">
                                    </div>
                                    <!-- Slide 2 -->
                                    <div class="w-full h-full flex-shrink-0 relative">
                                        <img src="{{ asset('images/pln-1.png') }}" class="w-full h-full object-cover" alt="PLN Slide 2" onerror="this.src='{{ asset('images/pln.png') }}'">
                                    </div>
                                    <!-- Slide 3 -->
                                    <div class="w-full h-full flex-shrink-0 relative">
                                        <img src="{{ asset('images/pln-2.png') }}" class="w-full h-full object-cover" alt="PLN Slide 3" onerror="this.src='{{ asset('images/pln.png') }}'">
                                    </div>
                                </div>
                            </div>
                            <!-- Dots -->
                            <div class="flex items-center justify-center gap-2 mt-4 mb-1">
                                <div class="slide-dot w-6 h-1.5 rounded-full bg-blue-600 transition-all duration-300"></div>
                                <div class="slide-dot w-1.5 h-1.5 rounded-full bg-slate-300 transition-all duration-300"></div>
                                <div class="slide-dot w-1.5 h-1.5 rounded-full bg-slate-300 transition-all duration-300"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- C. SECTION PERMOHONAN LAYANAN -->
        <section id="permohonanLayanan" class="bg-white py-20 border-t border-slate-50">
            <!-- Adjusted container: max-w-[1400px] + px-4 -->
            <div class="max-w-[1400px] mx-auto px-4 md:px-6">
                
                <!-- Section Title -->
                <div class="mb-10 text-left">
                    <h2 class="text-3xl font-bold text-slate-900 mb-2">Permohonan Layanan</h2>
                    <p class="text-slate-500">Pilih jenis layanan kelistrikan yang Anda butuhkan</p>
                </div>

                <!-- Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <!-- Card 1: Tambah Daya -->
                    <div class="group bg-white rounded-2xl border border-slate-200 p-8 shadow-sm hover:shadow-xl hover:shadow-blue-900/5 hover:border-blue-100 transition-all duration-300 relative overflow-hidden">
                        <!-- Hover Decoration -->
                        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-bl-full -mr-8 -mt-8 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        
                        <div class="relative z-10 flex flex-col h-full">
                            <!-- Icon Bubble -->
                            <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center text-[#2F5AA8] mb-6 group-hover:scale-110 transition-transform">
                                <i class="fas fa-bolt text-2xl"></i>
                            </div>
                            
                            <!-- Content -->
                            <div class="flex-grow">
                                <h3 class="text-xl font-bold text-slate-900 mb-2">Tambah Daya</h3>
                                <p class="text-slate-500 text-sm leading-relaxed mb-8">
                                    Layanan untuk mengajukan peningkatan daya listrik di rumah atau tempat usaha Anda agar lebih produktif.
                                </p>
                            </div>

                            <!-- Button -->
                            <a href="{{ route('layanan.tambah-daya.info') }}" class="w-full py-3.5 px-4 bg-[#2F5AA8] text-white rounded-xl font-semibold text-sm hover:bg-[#274C8E] transition-colors shadow-sm flex items-center justify-center gap-2 group-hover:gap-3">
                                <span>Ajukan Tambah Daya</span>
                                <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Card 2: Pasang Baru -->
                    <div class="group bg-white rounded-2xl border border-slate-200 p-8 shadow-sm hover:shadow-xl hover:shadow-blue-900/5 hover:border-blue-100 transition-all duration-300 relative overflow-hidden">
                         <!-- Hover Decoration -->
                         <div class="absolute top-0 right-0 w-32 h-32 bg-orange-50 rounded-bl-full -mr-8 -mt-8 opacity-0 group-hover:opacity-100 transition-opacity"></div>

                        <div class="relative z-10 flex flex-col h-full">
                            <!-- Icon Bubble -->
                            <div class="w-14 h-14 rounded-2xl bg-orange-50 flex items-center justify-center text-orange-600 mb-6 group-hover:scale-110 transition-transform">
                                <i class="fas fa-home text-2xl"></i>
                            </div>
                            
                            <!-- Content -->
                            <div class="flex-grow">
                                <h3 class="text-xl font-bold text-slate-900 mb-2">Pasang Baru</h3>
                                <p class="text-slate-500 text-sm leading-relaxed mb-8">
                                    Layanan pemasangan sambungan listrik baru untuk bangunan rumah tinggal atau bisnis dengan mudah.
                                </p>
                            </div>

                            <!-- Button -->
                            <a href="{{ route('layanan.pasang-baru.info') }}" class="w-full py-3.5 px-4 bg-[#2F5AA8] text-white rounded-xl font-semibold text-sm hover:bg-[#274C8E] transition-colors shadow-sm flex items-center justify-center gap-2 group-hover:gap-3">
                                <span>Ajukan Pasang Baru</span>
                                <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main>

    <!-- Optional Footer -->
    <footer class="bg-white border-t border-slate-100 py-8">
        <div class="container max-w-7xl mx-auto px-6 md:px-8 text-center md:text-left flex flex-col md:flex-row justify-between items-center text-slate-500 text-sm">
            <p>&copy; 2026 PT PLN (Persero) UP3 Kudus. All rights reserved.</p>
            <div class="flex gap-6 mt-4 md:mt-0 font-medium text-slate-400">
                <a href="#" class="hover:text-slate-600">Privacy Policy</a>
                <a href="#" class="hover:text-slate-600">Terms of Service</a>
            </div>
        </div>
    </footer>

    <!-- Toast Container (Default Hidden) -->
    <div id="toastLoginRequired" class="hidden fixed top-24 right-4 z-50 max-w-sm animate-fade-in-down">
        <div class="rounded-xl border border-red-100 border-l-4 border-l-red-500 bg-white shadow-xl px-4 py-3 flex gap-3 items-start">
            <div class="mt-0.5 text-red-500"><i class="fas fa-exclamation-circle"></i></div>
            <div class="text-sm text-slate-700 font-medium">
                Login terlebih dahulu untuk mengakses menu ini.
            </div>
        </div>
    </div>

    <!-- Debugging Script for User Diagnosis -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            console.log('Landing Page Loaded');
            ['btnLoginPegawai', 'btnLoginPelanggan'].forEach(id => {
                const el = document.getElementById(id);
                if(el) {
                    console.log(`Found button: ${id} -> href: ${el.getAttribute('href')}`);
                    el.addEventListener('click', (e) => {
                        console.log(`CLICKED ${id}`);
                        // e.preventDefault(); // Uncomment to test blocking locally
                    });
                }
            });
        });
    </script>
    <script>
        window.__NEED_LOGIN__ = {{ request('need_login') == 1 ? 'true' : 'false' }};
    </script>

    <!-- Custom Scripts for Guest Handling -->
    <script>
        function handleProtectedLink(event) {
            event.preventDefault();
            showToast();
            shakeLoginButtons();
        }

        function showToast() {
            const toast = document.getElementById('toastLoginRequired');
            toast.classList.remove('hidden');
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 4000);
        }

        function shakeLoginButtons() {
            const btnPegawai = document.getElementById('btnLoginPegawai');
            const btnPelanggan = document.getElementById('btnLoginPelanggan');
            
            [btnPegawai, btnPelanggan].forEach(btn => {
                if (btn) {
                    btn.classList.add('animate-shake');
                    setTimeout(() => {
                        btn.classList.remove('animate-shake');
                    }, 1000);
                }
            });
        }

        // Show toast if redirected with need_login
        if (window.__NEED_LOGIN__) {
            document.addEventListener('DOMContentLoaded', () => {
                showToast();
                shakeLoginButtons();
            });
        }
    </script>
</body>
</html>