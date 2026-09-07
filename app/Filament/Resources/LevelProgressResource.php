<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LevelProgressResource\Pages;
use App\Filament\Resources\LevelProgressResource\RelationManagers;
use App\Models\LevelProgress;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LevelProgressResource extends Resource
{
    protected static ?string $model = LevelProgress::class;

    public static function getNavigationGroup(): ?string
    {
        return __('dashboard.level_sounds_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('dashboard.level_progress');
    }

    public static function getModelLabel(): string
    {
        return __('dashboard.level_progress');
    }

    public static function getPluralModelLabel(): string
    {
        return __('dashboard.level_progresses');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('progress')
                    ->numeric()
                    ->label(__('dashboard.progress'))
                    ->default(null),
                Forms\Components\TextInput::make('sounds_count')
                    ->numeric()
                    ->label(__('dashboard.sounds_count'))
                    ->default(null),
                Forms\Components\TextInput::make('completed_sounds_count')
                    ->numeric()
                    ->label(__('dashboard.completed_sounds_count'))
                    ->default(null),
                Forms\Components\DatePicker::make('last_completed_sound_date')
                    ->label(__('dashboard.last_completed_sound_date'))
                    ->default(null),
                Forms\Components\TextInput::make('status')
                    ->label(__('dashboard.status'))
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('previous_level_id')
                    ->numeric()
                    ->label(__('dashboard.previous_level_id'))
                    ->default(null),
                Forms\Components\TextInput::make('level_id')
                    ->numeric()
                    ->label(__('dashboard.level_id'))
                    ->default(null),
                Forms\Components\TextInput::make('letter_id')
                    ->numeric()
                    ->label(__('dashboard.letter_id'))
                    ->default(null),
                Forms\Components\TextInput::make('trainee_id')
                    ->numeric()
                    ->label(__('dashboard.trainee_id'))
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('progress')
                    ->numeric()
                    ->label(__('dashboard.progress'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('sounds_count')
                    ->numeric()
                    ->label(__('dashboard.sounds_count'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('completed_sounds_count')
                    ->numeric()
                    ->label(__('dashboard.completed_sounds_count'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_completed_sound_date')
                    ->date()
                    ->label(__('dashboard.last_completed_sound_date'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('dashboard.status'))
                    ->state(fn (LevelProgress $record) => $record->status == 1 ? __('dashboard.completed') : __('dashboard.in_progress'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('level.name')
                    ->label(__('dashboard.level_name'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('previousLevel.name')
                    ->label(__('dashboard.previous_level_name'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('letter.name')
                    ->label(__('dashboard.letter_name'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('trainee.name')
                    ->label(__('dashboard.trainee_name'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label(__('dashboard.created_at'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->label(__('dashboard.updated_at'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'index' => Pages\ListLevelProgress::route('/'),
            // 'create' => Pages\CreateLevelProgress::route('/create'),
            'edit' => Pages\EditLevelProgress::route('/{record}/edit'),
        ];
    }
}
