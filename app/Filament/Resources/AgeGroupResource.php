<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgeGroupResource\Pages;
use App\Models\AgeGroup;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;


class AgeGroupResource extends Resource
{
    protected static ?string $model = AgeGroup::class;

    public static function getNavigationGroup(): ?string
    {
        return __('dashboard.letters_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('dashboard.age_groups');
    }

    public static function getModelLabel(): string
    {
        return __('dashboard.age_group');
    }

    public static function getPluralModelLabel(): string
    {
        return __('dashboard.age_groups');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->label(__('dashboard.name'))
                            ->maxLength(255)
                            ->default(null),
                        TextInput::make('from_age')
                            ->label(__('dashboard.from_age'))
                            ->required()
                            ->numeric(),
                        TextInput::make('to_age')
                            ->label(__('dashboard.to_age'))
                            ->required()
                            ->numeric(),
                    ])->columnSpan(1)
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('dashboard.name'))
                    ->searchable(),
                TextColumn::make('from_age')
                    ->label(__('dashboard.from_age'))
                    ->badge()
                    ->numeric()
                    ->sortable(),
                TextColumn::make('to_age')
                    ->label(__('dashboard.to_age'))
                    ->badge()
                    ->numeric()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label(__('dashboard.updated_at'))
                    ->dateTime('Y/m/d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label(__('dashboard.created_at'))
                    ->dateTime('Y/m/d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
            'index' => Pages\ListAgeGroups::route('/'),
            'create' => Pages\CreateAgeGroup::route('/create'),
            'edit' => Pages\EditAgeGroup::route('/{record}/edit'),
        ];
    }
}
