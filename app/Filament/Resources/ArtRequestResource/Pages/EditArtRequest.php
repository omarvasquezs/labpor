<?php

namespace App\Filament\Resources\ArtRequestResource\Pages;

use App\Filament\Resources\ArtRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArtRequest extends EditRecord
{
    protected static string $resource = ArtRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
