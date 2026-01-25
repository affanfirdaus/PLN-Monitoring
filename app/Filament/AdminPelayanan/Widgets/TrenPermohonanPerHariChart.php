<?php

namespace App\Filament\AdminPelayanan\Widgets;

use Filament\Widgets\ChartWidget;

class TrenPermohonanPerHariChart extends ChartWidget
{
    protected static ?string $heading = 'Tren permohonan per hari';

    protected int|string|array $columnSpan = [
        'default' => 12,
        'xl' => 6,
    ];

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Permohonan masuk',
                    'data' => [1200, 2100, 1800, 3900, 1700, 1600, 4100, 5200],
                    'tension' => 0.35,
                ],
                [
                    'label' => 'Permohonan selesai',
                    'data' => [200, 800, 500, 1100, 400, 300, 1400, 3100],
                    'tension' => 0.35,
                ],
            ],
            'labels' => ['Sep', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
