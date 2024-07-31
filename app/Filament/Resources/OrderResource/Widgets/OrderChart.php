<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class OrderChart extends ChartWidget
{
    // protected int |string | array $columnSpan = 'full';

    protected static ?int $sort = 2;
    protected static ?string $heading = 'Total Orders Made';

    protected function getData(): array
    {

        $data = Trend::model(Order::class)
        ->between(
            start: now()->startOfYear(),
            end: now()->endOfYear(),
        )
        ->perMonth()
        ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Total Orders',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
