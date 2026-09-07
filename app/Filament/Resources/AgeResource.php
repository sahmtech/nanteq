<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgeResource\Pages;
use App\Filament\Resources\AgeResource\RelationManagers;
use App\Models\Age;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AgeResource extends Resource
{
    protected static ?string $model = Age::class;

    public static function getNavigationGroup(): ?string
    {
        return __('dashboard.letters_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('dashboard.ages');
    }

    public static function getModelLabel(): string
    {
        return __('dashboard.age');
    }

    public static function getPluralModelLabel(): string
    {
        return __('dashboard.ages');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('age')
                            ->required()
                            ->numeric()
                            ->label(__('dashboard.age')),
                        Forms\Components\TextInput::make('name_written')
                            ->maxLength(255)
                            ->default(null)
                            ->label(__('dashboard.name_written')),
                        Select::make('ageGroup')
                            ->label(__('dashboard.the_age_group'))
                            ->relationship('ageGroup', 'name')
                            ->exists('age_groups', 'id')
                            ->live()
                            ->preload()
                            ->required(),
                    ])
                    ->columns(1)->columnSpan(2),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('age')
                    ->label(__('dashboard.age'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name_written')
                    ->label(__('dashboard.name_written'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('ageGroup.name')
                    ->label(__('dashboard.age_group_name'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ageGroup.from_age')
                    ->label(__('dashboard.from_age'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ageGroup.to_age')
                    ->label(__('dashboard.to_age'))
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
            'index' => Pages\ListAges::route('/'),
            'create' => Pages\CreateAge::route('/create'),
            'edit' => Pages\EditAge::route('/{record}/edit'),
        ];
    }
}
