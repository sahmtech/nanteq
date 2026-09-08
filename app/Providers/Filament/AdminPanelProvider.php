<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(\App\Filament\Auth\Login::class)
            ->profile()
            ->brandName('Nanteq')
            ->colors([
                'primary' => Color::hex('#0083a0'),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label(fn(): string => __('dashboard.users_management'))
                    ->icon('heroicon-o-users')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label(fn(): string => __('dashboard.reports_management'))
                    ->icon('heroicon-o-flag')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label(fn(): string => __('dashboard.letters_management'))
                    ->icon('heroicon-o-book-open')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label(fn(): string => __('dashboard.level_sounds_management'))
                    ->icon('heroicon-o-star')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label(fn(): string => __('dashboard.trainees_management'))
                    ->icon('heroicon-o-user-circle')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label(fn(): string => __('dashboard.subscriptions_management'))
                    ->icon('heroicon-o-inbox-stack')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label(fn(): string => __('dashboard.coupons_management'))
                    ->icon('heroicon-o-receipt-percent')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label(fn(): string => __('dashboard.levels_management'))
                    ->icon('heroicon-o-list-bullet')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label(fn(): string => __('dashboard.settings'))
                    ->icon('heroicon-o-cog-6-tooth')
                    ->collapsed(),
            ])
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make()
            ])
            ->sidebarFullyCollapsibleOnDesktop()
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->databaseNotifications();
    }
}
