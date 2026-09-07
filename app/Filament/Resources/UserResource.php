<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Carbon\Carbon;

class UserResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = User::class;

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

    public static function getNavigationBadgeTooltip(): ?string
    {
        return __('dashboard.The number of users');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('dashboard.users_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('dashboard.users');
    }

    public static function getModelLabel(): string
    {
        return __('dashboard.user');
    }

    public static function getPluralModelLabel(): string
    {
        return __('dashboard.users');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    // public static function canEdit($record): bool
    // {
    //     /** @var \App\Models\User $user */
    //     $user = auth()->user();
    //     return $record->role->name !== 'owner' && $user->hasPermission('user.update');
    // }

    public static function canDelete($record): bool
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $hasSuperAdminRole = $record->roles->contains(function ($role) {
            return $role->name === 'super_admin';
        });

        return !$hasSuperAdminRole && $user->can('delete_user');
    }

    public static function canEdit($record): bool
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $hasSuperAdminRole = $record->roles->contains(function ($role) {
            return $role->name === 'super_admin';
        });

        return !$hasSuperAdminRole && $user->can('update_user');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label(__('dashboard.name'))
                            ->minLength(2)->maxLength(15)->string()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label(__('dashboard.email'))
                            ->email()
                            ->unique(User::class, 'email', ignoreRecord: true)
                            ->required()
                            ->maxLength(255),
                        TextInput::make('phone_number')
                            ->label(__('dashboard.phone_number'))
                            ->tel()
                            ->maxLength(25)
                            ->default(null),
                        Select::make('roles')
                            ->label(__('filament-shield::filament-shield.resource.label.roles'))
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable(),
                        TextInput::make('password')
                            ->label(__('dashboard.password'))
                            ->password()
                            ->required()
                            ->revealable()
                            ->hiddenOn('edit')
                            ->maxLength(255),
                        ToggleButtons::make('type')
                            ->label(__('dashboard.type'))
                            ->inline()
                            ->options([
                                'personal' => __('dashboard.personal'),
                                'parent' => __('dashboard.parent'),
                            ])
                            ->colors([
                                'personal' => 'info',
                                'parent' => 'success',
                            ])
                            ->required(),
                        FileUpload::make('image')
                            ->label(__('dashboard.image'))
                            ->disk('public')
                            ->directory('images/profile_pictures')
                            ->previewable(false)
                            ->image(),
                        Hidden::make('email_verified_at')->default(now()),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('dashboard.id'))
                    ->sortable(),
                ImageColumn::make('profile_picture')
                    ->disk('public')
                    ->label(__('dashboard.image'))
                    ->circular(),
                // TextColumn::make('type')
                //     ->label(__('dashboard.type'))
                //     ->badge()
                //     ->color(function ($record) {
                //         return $record->type == 'personal' ? 'info' : ($record->type == 'parent' ? 'danger' : ($record->type == 'specialist' ?? 'success'));
                //     })
                //     ->formatStateUsing(fn($record) =>
                //         $record->type == 'personal' ? __('dashboard.personal') : ($record->type == 'parent' ? __('dashboard.parent') : ($record->type == 'specialist' ?? __('dashboard.specialist')))),
                TextColumn::make('name')
                    ->label(__('dashboard.name'))
                    ->searchable(),
                TextColumn::make('year_of_birth')
    ->label(__('dashboard.age'))
    ->getStateUsing(function ($record) {
        if (!$record->year_of_birth) {
            return '-';
        }

        return Carbon::now()->year - (int) $record->year_of_birth;
    })
    // TextColumn::make('year_of_birth')
    // ->label('TEST')
                    ->searchable(),
                TextColumn::make('email')
                    ->label(__('dashboard.email'))
                    ->searchable(),
                TextColumn::make('phone_number')
                    ->label(__('dashboard.phone_number'))
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->label(__('filament-shield::filament-shield.resource.label.roles'))
                    ->badge()
                    ->color(function ($record) {
                        return $record->roles == 'owner' ? 'danger' : 'warning';
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('subscription.start_date')
                    ->label(__('dashboard.subscription_start_date'))
                    ->badge()
                    ->dateTime('Y/m/d'),
                TextColumn::make('subscription.end_date')
                    ->label(__('dashboard.subscription_end_date'))
                    ->badge()
                    ->dateTime('Y/m/d'),
                TextColumn::make('created_at')
                    ->label(__('dashboard.created_at'))
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
