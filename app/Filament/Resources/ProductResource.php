<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Actions\Action;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductResource\RelationManagers;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Product Description')->schema([
                        Forms\Components\TextInput::make('name')
                            ->maxLength(255)
                            ->default(null)
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn($state, Set $set) => $set('slug', Str::slug($state))),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->disabled()
                            ->dehydrated()
                            ->maxLength(255)
                            ->unique(Product::class, 'slug', ignoreRecord: true),

                        Forms\Components\MarkdownEditor::make('description')
                            ->columnSpanFull()
                            ->fileAttachmentsDirectory('products'),

                    ])->columnSpanFull(),
                    Section::make('Images')->schema([
                        Forms\Components\FileUpload::make('image')
                            ->multiple()
                            ->directory('products')
                            ->maxFiles(5)
                            ->reorderable()
                    ]),
                ])->columnSpan(2),

                Group::make()->schema([

                    Section::make()->schema([
                        Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->default(null)
                            ->required()
                            ->prefix('₦'),
                    ]),

                    Section::make('Associations')->schema([
                        Forms\Components\Select::make('category_id')
                            ->searchable()
                            ->required()
                            ->preload()
                            ->relationship('category', 'name'),

                        Forms\Components\Select::make('brand_id')
                            ->searchable()
                            ->required()
                            ->preload()
                            ->relationship('brand', 'name')
                    ]),

                    Section::make('Status')->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->required()
                            ->default(true),
                            Forms\Components\Toggle::make('in_stock')
                            ->required()
                            ->default(true),
                            Forms\Components\Toggle::make('is_featured')
                            ->required(),
                             Forms\Components\Toggle::make('on_sale')
                            ->required(),
                    ]),


                ])->columnSpan(1),

            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('brand.name')
                    ->numeric()
                    ->sortable(),
                // Tables\Columns\TextColumn::make('slug')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->money('NGN')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean(),
                Tables\Columns\IconColumn::make('in_stock')
                    ->boolean(),
                Tables\Columns\IconColumn::make('on_sale')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
               SelectFilter::make('category_id')
               ->relationship('category', 'name'),
               SelectFilter::make('brand_id')
               ->relationship('brand', 'name'),
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
            //
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'price'];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
