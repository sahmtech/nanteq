<?php

namespace App\Filament\Resources\LevelProgressResource\Pages;

use App\Filament\Resources\LevelProgressResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLevelProgress extends EditRecord
{
    protected static string $resource = LevelProgressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
