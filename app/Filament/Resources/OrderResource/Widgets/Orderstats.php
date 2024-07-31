<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Illuminate\Support\Number;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class Orderstats extends BaseWidget
{
    protected function getStats(): array
    {
        $total_price = '₦ '. number_format(Order::query()->avg('grand_total')  ?? 0, 2);
        return [
        Stat::make('New Orders', Order::query()->where('status', 'new')->count()),
        Stat::make('Order Processing', Order::query()->where('status', 'processing')->count()),
        Stat::make('Order shipped', Order::query()->where('status', 'shipped')->count()),
        Stat::make('Average Price', $total_price),
        ];
    }

}
