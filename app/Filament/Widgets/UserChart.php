<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

// class UserChart extends ChartWidget
// {
//   protected int |string | array $columnSpan = 'full';

//     protected static ?int $sort = 3;
//     protected static ?string $heading = 'Total Users';

//     protected function getData(): array
//     {
//         $data = Trend::model(User::class)
//         ->between(
//             start: now()->startOfYear(),
//             end: now()->endOfYear(),
//         )
//         ->perMonth()
//         ->count();

//         return [
//             'datasets' => [
//                 [
//                     'label' => 'Total Users',
//                     'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
//                 ],
//             ],
//             'labels' => $data->map(fn (TrendValue $value) => $value->date),
//         ];
//     }

//     protected function getType(): string
//     {
//         return 'line';
//     }
// }
