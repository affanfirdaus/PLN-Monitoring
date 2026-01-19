<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permintaan Akun Pelanggan - Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans p-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-2xl font-bold mb-6 text-slate-800">Permintaan Akun Pelanggan</h1>
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white px-6 py-4 rounded-xl shadow border border-gray-200">
            @if($requests->count() > 0)
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-700 uppercase font-bold border-b">
                        <tr>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($requests as $req)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3">{{ $req->created_at->format('d M Y H:i') }}</td>
                            <td class="px-4 py-3 font-semibold">{{ $req->full_name }}</td>
                            <td class="px-4 py-3">{{ $req->email }}</td>
                            <td class="px-4 py-3">
                                <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full font-bold">Pending</span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.requests.show', $req->id) }}" class="text-blue-600 font-bold hover:underline">Detail</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-center text-gray-500 py-4">Tidak ada permintaan pending saat ini.</p>
            @endif
        </div>
    </div>
</body>
</html>
