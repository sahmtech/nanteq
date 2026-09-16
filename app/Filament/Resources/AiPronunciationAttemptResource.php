<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AiPronunciationAttemptResource\Pages;
use App\Models\AiPronunciationAttempt;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AiPronunciationAttemptResource extends Resource
{
    protected static ?string $model = AiPronunciationAttempt::class;

    protected static ?int $navigationSort = 20;

    public static function getNavigationGroup(): ?string
    {
        return __('dashboard.reports_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('dashboard.pronunciation_reviews');
    }

    public static function getModelLabel(): string
    {
        return __('dashboard.pronunciation_review');
    }

    public static function getPluralModelLabel(): string
    {
        return __('dashboard.pronunciation_reviews');
    }

    public static function canViewAny(): bool
    {
        return ! auth()->user()?->isSpecialist();
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make(__('dashboard.pronunciation_review'))
                    ->schema([
                        Infolists\Components\TextEntry::make('letter.name')->label(__('dashboard.the_letter')),
                        Infolists\Components\TextEntry::make('sound.written_word')->label(__('dashboard.the_sound')),
                        Infolists\Components\TextEntry::make('target')->label(__('dashboard.pronunciation_target')),
                        Infolists\Components\TextEntry::make('heard')->label(__('dashboard.pronunciation_heard')),
                        Infolists\Components\TextEntry::make('score')->label(__('dashboard.pronunciation_score')),
                        Infolists\Components\TextEntry::make('overall_score')->label(__('dashboard.pronunciation_overall_score')),
                        Infolists\Components\TextEntry::make('ai_status')->label(__('dashboard.pronunciation_status')),
                        Infolists\Components\TextEntry::make('ai_score')->label(__('dashboard.pronunciation_ai_score')),
                        Infolists\Components\TextEntry::make('user.name')->label(__('dashboard.the_trainee')),
                        Infolists\Components\TextEntry::make('created_at')->dateTime()->label(__('dashboard.created_at')),
                    ])->columns(2),
                Infolists\Components\Section::make(__('dashboard.pronunciation_technical_log'))
                    ->schema([
                        Infolists\Components\TextEntry::make('technical_log')
                            ->label(__('dashboard.pronunciation_technical_log'))
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('audio_url')
                            ->label(__('dashboard.pronunciation_audio'))
                            ->url(fn (?string $state) => $state, true)
                            ->placeholder('-'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('dashboard.created_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('letter.name')
                    ->label(__('dashboard.the_letter'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sound.written_word')
                    ->label(__('dashboard.the_sound'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('score')
                    ->label(__('dashboard.pronunciation_score'))
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('overall_score')
                    ->label(__('dashboard.pronunciation_overall_score'))
                    ->badge()
                    ->color('info')
                    ->sortable(),
                Tables\Columns\TextColumn::make('ai_status')
                    ->label(__('dashboard.pronunciation_status'))
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'correct' => 'success',
                        'unclear', 'low_quality' => 'warning',
                        default => 'gray',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('target')
                    ->label(__('dashboard.pronunciation_target')),
                Tables\Columns\TextColumn::make('heard')
                    ->label(__('dashboard.pronunciation_heard')),
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('dashboard.the_trainee'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('audio_url')
                    ->label(__('dashboard.pronunciation_audio'))
                    ->url(fn (?string $state) => $state, true)
                    ->limit(24)
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('letter_id')
                    ->label(__('dashboard.the_letter'))
                    ->relationship('letter', 'name'),
                Tables\Filters\SelectFilter::make('score')
                    ->label(__('dashboard.pronunciation_score'))
                    ->options([
                        1 => '1',
                        2 => '2',
                        3 => '3',
                        4 => '4',
                        5 => '5',
                    ]),
                Tables\Filters\SelectFilter::make('ai_status')
                    ->label(__('dashboard.pronunciation_status'))
                    ->options([
                        'correct' => 'correct',
                        'unclear' => 'unclear',
                        'low_quality' => 'low_quality',
                        'incorrect' => 'incorrect',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['letter', 'sound', 'user']);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAiPronunciationAttempts::route('/'),
            'view' => Pages\ViewAiPronunciationAttempt::route('/{record}'),
        ];
    }
}
