<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubscriptionResource\Pages;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubscriptionResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Subscription::class;

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
        return __('dashboard.subscriptions');
    }

    public static function getModelLabel(): string
    {
        return __('dashboard.subscription');
    }

    public static function getPluralModelLabel(): string
    {
        return __('dashboard.subscriptions');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('plan_id')
                    ->label(__('dashboard.package'))
                    ->relationship('package')
                    ->exists('packages', 'id')
                    ->live()
                    ->preload()
                    ->getOptionLabelFromRecordUsing(fn(Plan $record) => "{$record->name}"),
                Select::make('user_id')
                    ->label(__('dashboard.user'))
                    ->relationship('user')
                    ->exists('users', 'id')
                    ->live()
                    ->preload()
                    ->getOptionLabelFromRecordUsing(fn(User $record) => "{$record->name} - {$record->phone_number}"),
                DatePicker::make('start_date')
                    ->label(__('dashboard.start_date'))
                    ->required(),
                DatePicker::make('end_date')
                    ->label(__('dashboard.end_date')),
                Select::make('status')
                    ->label(__('dashboard.status'))
                    ->required()
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->default('active'),
                Toggle::make('renew')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('dashboard.id'))
                    ->sortable(),
                TextColumn::make('package.name')
                    ->label(__('dashboard.package_name')),
                TextColumn::make('user.name')
                    ->label(__('dashboard.user_name')),
                TextColumn::make('start_date')
                    ->label(__('dashboard.start_date'))
                    ->badge()
                    ->color('gray')
                    ->date('Y/m/d')
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label(__('dashboard.end_date'))
                    ->badge()
                    ->color('gray')
                    ->date('Y/m/d')
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('dashboard.status'))
                    ->searchable(),
                IconColumn::make('renew')
                    ->label(__('dashboard.auto_renew'))
                    ->boolean(),
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
            'index' => Pages\ListSubscriptions::route('/'),
            'create' => Pages\CreateSubscription::route('/create'),
            'edit' => Pages\EditSubscription::route('/{record}/edit'),
        ];
    }
}
