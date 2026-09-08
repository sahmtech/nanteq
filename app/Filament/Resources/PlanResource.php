<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlanResource\Pages;
use App\Models\Plan;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class PlanResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Plan::class;

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
            'restore',
            'restore_any',
            'force_delete',
            'force_delete_any',
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('dashboard.subscriptions_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('dashboard.plans');
    }

    public static function getModelLabel(): string
    {
        return __('dashboard.plan');
    }

    public static function getPluralModelLabel(): string
    {
        return __('dashboard.plans');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columnSpan(1)
                    ->schema([
                        TextInput::make('name')
                            ->label(__('dashboard.name'))
                            ->maxLength(255)
                            ->default(null),
                        TextInput::make('price')
                            ->label(__('dashboard.price'))
                            ->required()
                            ->numeric()
                            ->prefix('SAR')
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->maxValue(99999999),
                        TextInput::make('patiant_count')
                            ->label(__('dashboard.patiant_count'))
                            ->required()
                            ->numeric(),
                        Checkbox::make('is_for_specialists')
                            ->label(__('dashboard.is_for_specialists'))
                            ->helperText(__('dashboard.is_for_specialists_helper'))
                            ->default(false),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('dashboard.name'))
                    ->searchable(),
                TextColumn::make('price')
                    ->label(__('dashboard.price'))
                    ->money('SAR')
                    ->sortable(),
                TextColumn::make('patiant_count')
                    ->label(__('dashboard.patiant_count'))
                    ->badge()
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_for_specialists')
                    ->label(__('dashboard.is_for_specialists'))
                    ->boolean()
                    ->sortable(),
                TextColumn::make('subscriptions')
                    ->label(__('dashboard.subscribers_count'))
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->getStateUsing(function ($record) {
                        return $record->subscriptions()->count();
                    }),
                TextColumn::make('created_at')
                    ->label(__('dashboard.created_at'))
                    ->dateTime('Y/m/d H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('dashboard.updated_at'))
                    ->dateTime('Y/m/d H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label(__('dashboard.deleted_at'))
                    ->dateTime('Y/m/d H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\TernaryFilter::make('is_for_specialists')
                    ->label(__('dashboard.is_for_specialists')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
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
            'index' => Pages\ListPlans::route('/'),
            'create' => Pages\CreatePlan::route('/create'),
            'edit' => Pages\EditPlan::route('/{record}/edit'),
        ];
    }
}
