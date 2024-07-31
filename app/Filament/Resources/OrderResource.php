<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\RelationManagers\AddressRelationManager;
use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Number;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\ToggleButtons;
use App\Filament\Resources\OrderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OrderResource\RelationManagers;
use Filament\Tables\Actions\ActionGroup;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
               Group::make()->schema([
                Section::make('Order Information')->schema([
                    Select::make('user_id')
                    ->label('Customer')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                    Select::make('payment_method')
                    ->options([
                        'paystack' => 'paystack',
                        'cod' => 'Cash on delivery'
                    ])
                    ->required(),

                    Select::make('payment_status')
                    ->options([
                        'pending' => 'pending',
                        'paid' => 'paid',
                        'failed' => 'failed',
                    ])
                    ->default('pending')
                    ->required(),

                    ToggleButtons::make('status')
                    ->inline()
                    ->default('new')
                    ->required()
                    ->options([
                        'new' => 'new',
                        'processing' => 'processing',
                        'shipped' => 'shipped',
                        'delivered' => 'delivered',
                        'canceled' => 'cancelled',
                    ])
                    ->colors([
                        'new' => 'info',
                        'processing' => 'warning',
                        'shipped' => 'success',
                        'delivered' => 'success',
                        'canceled' => 'danger',
                    ])
                    ->icons([
                        'new' => 'heroicon-m-sparkles',
                        'processing' => 'heroicon-m-arrow-path',
                        'shipped' => 'heroicon-m-truck',
                        'delivered' => 'heroicon-m-check-badge',
                        'canceled' => 'heroicon-m-x-circle',
                    ]),

                    Select::make('currency')
                    ->options([
                        'ngn' => 'NGN',
                        'usd' => 'USD',
                        'eur' => 'EUR',
                        'gbp' => 'GBP',
                    ])
                    ->default('ngn')
                    ->required(),

                    Select::make('shipping_method')
                    ->options([
                        'fedex' => 'Fedex',
                        'dhl' => 'DHL',
                    ]),

                    Textarea::make('notes')
                    ->columnSpanFull()

                ])->columns(2),

                Section::make('Order Items')->schema([
                    Repeater::make('orderItem')
                    ->relationship()
                    ->schema([
                        Select::make('product_id')
                        ->relationship('product', 'name')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->distinct()
                        ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                        ->reactive()
                        ->afterStateUpdated(function ($state, Set $set) {
                         $product = Product::find($state);
                          $set('unit_amount', $product ? $product->price : 0);
                         })
                          ->afterStateUpdated(function ($state, Set $set) {
                         $product = Product::find($state);
                          $set('total_amount', $product ? $product->price : 0);
                         })
                        ->columnSpan(4),

                        TextInput::make('quantity')
                        ->required()
                        ->numeric()
                        ->default(1)
                        ->minValue(1)
                        ->columnSpan(2)
                        ->reactive()
                        ->afterStateUpdated(fn($state, Set $set, Get $get) => $set('total_amount', $state * $get('unit_amount'))),

                        TextInput::make('unit_amount')
                        ->required()
                        ->numeric()
                        ->disabled()
                        ->dehydrated()
                        ->columnSpan(3),

                        TextInput::make('total_amount')
                        ->required()
                        ->numeric()
                        ->dehydrated()
                        ->columnSpan(3),
                    ])->columns(12),

                    Placeholder::make('grand_total_placeholder')
                    ->label('Grand Total')
                    ->content(function(Get $get, Set $set){
                        $total = 0;
                        if (!$repeater = $get('orderItem')) {
                            return $total;
                        }
                        foreach ($repeater as $value) {
                            $total += $value['total_amount']; // Access total_amount directly from the value
                        }

                        $set('grand_total', $total);
                        return Number::currency($total, 'NGN');
                    }),

                    Hidden::make('grand_total')
                    ->default(0),
                ])

               ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
               TextColumn::make('user.name')
               ->label('Customer')
               ->sortable()
               ->searchable(),

               TextColumn::make('grand_total')
               ->numeric()
               ->sortable()
               ->money('NGN', true),

               TextColumn::make('payment_method')
               ->searchable()
               ->sortable(),

               TextColumn::make('payment_status')
               ->searchable()
               ->sortable(),

               TextColumn::make('currency')
               ->searchable()
               ->sortable(),

               TextColumn::make('shipping_method')
               ->searchable()
               ->sortable(),

               SelectColumn::make('status')
               ->options([
                'new' => 'new',
                'processing' => 'processing',
                'shipped' => 'shipped',
                'delivered' => 'delivered',
                'canceled' => 'cancelled',
            ])
            ->searchable()
            ->sortable(),

            TextColumn::make('created_at')
               ->dateTime()
               ->sortable()
               ->toggleable(isToggledHiddenByDefault: true),

               TextColumn::make('updated_at')
               ->dateTime()
               ->sortable()
               ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            AddressRelationManager::class,
        ];
    }

    public static function getNavigationBadge(): ?string{
    return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null{
        return static::getModel()::count() > 10 ? 'danger' : 'success';
        }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
