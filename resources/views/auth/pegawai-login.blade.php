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
            </div>
        </div>
    </header>

    <main class="pt-20">
        <!-- Hero Section -->
        <section class="relative w-full overflow-hidden bg-slate-50 border-b border-slate-200 min-h-screen bg-cover bg-center bg-no-repeat flex items-center" style="background-image: url('{{ asset('images/Hero-section.png') }}');">
            <!-- Overlay -->
            <div class="absolute inset-0 bg-gradient-to-r from-white/20 via-white/10 to-transparent z-0 backdrop-blur-[1px]"></div>
            
            <!-- Back Button inside Hero -->
            <div class="absolute top-8 left-4 md:left-8 z-20">
                <a href="{{ route('pegawai.login') }}" class="flex items-center gap-2 px-4 py-2 rounded-full bg-white/70 backdrop-blur border border-white/60 shadow-sm hover:bg-white/90 transition text-slate-700 font-medium text-sm">
                    <i class="fas fa-arrow-left text-xs"></i>
                    Kembali
                </a>
            </div>

            <!-- Login Form Container -->
            <div class="relative z-10 w-full max-w-md mx-auto px-4">
                <div class="bg-white/90 backdrop-blur-md rounded-3xl shadow-2xl border border-white/60 p-8">
                    <!-- Title -->
                    <div class="text-center mb-8">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-100/80 border border-blue-200 text-blue-800 text-xs font-bold uppercase tracking-wider backdrop-blur-sm mb-4">
                            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                            Login Area
                        </div>
                        <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900 leading-tight">
                            Login sebagai: <span class="text-[#2F5AA8]">{{ $role_label ?? 'Pegawai' }}</span>
                        </h2>
                        <p class="text-slate-600 text-sm mt-2">Masuk ke panel internal Anda</p>
                    </div>

                    <!-- Success Message -->
                    @if (session('success'))
                        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800 text-sm">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('pegawai.login.post', ['role' => $role_key]) }}" class="space-y-6">
                        @csrf

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">
                                Email
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-slate-400"></i>
                                </div>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email') }}"
                                    required 
                                    autofocus
                                    class="block w-full pl-11 pr-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#2F5AA8] focus:border-[#2F5AA8] transition-all @error('email') border-red-500 @enderror"
                                    placeholder="nama@domain.com"
                                >
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div>
                            <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">
                                Password
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-slate-400"></i>
                                </div>
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    required
                                    class="block w-full pl-11 pr-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#2F5AA8] focus:border-[#2F5AA8] transition-all @error('password') border-red-500 @enderror"
                                    placeholder="••••••••"
                                >
                            </div>
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center">
                            <input 
                                type="checkbox" 
                                id="remember" 
                                name="remember"
                                class="w-4 h-4 text-[#2F5AA8] border-slate-300 rounded focus:ring-[#2F5AA8]"
                            >
                            <label for="remember" class="ml-2 text-sm text-slate-700">
                                Ingat saya
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button 
                            type="submit"
                            class="w-full py-3 px-4 bg-[#2F5AA8] text-white font-semibold rounded-xl hover:bg-[#274C8E] hover:shadow-lg hover:-translate-y-0.5 transition-all shadow-md shadow-blue-900/10"
                        >
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Masuk
                        </button>
                    </form>

                    <!-- Footer Info -->
                    <div class="mt-6 text-center text-xs text-slate-500">
                        <p>Hanya untuk pegawai internal PLN UP3 Kudus</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

</body>
</html>
