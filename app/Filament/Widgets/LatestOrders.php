<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use App\Models\Order;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use App\Filament\Resources\OrderResource;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrders extends BaseWidget
{
    protected int |string | array $columnSpan = 'full';

    protected static ?int $sort = 4;

    public function table(Table $table): Table
    {
        return $table
            ->query(OrderResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                ->label('Order ID')
                ->searchable(),

                Tables\Columns\TextColumn::make('user.name')
                ->label('Customer Name')
                ->searchable(),

                Tables\Columns\TextColumn::make('grand_total')
                ->money('NGN')
                ->sortable(),

                Tables\Columns\TextColumn::make('status')
                ->badge()
                ->sortable()
                ->colors([
                 'new' => 'info',
                 'processing' => 'warning',
                 'shipped' => 'success',
                 'delivered' => 'secondary',
                 'canceled' => 'danger'  ,
                ])
                ->icon(fn (string $state): ?string => match ($state) {
                    'new' => 'heroicon-m-sparkles',
                    'processing' => 'heroicon-m-arrow-path',
                    'shipped' => 'heroicon-m-truck',
                    'delivered' => 'heroicon-m-check-badge',
                    'canceled' => 'heroicon-m-x-circle',
                }),

                Tables\Columns\TextColumn::make('payment_method')
                ->sortable()
                ->searchable(),

                Tables\Columns\TextColumn::make('payment_status')
                ->sortable()
                ->badge()
                ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                ->label('Order Date')
                ->dateTime(),
            ])
            ->actions([
                Action::make('View Order')
                ->url(fn (Order $record):string => OrderResource::getUrl('view', ['record' => $record]))
                ->color('info')
                ->icon('heroicon-o-eye'),

                Tables\Actions\DeleteAction::make(),
            ]);
    }
}