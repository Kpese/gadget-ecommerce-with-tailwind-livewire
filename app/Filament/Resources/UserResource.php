<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Filament\Resources\UserResource\RelationManagers\OrderRelationManager;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                forms\Components\TextInput::make('name')->required(),

                forms\Components\TextInput::make('email')
                ->label('Email Address')
                ->email()
                ->maxLength(255)
                ->unique(ignoreRecord:true)
                ->required(),

                forms\Components\DateTimePicker::make('email_verified_at')
                ->label('Email Verified At')
                ->default(now()),

                forms\Components\TextInput::make('password')
                ->label('Password')
                ->password()
                ->maxLength(255)
                ->dehydrated(fn($state) => filled($state))
                ->required(fn(Page $livewire): bool => $livewire instanceof CreateRecord),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\columns\TextColumn::make('name')->searchable(),
                Tables\columns\TextColumn::make('email')->searchable(),
                Tables\columns\TextColumn::make('email_verified_at')->dateTime()->sortable(),

                Tables\columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\ViewAction::make(),
                // Tables\Actions\DeleteAction::make(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
          OrderRelationManager::class,
        ];
    }

    public static function getGloballySearchableAttributes(): array
{
    return ['name', 'email'];
}

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
