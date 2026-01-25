<?php

namespace App\Filament\AdminPelayanan\Widgets;

use Filament\Widgets\Widget;

class MenungguDistribusiSetelahPembayaranTable extends Widget
{
    protected static string $view = 'filament.admin-pelayanan.widgets.menunggu-distribusi-setelah-pembayaran-table';

    protected int|string|array $columnSpan = [
        'default' => 12,
        'xl' => 8,
    ];

    protected function getViewData(): array
    {
        return [
            'rows' => [
                ['id' => '27', 'nama' => '—', 'nik' => '—', 'status' => 'Menunggu', 'aksi' => 'Detail', 'antrian' => '23.3an hari'],
                ['id' => '28', 'nama' => '—', 'nik' => '—', 'status' => 'Menunggu', 'aksi' => 'Detail', 'antrian' => '22.3an hari'],
                ['id' => '29', 'nama' => '—', 'nik' => '—', 'status' => 'Menunggu', 'aksi' => 'Detail', 'antrian' => '23.5an hari'],
            ],
        ];
    }
}
