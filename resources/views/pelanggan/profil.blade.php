<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - PLN UP3 Kudus</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800">

    <!-- Navbar Simple -->
    <header class="bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 md:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('landing') }}" class="flex items-center gap-2">
                <i class="fas fa-arrow-left text-slate-400"></i>
                <span class="font-semibold text-slate-600">Kembali ke Beranda</span>
            </a>
            <span class="font-bold text-[#2F5AA8]">PLN UP3 KUDUS</span>
        </div>
    </header>

    <main class="max-w-2xl mx-auto px-4 py-10">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-slate-50 px-8 py-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold text-slate-800">Profil Saya</h1>
                    <p class="text-sm text-slate-500">Informasi akun pelanggan</p>
                </div>
            </div>
            
            <div class="p-8 space-y-6">
                <!-- Avatar Section -->
                <div class="flex items-center gap-6">
                    <div class="w-20 h-20 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 text-2xl font-bold border-2 border-white shadow-md">
                        @if($user->profile_photo)
                            <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Avatar" class="w-full h-full object-cover rounded-full">
                        @else
                            {{ substr($user->name, 0, 2) }}
                        @endif
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">{{ $user->name }}</h2>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Pelanggan
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 pt-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-500 mb-1">Nama Lengkap</label>
                        <div class="text-base font-semibold text-slate-800 border-b border-slate-100 pb-2">
                            {{ $user->name }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-500 mb-1">Alamat Email</label>
                        <div class="text-base font-semibold text-slate-800 border-b border-slate-100 pb-2">
                            {{ $user->email }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-500 mb-1">Bergabung Sejak</label>
                        <div class="text-base font-semibold text-slate-800 border-b border-slate-100 pb-2">
                            {{ $user->created_at->format('d F Y') }}
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-slate-50 px-8 py-4 border-t border-slate-100 flex justify-end">
                <form action="{{ route('pelanggan.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-red-600 font-medium text-sm hover:text-red-700">
                        Logout Akun
                    </button>
                </form>
            </div>
        </div>
    </main>

</body>
</html>
