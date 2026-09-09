<?php

namespace App\Filament\Specialist\Resources\SoundProgressResource\Pages;

use App\Filament\Specialist\Resources\SoundProgressResource;
use Filament\Resources\Pages\ListRecords;

class ListSoundProgress extends ListRecords
{
    protected static string $resource = SoundProgressResource::class;

    public function mount(): void
    {
        session()->forget('specialist_just_registered');

        parent::mount();
    }
}
