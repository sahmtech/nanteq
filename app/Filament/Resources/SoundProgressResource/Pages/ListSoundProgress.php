<?php

namespace App\Filament\Resources\SoundProgressResource\Pages;

use App\Filament\Resources\SoundProgressResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSoundProgress extends ListRecords
{
    protected static string $resource = SoundProgressResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
