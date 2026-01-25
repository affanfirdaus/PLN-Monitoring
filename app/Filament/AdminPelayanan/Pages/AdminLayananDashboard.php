<?php

namespace App\Filament\AdminPelayanan\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class AdminLayananDashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $title = 'Dashboard Admin Layanan';
    protected static ?int $navigationSort = 1;

    public function getColumns(): int
    {
        return 12;
    }

    public function getHeaderWidgets(): array
    {
        return [
            \App\Filament\AdminPelayanan\Widgets\AdminLayananSearchBox::class,
        ];
    }

    public function getWidgets(): array
    {
        return [
            \App\Filament\AdminPelayanan\Widgets\AdminLayananStatsOverview::class,
            \App\Filament\AdminPelayanan\Widgets\TrenPermohonanPerHariChart::class,
            \App\Filament\AdminPelayanan\Widgets\PaymentGatewayChart::class,
            \App\Filament\AdminPelayanan\Widgets\AntrianVerifikasiRegistrasiTable::class,
            \App\Filament\AdminPelayanan\Widgets\AntrianVerifikasiSloTable::class,
            \App\Filament\AdminPelayanan\Widgets\MenungguDistribusiSetelahPembayaranTable::class,
        ];
    }
}
