<?php

namespace App\Filament\AdminPelayanan\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminLayananStatsOverview extends StatsOverviewWidget
{
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        return [
            Stat::make('Permohonan hari ini', '12')
                ->icon('heroicon-o-document-text')
                ->color('primary')
                ->extraAttributes(['class' => 'pln-stat pln-stat--primary']),

            Stat::make('Permohonan bulan ini', '245')
                ->icon('heroicon-o-calendar-days')
                ->color('primary')
                ->extraAttributes(['class' => 'pln-stat pln-stat--primary']),

            Stat::make('Permohonan masuk unit', '38')
                ->icon('heroicon-o-inbox-arrow-down')
                ->color('primary')
                ->extraAttributes(['class' => 'pln-stat pln-stat--primary']),

            Stat::make('Pending verifikasi', '15 Pending')
                ->icon('heroicon-o-clock')
                ->color('warning')
                ->extraAttributes(['class' => 'pln-stat pln-stat--warning']),

            Stat::make('Pending verifikasi SLO', '8 Pending')
                ->icon('heroicon-o-document-check')
                ->color('success')
                ->extraAttributes(['class' => 'pln-stat pln-stat--success']),

            Stat::make('Pending verifikasi', '8 Pending')
                ->icon('heroicon-o-exclamation-triangle')
                ->color('danger')
                ->extraAttributes(['class' => 'pln-stat pln-stat--danger']),

            Stat::make('Menunggu pembayaran', '20')
                ->icon('heroicon-o-banknotes')
                ->color('success')
                ->extraAttributes(['class' => 'pln-stat pln-stat--success']),

            Stat::make('Pembayaran sukses', '185')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->extraAttributes(['class' => 'pln-stat pln-stat--success']),

            Stat::make('Pembayaran gagal', '9 Attempt 1')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->extraAttributes(['class' => 'pln-stat pln-stat--danger']),

            Stat::make('Dalam proses unit', '56')
                ->icon('heroicon-o-cog-6-tooth')
                ->color('success')
                ->extraAttributes(['class' => 'pln-stat pln-stat--success']),

            Stat::make('Close order gagal final', '102')
                ->icon('heroicon-o-no-symbol')
                ->color('gray')
                ->extraAttributes(['class' => 'pln-stat pln-stat--gray']),
        ];
    }

    protected function getColumns(): int
    {
        return 5;
    }
}
