<?php

namespace App\Filament\AdminPelayanan\Widgets;

use Filament\Widgets\Widget;

class AntrianVerifikasiSloTable extends Widget
{
    protected static string $view = 'filament.admin-pelayanan.widgets.antrian-verifikasi-slo-table';

    protected int|string|array $columnSpan = [
        'default' => 12,
        'xl' => 8,
    ];

    protected function getViewData(): array
    {
        return [
            'rows' => [
                ['id' => '30', 'nama' => '—', 'nik' => '3934', 'status' => 'Menunggu', 'antrian' => '23 hari'],
                ['id' => '10', 'nama' => '—', 'nik' => '3924', 'status' => 'Menunggu', 'antrian' => '22 hari'],
                ['id' => '25', 'nama' => '—', 'nik' => '3921', 'status' => 'Menunggu', 'antrian' => '21 hari'],
            ],
        ];
    }
}
