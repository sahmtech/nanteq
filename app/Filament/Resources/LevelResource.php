<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LevelResource\Pages;
use App\Filament\Resources\LevelResource\RelationManagers;
use App\Models\Level;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LevelResource extends Resource
{
    protected static ?string $model = Level::class;

    public static function getNavigationGroup(): ?string
    {
        return __('dashboard.level_sounds_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('dashboard.levels');
    }

    public static function getModelLabel(): string
    {
        return __('dashboard.level');
    }

    public static function getPluralModelLabel(): string
    {
        return __('dashboard.levels');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('dashboard.name'))
                            ->maxLength(255)
                            ->required(),
                        Select::make('letter_id')
                            ->label(__('dashboard.the_letter'))
                            ->relationship('letter', 'name')
                            ->exists('letters', 'id')
                            ->live()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('completed_sounds_to_success')
                            ->label(__('dashboard.complete_sounds_to_success'))
                            ->numeric()
                            ->required()
                            ->minValue(0),
                        Forms\Components\TextInput::make('sort_order')
                            ->label(__('dashboard.sort_order'))
                            ->numeric()
                            ->required()
                            ->minValue(0),
                    ])->columns(1)
                    ->columnSpan(1)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('dashboard.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('letter.name')
                    ->label(__('dashboard.the_letter'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('completed_sounds_to_success')
                    ->label(__('dashboard.complete_sounds_to_success'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label(__('dashboard.sort_order'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('dashboard.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('dashboard.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('dashboard.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('letter')
                    ->relationship('letter', 'name')
                    ->label(__('dashboard.the_letter'))
                    ->preload()
                    ->searchable()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLevels::route('/'),
            'create' => Pages\CreateLevel::route('/create'),
            'edit' => Pages\EditLevel::route('/{record}/edit'),
        ];
    }
}
