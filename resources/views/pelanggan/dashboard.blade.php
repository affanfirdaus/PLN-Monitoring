<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pelanggan - PLN UP3 Kudus</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans text-gray-800">
    <div class="min-h-screen flex flex-col">
        <!-- Navbar -->
        <nav class="bg-white shadow-sm border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <span class="text-blue-600 font-bold text-xl">PLN UP3 KUDUS</span>
                        <span class="ml-4 text-gray-500 text-sm border-l pl-4">Dashboard Pelanggan</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-sm font-medium">{{ Auth::user()->name }}</span>
                        <form action="{{ route('pelanggan.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-red-600 text-sm font-medium hover:text-red-700">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Content -->
        <main class="flex-grow container mx-auto px-4 py-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h1 class="text-2xl font-bold mb-4">Selamat Datang, {{ Auth::user()->name }}!</h1>
                <p class="text-gray-600">Anda berhasil login sebagai Pelanggan.</p>
                
                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-6 bg-blue-50 rounded-lg border border-blue-100">
                        <h3 class="font-bold text-blue-800 text-lg mb-2">Permohonan Aktif</h3>
                        <p class="text-blue-600 text-3xl font-bold">0</p>
                    </div>
                    <div class="p-6 bg-green-50 rounded-lg border border-green-100">
                        <h3 class="font-bold text-green-800 text-lg mb-2">Riwayat Layanan</h3>
                        <p class="text-green-600 text-3xl font-bold">0</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
