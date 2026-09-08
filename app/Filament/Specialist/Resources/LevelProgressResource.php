<?php

namespace App\Filament\Specialist\Resources;

use App\Filament\Resources\LevelProgressResource as AdminLevelProgressResource;
use App\Filament\Specialist\Resources\LevelProgressResource\Pages;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;

class LevelProgressResource extends AdminLevelProgressResource
{
    protected static ?string $slug = 'stages-progress';

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return null;
    }

    public static function getNavigationLabel(): string
    {
        return __('dashboard.level_progresses');
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
            'index' => Pages\ListLevelProgress::route('/'),
        ];
    }
}
