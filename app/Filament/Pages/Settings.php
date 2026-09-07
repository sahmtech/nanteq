<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Textarea;
use Filament\Pages\Page;
use Closure;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Outerweb\FilamentSettings\Filament\Pages\Settings as BaseSettings;
class Settings extends BaseSettings
{

    protected static ?string $navigationIcon = '';

    public static function canAccess(): bool
    {
        return auth()->user()->can('page_Settings');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('dashboard.settings');
    }

    public static function getNavigationLabel(): string
    {
        return __('dashboard.system_settings');
    }

    public static function getModelLabel(): string
    {
        return __('dashboard.setting');
    }

    public static function getPluralModelLabel(): string
    {
        return __('dashboard.settings');
    }

    public function getTitle(): string
    {
        return __('dashboard.settings');
    }
    public function schema(): array|Closure
    {
        return [
            Tabs::make('Settings')
                ->schema([
                    Tabs\Tab::make('General')
                        ->label(__('dashboard.general'))
                        ->columns(2)
                        ->schema([
                            TextInput::make('app_name')
                                ->maxLength(255)
                                ->label(__('dashboard.application_name')),
                            TextInput::make('app_description')
                                ->label(__('dashboard.application_description')),
                            Textarea::make('app_terms')
                                ->columnSpan(2)
                                ->maxLength(65535)
                                ->label(__('dashboard.application_terms')),
                            Textarea::make('app_policy')
                                ->columnSpan(2)
                                ->maxLength(65535)
                                ->label(__('dashboard.application_policy')),
                        ]),
                    Tabs\Tab::make('Contact us')
                        ->columns(2)
                        ->label(__('dashboard.contact_us'))
                        ->schema([
                            TextInput::make('contacts.phone')
                                ->suffixIcon('heroicon-o-phone')
                                ->label(__('dashboard.phone')),
                            TextInput::make('contacts.email')
                                ->suffixIcon('heroicon-o-at-symbol')
                                ->email()
                                ->label(__('dashboard.email')),
                        ]),

                    Tabs\Tab::make('About us')
                        ->label(__('dashboard.about_us'))
                        ->schema([
                            MarkdownEditor::make('about.who_are_we')
                                ->label(__('dashboard.who_are_we'))
                                ->disableToolbarButtons([
                                    'attachFiles',
                                ]),
                            MarkdownEditor::make('about.our_services')
                                ->label(__('dashboard.our_services'))
                                ->disableToolbarButtons([
                                    'attachFiles',
                                ]),
                        ]),

                    Tabs\Tab::make('Social Media')
                        ->columns(2)
                        ->label(__('dashboard.social_media'))
                        ->schema([
                            TextInput::make('media.facebook')
                                ->suffixIcon('heroicon-m-globe-alt')
                                ->url()
                                ->label(__('dashboard.facebook')),
                            TextInput::make('media.instagram')
                                ->suffixIcon('heroicon-m-globe-alt')
                                ->url()
                                ->label(__('dashboard.instagram')),
                            TextInput::make('media.linkedin')
                                ->suffixIcon('heroicon-m-globe-alt')
                                ->url()
                                ->label(__('dashboard.linkedin')),
                            TextInput::make('media.threads')
                                ->suffixIcon('heroicon-m-globe-alt')
                                ->url()
                                ->label(__('dashboard.threads')),
                        ]),
                ]),
        ];
    }
    public function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('dashboard.save'))
                ->submit('data')
                ->keyBindings(['mod+s'])
        ];
    }
}
