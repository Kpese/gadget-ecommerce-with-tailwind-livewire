<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use App\Filament\Resources\OrderResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class OrderRelationManager extends RelationManager
{
    protected static string $relationship = 'order';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                ->label('Order ID')
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
                 'delivered' => 'success',
                 'cancelled' => 'danger'  ,
                ])
                ->icon(fn (string $state): ?string => match ($state) {
                    'new' => 'heroicon-m-sparkles',
                    'processing' => 'heroicon-m-arrow-path',
                    'shipped' => 'heroicon-m-truck',
                    'delivered' => 'heroicon-m-check-badge',
                    'cancelled' => 'heroicon-m-x-circle',
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
            ->filters([
                //
            ])
            // ->headerActions([
            //     Tables\Actions\CreateAction::make(),
            // ])
            ->actions([
                Action::make('View Order')
                ->url(fn (Order $record):string => OrderResource::getUrl('view', ['record' => $record]))
                ->color('info')
                ->icon('heroicon-o-eye'),

                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
