<?php

namespace App\Filament\AdminPelayanan\Widgets;

use Filament\Widgets\Widget;

class AntrianVerifikasiRegistrasiTable extends Widget
{
    protected static string $view = 'filament.admin-pelayanan.widgets.antrian-verifikasi-registrasi-table';

    protected int|string|array $columnSpan = [
        'default' => 12,
        'xl' => 4,
    ];

    protected function getViewData(): array
    {
        return [
            'rows' => [
                ['id' => '30', 'nama' => '—', 'nik' => '3924', 'status' => 'Menunggu', 'antrian' => '23 hari'],
                ['id' => '30', 'nama' => '—', 'nik' => '3924', 'status' => 'Menunggu', 'antrian' => '22 hari'],
                ['id' => '18', 'nama' => '—', 'nik' => '3924', 'status' => 'Menunggu', 'antrian' => '22 hari'],
                ['id' => '12', 'nama' => '—', 'nik' => '3924', 'status' => 'Menunggu', 'antrian' => '22 hari'],
                ['id' => '15', 'nama' => '—', 'nik' => '3924', 'status' => 'Menunggu', 'antrian' => '22 hari'],
            ],
        ];
    }
}
