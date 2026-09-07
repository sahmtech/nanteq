<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SoundResource\Pages;
use App\Models\Sound;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
class SoundResource extends Resource
{
    protected static ?string $model = Sound::class;

public static function getPermissionPrefixes(): array
{
    return [
        'view',
        'view_any',
        'create',
        'update',
        'delete',
        'delete_any',
        'access_video', 
        'access_audio', 
    ];
}


    public static function getNavigationGroup(): ?string
    {
        return __('dashboard.level_sounds_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('dashboard.sounds');
    }

    public static function getModelLabel(): string
    {
        return __('dashboard.sound');
    }

    public static function getPluralModelLabel(): string
    {
        return __('dashboard.sounds');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('dashboard.basic_information'))
                    ->schema([
                        Forms\Components\Select::make('letter_id')
                            ->relationship('letter', 'name')
                            ->label(__('dashboard.the_letter'))
                            ->exists('letters', 'id')
                            ->live()
                            ->preload()
                            ->required()
                            ->afterStateUpdated(function (callable $set, $state) {
                                $set('level_id', null);
                            })
                            ->searchable(),

                        Forms\Components\Select::make('level_id')
                            ->relationship('level', 'name', function ($query, $get) {
                                $letterId = $get('letter_id');
                                if ($letterId) {
                                    $query->where('letter_id', $letterId);
                                }
                            })
                            ->label(__('dashboard.the_level'))
                            ->exists('levels', 'id')
                            ->live()
                            ->preload()
                            ->disabled(fn(Get $get) => $get('letter_id') ? false : true)
                            ->required(),

                        Forms\Components\TextInput::make('written_word')
                            ->label(__('dashboard.written_word'))
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('spelled_word')
                            ->label(__('dashboard.spelled_word'))
                            ->maxLength(255),

                        // ---------- model_type ----------
                        Forms\Components\Select::make('model_type')
                            ->label('Model Type')
                            ->options([
                                'model1' => 'Model 1',
                                'model2' => 'Model 2',
                            ])
                            ->required()
                            ->native(false)

                            // عند التعديل
                            ->afterStateHydrated(function ($component, $state) {
                                if ($state === 0) {
                                    $component->state('model1');
                                } elseif ($state === 1) {
                                    $component->state('model2');
                                }
                            })

                            // عند الحفظ
                            ->dehydrateStateUsing(function ($state) {
                                return $state === 'model2' ? 1 : 0;
                            })

                            ->default('model1'),
                        // ---------- END model_type ----------

                          Forms\Components\Toggle::make('is_letter')
            ->label(__('Is Letter'))
            ->default(false),
                    ])->columns(2),

        

                Forms\Components\Section::make(__('dashboard.settings'))
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->label(__('dashboard.type'))
                            ->options([
                                'picture' => __('dashboard.picture'),
                                'video' => __('dashboard.video'),
                                'audio' => __('dashboard.audio')
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('attempts_to_success')
                            ->numeric()
                            ->label(__('dashboard.attempts_to_success'))
                            ->minValue(0),

                        Forms\Components\TextInput::make('success_rate')
                            ->numeric()
                            ->label(__('dashboard.success_rate'))
                            ->minValue(0)
                            ->maxValue(100)
                            ->suffix('%'),
                    ])->columns(2),

                Forms\Components\Section::make(__('dashboard.media'))
                    ->schema([
                        Forms\Components\FileUpload::make('audio')
                            ->label(__('dashboard.audio'))
                            ->directory('sounds/audio')
                            ->disk('public')
                            ->acceptedFileTypes(['audio/mpeg', 'audio/wav'])
                            ->visible(fn () => auth()->user()->can('access_audio_sound'))
                             ->maxSize(10240),

                        Forms\Components\FileUpload::make('picture')
                            ->label(__('dashboard.picture'))
                            ->directory('sounds/pictures')
                          ->visible(fn () => auth()->user()->can('access_video_sound'))
                             ->disk('public')
                            ->image()
                            ->maxSize(5120),

                        Forms\Components\FileUpload::make('xray_video')
                            ->label(__('dashboard.xray_video'))
                          
                           ->visible(fn () => auth()->user()->can('access_video_sound'))
                            ->directory('sounds/xray-videos')
                            ->acceptedFileTypes(['video/mp4'])
                            ->disk('public')
                            ->maxSize(51200),

                        Forms\Components\FileUpload::make('natural_video')
                            ->label(__('dashboard.natural_video'))
                           ->visible(fn () => auth()->user()->can('access_video_sound'))
                           ->directory('sounds/natural-videos')
                            ->acceptedFileTypes(['video/mp4'])
                            ->disk('public')
                            ->maxSize(51200),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('letter.name')
                    ->label(__('dashboard.letter'))
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('level.name')
                    ->label(__('dashboard.level_name'))
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('written_word')
                    ->label(__('dashboard.written_word'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('spelled_word')
                    ->label(__('dashboard.spelled_word'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    ->label(__('dashboard.type'))
                    ->state(fn(Sound $record) =>
                        $record->type == 'picture'
                            ? __('dashboard.picture')
                            : ($record->type == 'video'
                                ? __('dashboard.video')
                                : __('dashboard.audio'))
                    )
                    ->searchable(),

                Tables\Columns\TextColumn::make('attempts_to_success')
                    ->label(__('dashboard.attempts_to_success'))
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('success_rate')
                    ->label(__('dashboard.success_rate'))
                    ->numeric()
                    ->suffix('%')
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
        return [];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSounds::route('/'),
            'create' => Pages\CreateSound::route('/create'),
            'edit' => Pages\EditSound::route('/{record}/edit'),
        ];
    }
}
