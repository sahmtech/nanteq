<?php

namespace App\Filament\Resources\AgeGroupResource\Pages;

use App\Filament\Resources\AgeGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAgeGroups extends ListRecords
{
    protected static string $resource = AgeGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
