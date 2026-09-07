<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LetterResource\Pages;
use App\Models\Letter;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;

class LetterResource extends Resource
{
    protected static ?string $model = Letter::class;

    public static function getNavigationGroup(): ?string
    {
        return __('dashboard.letters_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('dashboard.letters');
    }

    public static function getModelLabel(): string
    {
        return __('dashboard.letter');
    }

    public static function getPluralModelLabel(): string
    {
        return __('dashboard.letters');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Select::make('ageGroup')
                            ->label(__('dashboard.the_age_group'))
                            ->relationship('ageGroup', 'name')
                            ->exists('age_groups', 'id')
                            ->live()
                            ->preload()
                            ->required(),
                        FileUpload::make('image')
                            ->label(__('dashboard.picture'))
                            ->image()
                            ->directory('images/letters')
                            ->required(),
                    ])
                    ->columns(1)->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('dashboard.the_letter'))
                    ->searchable(),
                // ViewColumn::make('audio')
                //     ->view('filament.tables.columns.audio')
                //     ->disableClick()
                //     ->width(325),
                TextColumn::make('ageGroup.name')
                    ->label(__('dashboard.age_group_name'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ageGroup.from_age')
                    ->label(__('dashboard.from_age'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ageGroup.to_age')
                    ->label(__('dashboard.to_age'))
                    ->numeric()
                    ->sortable(),
                CheckboxColumn::make('is_demo')
                    ->label(__('dashboard.is_letter_demo'))
                    ->sortable(),
                ImageColumn::make('image')
                    ->label(__('dashboard.picture'))
                    ->rounded()
                    ->disk('public')
                    ->width(50)
                    ->height(50),
                ToggleColumn::make('is_active')
                    ->label(__('dashboard.is_active'))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('dashboard.created_at'))
                    ->dateTime('Y/m/d H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->paginated(false)
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\Action::make('viewAttachments')
                //     ->label(__('dashboard.attachments_view'))
                //     ->icon('heroicon-o-paper-clip')
                //     ->color('gray')
                //     ->modalHeading(__('dashboard.attachments'))
                //     ->modalWidth(MaxWidth::FourExtraLarge)
                //     ->modalSubmitAction(false)
                //     ->modalContent(function ($record) {
                //         $id = $record->id;
                //         if (isset($record->xray_videos) || isset($record->natural_videos)) {
                //             return view('components.attachment-viewer', compact('id'));
                //         }
                //     }),
                Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListLetters::route('/'),
            'edit' => Pages\EditLetter::route('/{record}/edit'),
        ];
    }
}
