<?php

namespace App\Filament\Resources\AgeGroupResource\Pages;

use App\Filament\Resources\AgeGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAgeGroup extends EditRecord
{
    protected static string $resource = AgeGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
