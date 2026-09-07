<?php

namespace App\Filament\Resources\AgeResource\Pages;

use App\Filament\Resources\AgeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAge extends EditRecord
{
    protected static string $resource = AgeResource::class;

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
