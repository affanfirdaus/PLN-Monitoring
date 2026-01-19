<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permintaan Diproses - PLN UP3 Kudus</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen font-sans p-4">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-slate-100 p-8 text-center">
        <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-clock text-3xl text-blue-600"></i>
        </div>
        
        <h1 class="text-2xl font-bold text-slate-800 mb-2">Permintaan Diproses</h1>
        
        <p class="text-slate-600 mb-8 leading-relaxed">
            Permintaan pembuatan akun Anda sedang diproses. Silakan mengecek email Anda secara berkala hingga <strong>1 x 24 jam</strong>.
        </p>

        <a href="{{ route('pelanggan.login') }}" class="inline-block w-full bg-[#2F5AA8] hover:bg-[#274C8E] text-white font-semibold py-3 rounded-xl transition-all shadow-md hover:shadow-lg">
            Login
        </a>
        
        <p class="text-xs text-slate-400 mt-6">
            Jika Anda tidak menerima email konfirmasi, silakan hubungi layanan pelanggan kami.
        </p>
    </div>

</body>
</html>
