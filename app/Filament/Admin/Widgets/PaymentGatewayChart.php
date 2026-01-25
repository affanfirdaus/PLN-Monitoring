<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;

class PaymentGatewayChart extends ChartWidget
{
    protected static ?string $heading = 'Payment Gateway';

    protected int|string|array $columnSpan = 6;

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Success',
                    'data' => [6000, 9000, 7000, 12000, 8000, 15000],
                ],
                [
                    'label' => 'Pending',
                    'data' => [3000, 4500, 2500, 6000, 4000, 8000],
                ],
                [
                    'label' => 'Failed',
                    'data' => [2000, 3500, 1500, 4000, 2500, 5000],
                ],
            ],
            'labels' => ['Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'x' => ['stacked' => true],
                'y' => ['stacked' => true],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                    'align' => 'end',
                ],
            ],
        ];
    }
}
