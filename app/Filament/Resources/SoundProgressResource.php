<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SoundProgressResource\Pages;
use App\Filament\Resources\SoundProgressResource\RelationManagers;
use App\Models\SoundProgress;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SoundProgressResource extends Resource
{
    protected static ?string $model = SoundProgress::class;


    public static function getNavigationGroup(): ?string
    {
        return __('dashboard.level_sounds_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('dashboard.sound_progress');
    }

    public static function getModelLabel(): string
    {
        return __('dashboard.sound_progress');
    }

    public static function getPluralModelLabel(): string
    {
        return __('dashboard.sound_progresses');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('trainee_id')
                    ->relationship('trainee', 'name')
                    ->label(__('dashboard.trainee'))
                    ->required(),
                Forms\Components\Select::make('letter_id')
                    ->relationship('letter', 'name')
                    ->label(__('dashboard.letter'))
                    ->required(),
                Forms\Components\Select::make('sound_id')
                    ->relationship('sound', 'written_word')
                    ->label(__('dashboard.sound'))
                    ->required(),
                Forms\Components\TextInput::make('success_attempts')
                    ->label(__('dashboard.success_attempts'))
                    ->numeric()
                    ->minValue(0)
                    ->required(),
                Forms\Components\TextInput::make('failure_attempts')
                    ->label(__('dashboard.failure_attempts'))
                    ->numeric()
                    ->minValue(0)
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label(__('dashboard.status'))
                    ->options([
                        'pending' => __('dashboard.pending'),
                        'completed' => __('dashboard.completed'),
                        'failed' => __('dashboard.failed')
                    ])
                    ->required(),
                Forms\Components\Textarea::make('records')
                    ->label(__('dashboard.records'))
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('result')
                    ->label(__('dashboard.result'))
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('trainee.name')
                    ->label(__('dashboard.the_trainee'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('letter.name')
                    ->label(__('dashboard.the_letter'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sound.written_word')
                    ->label(__('dashboard.the_sound'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('success_attempts')
                    ->label(__('dashboard.success_attempts'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('failure_attempts')
                    ->label(__('dashboard.failure_attempts'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('dashboard.status'))
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'in_progress' => 'warning',
                        'completed' => 'success',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'in_progress' => __('dashboard.in_progress'),
                        'completed' => __('dashboard.completed'),
                    })
                    ->searchable(),
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
            ])
            ->bulkActions([
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
            'index' => Pages\ListSoundProgress::route('/'),
        ];
    }
}
