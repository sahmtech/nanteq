<?php

namespace App\Filament\Resources\AgeGroupResource\Pages;

use App\Filament\Resources\AgeGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAgeGroup extends CreateRecord
{
    protected static string $resource = AgeGroupResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
