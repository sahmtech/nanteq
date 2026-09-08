<?php

namespace App\Filament\Pages;

class Dashboard extends \Filament\Pages\Dashboard
{
    public static function canAccess(): bool
    {
        return ! auth()->user()?->isSpecialist();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return ! auth()->user()?->isSpecialist();
    }

    public function getColumns(): int|string|array
    {
        return 12;
    }
}