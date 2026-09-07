<?php

namespace App\Filament\Resources\LevelProgressResource\Pages;

use App\Filament\Resources\LevelProgressResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLevelProgress extends ListRecords
{
    protected static string $resource = LevelProgressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
