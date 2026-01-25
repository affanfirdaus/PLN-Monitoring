<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Widget;

class AntrianVerifikasiRegistrasiTable extends Widget
{
    protected static string $view = 'filament.admin.widgets.antrian-verifikasi-registrasi-table';

    protected int|string|array $columnSpan = 6;

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
