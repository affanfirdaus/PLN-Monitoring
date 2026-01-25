<x-filament::section heading="Menunggu distribusi tindakan setelah pembayaran (Top 10)">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="border-b bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-3 py-2 text-left">ID / Draft No</th>
                    <th class="px-3 py-2 text-left">Nama</th>
                    <th class="px-3 py-2 text-left">NIK</th>
                    <th class="px-3 py-2 text-left">Status</th>
                    <th class="px-3 py-2 text-left">Aksi</th>
                    <th class="px-3 py-2 text-left">Antrian</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach ($rows as $r)
                    <tr>
                        <td class="px-3 py-2">{{ $r['id'] }}</td>
                        <td class="px-3 py-2">{{ $r['nama'] }}</td>
                        <td class="px-3 py-2">{{ $r['nik'] }}</td>
                        <td class="px-3 py-2">
                            <x-filament::badge color="success">{{ $r['status'] }}</x-filament::badge>
                        </td>
                        <td class="px-3 py-2">
                            <x-filament::badge color="primary">{{ $r['aksi'] }}</x-filament::badge>
                        </td>
                        <td class="px-3 py-2">{{ $r['antrian'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-filament::section>
