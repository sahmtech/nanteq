<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentSubscriptionResource\Pages;
use App\Filament\Resources\PaymentSubscriptionResource\RelationManagers;
use App\Models\PaymentSubscription;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentSubscriptionResource extends Resource
{
    protected static ?string $model = PaymentSubscription::class;

    public static function getNavigationGroup(): ?string
    {
        return __('dashboard.subscriptions_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('dashboard.payment_subscriptions');
    }

    public static function getModelLabel(): string
    {
        return __('dashboard.payment_subscription');
    }

    public static function getPluralModelLabel(): string
    {
        return __('dashboard.payment_subscriptions');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('subscription_id')
                    ->required()
                    ->numeric()
                    ->label(__('dashboard.subscription_id')),
                Forms\Components\TextInput::make('plan_id')
                    ->required()
                    ->numeric()
                    ->label(__('dashboard.plan_id')),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->label(__('dashboard.amount')),
                Forms\Components\DatePicker::make('payment_date')
                    ->required()
                    ->label(__('dashboard.payment_date')),
                Forms\Components\TextInput::make('payment_method')
                    ->required()
                    ->maxLength(255)
                    ->label(__('dashboard.payment_method')),
                Forms\Components\TextInput::make('transaction_id')
                    ->required()
                    ->maxLength(255)
                    ->label(__('dashboard.transaction_id')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subscription_id')
                    ->label(__('dashboard.subscription_id'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('plan_id')
                    ->label(__('dashboard.plan_id'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label(__('dashboard.amount'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_date')
                    ->label(__('dashboard.payment_date'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label(__('dashboard.payment_method'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('transaction_id')
                    ->label(__('dashboard.transaction_id'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('dashboard.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPaymentSubscriptions::route('/'),
        ];
    }
}
