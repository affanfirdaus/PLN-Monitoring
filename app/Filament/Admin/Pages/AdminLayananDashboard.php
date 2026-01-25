<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Dashboard;
use App\Filament\Admin\Widgets\AdminLayananSearchBox;
use App\Filament\Admin\Widgets\AdminLayananStatsOverview;
use App\Filament\Admin\Widgets\TrenPermohonanPerHariChart;
use App\Filament\Admin\Widgets\PaymentGatewayChart;
use App\Filament\Admin\Widgets\AntrianVerifikasiRegistrasiTable;
use App\Filament\Admin\Widgets\AntrianVerifikasiSloTable;
use App\Filament\Admin\Widgets\MenungguDistribusiSetelahPembayaranTable;

class AdminLayananDashboard extends Dashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $navigationGroup = 'Admin Layanan';
    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'Dashboard Admin Layanan';
    protected static ?string $slug = 'dashboard-admin-layanan';

    public function getHeaderWidgets(): array
    {
        return [
            AdminLayananSearchBox::class,
            AdminLayananStatsOverview::class,
        ];
    }

    public function getWidgets(): array
    {
        return [
            TrenPermohonanPerHariChart::class,
            PaymentGatewayChart::class,
            AntrianVerifikasiRegistrasiTable::class,
            AntrianVerifikasiSloTable::class,
            MenungguDistribusiSetelahPembayaranTable::class,
        ];
    }

    public function getColumns(): int|array
    {
        return 12;
    }
}
