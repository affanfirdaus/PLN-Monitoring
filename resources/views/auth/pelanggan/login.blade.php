<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pelanggan - PLN UP3 Kudus</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800 min-h-screen flex items-center justify-center">

    <!-- Back Button -->
    <div class="absolute top-8 left-4 md:left-8 z-20">
        <a href="{{ route('landing') }}" class="flex items-center gap-2 px-4 py-2 rounded-full bg-white/70 backdrop-blur border border-white/60 shadow-sm hover:bg-white/90 transition text-slate-700 font-medium text-sm">
            <i class="fas fa-arrow-left text-xs"></i>
            Kembali
        </a>
    </div>

    <div class="w-full max-w-sm bg-white p-8 rounded-2xl shadow-lg border border-slate-100">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-slate-900">Login</h1>
            <p class="text-slate-500 text-sm mt-1">Masuk ke akun Anda</p>
        </div>

        <form action="{{ route('pelanggan.login.submit') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <i class="fas fa-envelope text-xs"></i>
                    </span>
                    <input type="email" name="email" id="email" required 
                           class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all text-sm @error('email') border-red-500 @enderror"
                           placeholder="nama@email.com" value="{{ old('email') }}">
                </div>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <i class="fas fa-lock text-xs"></i>
                    </span>
                    <input type="password" name="password" id="password" required 
                           class="w-full pl-9 pr-12 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all text-sm @error('password') border-red-500 @enderror"
                           placeholder="******">
                    <button type="button" id="togglePassword" class="absolute inset-y-0 right-3 flex items-center text-slate-500 hover:text-slate-700 cursor-pointer transition-colors" aria-label="Tampilkan password">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full py-3 px-4 bg-[#2F5AA8] text-white font-semibold rounded-xl hover:bg-[#274C8E] transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5 text-sm">
                Login
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-slate-500">
            Belum memiliki akun? <a href="{{ route('pelanggan.register') }}" class="text-[#2F5AA8] font-bold hover:underline">Klik di sini</a>
        </div>
    </div>

    <script>
        const toggle = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        const icon = toggle.querySelector('i');

        toggle.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            // Toggle Icon FontAwesome
            if (type === 'password') {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        });
    </script>
</body>
</html>
