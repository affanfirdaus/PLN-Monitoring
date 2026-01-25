<x-filament::section class="w-full pln-kpi pln-kpi--hijau pln-kpi--half" heading="Verifikasi Dokumen & SLO (Top 10, paling lama)">
    <div class="pln-table-scroll">
        <table class="pln-table w-full">
            <thead>
                <tr>
                    <th>ID / Draft No</th>
                    <th>Nama</th>
                    <th>NIK</th>
                    <th>Status</th>
                    <th>Antrian</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                    <tr>
                        <td>{{ $row['id'] }}</td>
                        <td>{{ $row['nama'] }}</td>
                        <td>{{ $row['nik'] }}</td>
                        <td><span class="pln-badge pln-badge--green">{{ $row['status'] }}</span></td>
                        <td>{{ $row['antrian'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-filament::section>
