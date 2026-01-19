<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Pelanggan - PLN UP3 Kudus</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800 min-h-screen flex items-center justify-center py-10">

    <div class="w-full max-w-2xl bg-white p-8 rounded-2xl shadow-lg border border-slate-100">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-slate-900">Registrasi Pelanggan</h1>
            <p class="text-slate-500 text-sm mt-1">Lengkapi data diri untuk mengajukan layanan</p>
        </div>

        <form action="{{ route('pelanggan.register.submit') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Section 1: Data Diri -->
            <div>
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wide border-b border-slate-100 pb-2 mb-4">Data Diri</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">Nama Lengkap</label>
                        <input type="text" name="name" id="name" required 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all text-sm"
                               placeholder="Nama Lengkap Sesuai KTP" value="{{ old('name') }}">
                    </div>

                    <!-- Gender -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Jenis Kelamin</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="gender" value="L" class="w-4 h-4 text-blue-600 focus:ring-blue-500" {{ old('gender') == 'L' ? 'checked' : '' }}>
                                <span class="text-sm text-slate-600">Laki-laki</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="gender" value="P" class="w-4 h-4 text-blue-600 focus:ring-blue-500" {{ old('gender') == 'P' ? 'checked' : '' }}>
                                <span class="text-sm text-slate-600">Perempuan</span>
                            </label>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-slate-700 mb-1.5">No. Handphone</label>
                        <input type="tel" name="phone" id="phone" required 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all text-sm"
                               placeholder="08xxxxxxxxxx" value="{{ old('phone') }}">
                    </div>

                    <!-- Email -->
                    <div class="md:col-span-2">
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
                        <input type="email" name="email" id="email" required 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all text-sm"
                               placeholder="nama@email.com" value="{{ old('email') }}">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: Alamat -->
            <div>
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wide border-b border-slate-100 pb-2 mb-4">Alamat Domisili</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Province -->
                    <div>
                        <label for="province" class="block text-sm font-medium text-slate-700 mb-1.5">Provinsi</label>
                        <select name="province" id="province" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all text-sm">
                            <option value="">Pilih Provinsi</option>
                            <option value="Jawa Tengah">Jawa Tengah</option>
                            <!-- More options would be dynamic in real app -->
                        </select>
                    </div>

                    <!-- Regency -->
                    <div>
                        <label for="regency" class="block text-sm font-medium text-slate-700 mb-1.5">Kabupaten/Kota</label>
                        <select name="regency" id="regency" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all text-sm">
                            <option value="">Pilih Kota/Kab</option>
                            <option value="Kudus">Kudus</option>
                            <!-- More options would be dynamic in real app -->
                        </select>
                    </div>

                    <!-- District -->
                    <div>
                        <label for="district" class="block text-sm font-medium text-slate-700 mb-1.5">Kecamatan</label>
                        <select name="district" id="district" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all text-sm">
                            <option value="">Pilih Kecamatan</option>
                            <option value="Kota Kudus">Kota Kudus</option>
                            <option value="Jati">Jati</option>
                            <!-- More options would be dynamic in real app -->
                        </select>
                    </div>

                    <!-- Village -->
                    <div>
                        <label for="village" class="block text-sm font-medium text-slate-700 mb-1.5">Kelurahan/Desa</label>
                        <input type="text" name="village" id="village" required 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all text-sm"
                               placeholder="Nama Desa" value="{{ old('village') }}">
                    </div>

                    <!-- Postal Code -->
                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-slate-700 mb-1.5">Kode Pos</label>
                        <input type="text" name="postal_code" id="postal_code" required 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all text-sm"
                               placeholder="593xx" value="{{ old('postal_code') }}">
                    </div>

                    <!-- Detail Address -->
                    <div class="md:col-span-2">
                        <label for="address_detail" class="block text-sm font-medium text-slate-700 mb-1.5">Detail Alamat</label>
                        <textarea name="address_detail" id="address_detail" rows="2" 
                                  class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all text-sm"
                                  placeholder="Nama jalan, nomor rumah, RT/RW...">{{ old('address_detail') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Section 3: Password -->
            <div>
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wide border-b border-slate-100 pb-2 mb-4">Keamanan Akun</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">Password</label>
                        <input type="password" name="password" id="password" required 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all text-sm"
                               placeholder="Min. 8 karakter">
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1.5">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all text-sm"
                               placeholder="Ulangi password">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full py-3.5 px-4 bg-[#2F5AA8] text-white font-semibold rounded-xl hover:bg-[#274C8E] transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5 text-sm mt-6">
                Daftar
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-slate-500">
            Sudah punya akun? <a href="{{ route('pelanggan.login') }}" class="text-[#2F5AA8] font-bold hover:underline">Login</a>
        </div>
    </div>

</body>
</html>
