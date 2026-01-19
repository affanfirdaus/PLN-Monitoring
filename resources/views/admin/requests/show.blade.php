<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Permintaan - {{ $request->full_name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans p-8">
    <div class="max-w-4xl mx-auto">
        <a href="{{ route('admin.requests.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">&larr; Kembali ke List</a>
        
        <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h1 class="text-xl font-bold text-gray-800">Detail Permintaan Akun</h1>
                <span class="bg-yellow-100 text-yellow-800 text-xs px-3 py-1 rounded-full font-bold uppercase tracking-wide">Pending</span>
            </div>
            
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Personal Info -->
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Informasi Pribadi</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs text-gray-500">Nama Lengkap</label>
                            <p class="font-medium text-gray-800">{{ $request->full_name }}</p>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500">Email</label>
                            <p class="font-medium text-gray-800">{{ $request->email }}</p>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500">No HP</label>
                            <p class="font-medium text-gray-800">{{ $request->phone }}</p>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500">Jenis Kelamin</label>
                            <p class="font-medium text-gray-800">{{ $request->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Address Info -->
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Alamat Domisili</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs text-gray-500">Provinsi</label>
                            <p class="font-medium text-gray-800">{{ $request->province }}</p>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500">Kota/Kabupaten</label>
                            <p class="font-medium text-gray-800">{{ $request->regency }}</p>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500">Kecamatan</label>
                            <p class="font-medium text-gray-800">{{ $request->district }}</p>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500">Detail</label>
                            <p class="font-medium text-gray-800">{{ $request->address_text ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-end gap-3">
                <!-- Reject Button (Modal Trigger - mocked with JS prompt for now or simple form) -->
                <form action="{{ route('admin.requests.reject', $request->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menolak?');">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-white border border-red-300 text-red-600 rounded-lg hover:bg-red-50 text-sm font-semibold transition-colors">
                        Tolak Permintaan
                    </button>
                </form>

                <!-- Approve Button -->
                <form action="{{ route('admin.requests.approve', $request->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menyetujui dan membuat akun?');">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-md text-sm font-semibold transition-colors">
                        Setujui & Buat Akun
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
