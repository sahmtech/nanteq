<?php

namespace App\Filament\Specialist\Resources;

use App\Filament\Resources\SoundProgressResource as AdminSoundProgressResource;
use App\Filament\Specialist\Resources\SoundProgressResource\Pages;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;

class SoundProgressResource extends AdminSoundProgressResource
{
    protected static ?string $slug = 'sounds-progress';

    protected static ?string $navigationIcon = 'heroicon-o-speaker-wave';

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return null;
    }

    public static function getNavigationLabel(): string
    {
        return __('dashboard.sound_progresses');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Filament::getCurrentPanel()?->getId() === 'specialist';
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->isSpecialist() ?? false;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        $specialistId = auth()->id();

        return parent::getEloquentQuery()->whereHas('trainee', function (Builder $query) use ($specialistId) {
            $query->where('followed_specialist_id', $specialistId);
        });
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSoundProgress::route('/'),
        ];
    }
}
