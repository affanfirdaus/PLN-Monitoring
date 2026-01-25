<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Widget;

class MenungguDistribusiSetelahPembayaranTable extends Widget
{
    protected static string $view = 'filament.admin.widgets.menunggu-distribusi-setelah-pembayaran-table';

    protected int|string|array $columnSpan = 12;

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
