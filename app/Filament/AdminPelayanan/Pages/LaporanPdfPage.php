<?php

namespace App\Filament\AdminPelayanan\Pages;

use Filament\Pages\Page;

class LaporanPdfPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationLabel = 'Laporan PDF';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?int $navigationSort = 7;

    protected static string $view = 'filament.admin-pelayanan.pages.laporan-pdf-page';
}
