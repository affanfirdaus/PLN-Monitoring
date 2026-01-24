<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pegawai - PLN UP3 Kudus</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="font-sans text-slate-800 antialiased bg-white selection:bg-blue-100 selection:text-blue-900">

    <!-- A. TOPBAR (Fixed Header) -->
    <header class="fixed top-0 inset-x-0 h-20 bg-white border-b border-slate-100 z-50 flex items-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07)]">
        <div class="w-full px-4 md:px-6 flex items-center h-full">
            <div class="flex items-center gap-8 md:gap-10">
                <!-- Logo -->
                <a href="{{ route('landing') }}" class="flex items-center gap-3">
                    <div class="h-10 w-auto">
                        <img src="{{ asset('images/pln-logo2.png') }}" alt="PLN Logo" class="h-full object-contain">
                    </div>
                    <div class="flex flex-col leading-none">
                        <span class="font-bold text-lg tracking-tight" style="color: #0099ff;">PLN</span>
                        <span class="text-yellow-600 font-bold text-base tracking-wide">UP3 KUDUS</span>
                    </div>
                </a>

                <!-- Navigation Menu (Consistently Shown) -->
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="#" class="text-slate-600 font-medium hover:text-[#2F5AA8] transition-colors">Dashboard</a>
                    <a href="#" class="text-slate-600 font-medium hover:text-[#2F5AA8] transition-colors">Monitoring</a>
                    <a href="#" class="text-slate-600 font-medium hover:text-[#2F5AA8] transition-colors">Pembayaran</a>
                </nav>
            </div>
        </div>
    </header>

    <main class="pt-20">
        <!-- Hero Section (Clean) -->
        <section class="relative w-full overflow-hidden bg-slate-50 border-b border-slate-200 min-h-[80vh] bg-cover bg-center bg-no-repeat flex items-center" style="background-image: url('{{ asset('images/Hero-section.png') }}');">
            <!-- Overlay -->
            <div class="absolute inset-0 bg-gradient-to-r from-white/70 via-white/40 to-white/10 z-0 backdrop-blur-[1px]"></div>
            
            <!-- Back Button inside Hero -->
            <div class="absolute top-8 left-4 md:left-8 z-20">
                <a href="{{ route('landing') }}" class="flex items-center gap-2 px-4 py-2 rounded-full bg-white/70 backdrop-blur border border-white/60 shadow-sm hover:bg-white/90 transition text-slate-700 font-medium text-sm">
                    <i class="fas fa-arrow-left text-xs"></i>
                    Kembali
                </a>
            </div>
        </section>

        <!-- Content Area -->
        <section class="max-w-[1200px] mx-auto px-4 py-10 relative">
            
            <!-- Title Section (Moved below Hero) -->
            <div class="flex flex-col items-start mb-6">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-100/80 border border-blue-200 text-blue-800 text-xs font-bold uppercase tracking-wider backdrop-blur-sm mb-3">
                    <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                    Login Area
                </div>
                <h2 class="text-3xl md:text-3xl font-extrabold text-slate-900 leading-tight">
                    Login sebagai <span class="text-[#2F5AA8]">pegawai unit ?</span>
                </h2>
            </div>

            <!-- Grid Card Aktor -->
            <!-- Row 1: 5 Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                <!-- Admin Pelayanan -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl hover:shadow-blue-900/5 hover:border-blue-100 transition-all duration-300 overflow-hidden group">
                    <div class="h-32 bg-blue-50 flex items-center justify-center group-hover:bg-blue-100 transition-colors">
                        <i class="fas fa-headset text-4xl text-blue-300 group-hover:text-blue-500 transition-colors"></i>
                    </div>
                    <div class="p-5 text-center">
                        <h3 class="text-lg font-bold text-slate-800 mb-4">Admin Pelayanan</h3>
                        <a href="#" class="block w-full rounded-xl bg-[#2F5AA8] text-white py-2.5 font-semibold hover:bg-[#274C8E] hover:shadow-lg hover:-translate-y-0.5 transition-all shadow-md shadow-blue-900/10">Login</a>
                    </div>
                </div>

                <!-- Unit Survey -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl hover:shadow-green-900/5 hover:border-green-100 transition-all duration-300 overflow-hidden group">
                    <div class="h-32 bg-green-50 flex items-center justify-center group-hover:bg-green-100 transition-colors">
                        <i class="fas fa-map-marked-alt text-4xl text-green-300 group-hover:text-green-500 transition-colors"></i>
                    </div>
                    <div class="p-5 text-center">
                        <h3 class="text-lg font-bold text-slate-800 mb-4">Unit Survey</h3>
                        <a href="#" class="block w-full rounded-xl bg-[#2F5AA8] text-white py-2.5 font-semibold hover:bg-[#274C8E] hover:shadow-lg hover:-translate-y-0.5 transition-all shadow-md shadow-blue-900/10">Login</a>
                    </div>
                </div>

                <!-- Unit Perencanaan -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl hover:shadow-yellow-900/5 hover:border-yellow-100 transition-all duration-300 overflow-hidden group">
                    <div class="h-32 bg-yellow-50 flex items-center justify-center group-hover:bg-yellow-100 transition-colors">
                        <i class="fas fa-pencil-ruler text-4xl text-yellow-300 group-hover:text-yellow-500 transition-colors"></i>
                    </div>
                    <div class="p-5 text-center">
                        <h3 class="text-lg font-bold text-slate-800 mb-4">Unit Perencanaan</h3>
                        <a href="#" class="block w-full rounded-xl bg-[#2F5AA8] text-white py-2.5 font-semibold hover:bg-[#274C8E] hover:shadow-lg hover:-translate-y-0.5 transition-all shadow-md shadow-blue-900/10">Login</a>
                    </div>
                </div>

                <!-- Unit Konstruksi -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl hover:shadow-orange-900/5 hover:border-orange-100 transition-all duration-300 overflow-hidden group">
                    <div class="h-32 bg-orange-50 flex items-center justify-center group-hover:bg-orange-100 transition-colors">
                        <i class="fas fa-hard-hat text-4xl text-orange-300 group-hover:text-orange-500 transition-colors"></i>
                    </div>
                    <div class="p-5 text-center">
                        <h3 class="text-lg font-bold text-slate-800 mb-4">Unit Konstruksi</h3>
                        <a href="#" class="block w-full rounded-xl bg-[#2F5AA8] text-white py-2.5 font-semibold hover:bg-[#274C8E] hover:shadow-lg hover:-translate-y-0.5 transition-all shadow-md shadow-blue-900/10">Login</a>
                    </div>
                </div>

                <!-- Unit TE -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl hover:shadow-purple-900/5 hover:border-purple-100 transition-all duration-300 overflow-hidden group">
                    <div class="h-32 bg-purple-50 flex items-center justify-center group-hover:bg-purple-100 transition-colors">
                        <i class="fas fa-bolt text-4xl text-purple-300 group-hover:text-purple-500 transition-colors"></i>
                    </div>
                    <div class="p-5 text-center">
                        <h3 class="text-lg font-bold text-slate-800 mb-4">Unit TE</h3>
                        <a href="#" class="block w-full rounded-xl bg-[#2F5AA8] text-white py-2.5 font-semibold hover:bg-[#274C8E] hover:shadow-lg hover:-translate-y-0.5 transition-all shadow-md shadow-blue-900/10">Login</a>
                    </div>
                </div>
            </div>

            <!-- Row 2: Supervisor (Centered) -->
            <div class="mt-8 flex justify-center">
                <div class="w-full max-w-xs bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl hover:shadow-slate-900/5 hover:border-slate-300 transition-all duration-300 overflow-hidden group">
                    <div class="h-32 bg-slate-100 flex items-center justify-center group-hover:bg-slate-200 transition-colors">
                        <i class="fas fa-user-tie text-4xl text-slate-400 group-hover:text-slate-600 transition-colors"></i>
                    </div>
                    <div class="p-5 text-center">
                        <h3 class="text-lg font-bold text-slate-800 mb-4">Supervisor</h3>
                        <a href="#" class="block w-full rounded-xl bg-[#2F5AA8] text-white py-2.5 font-semibold hover:bg-[#274C8E] hover:shadow-lg hover:-translate-y-0.5 transition-all shadow-md shadow-blue-900/10">Login</a>
                    </div>
                </div>
            </div>

        </section>
    </main>

</body>
</html>
