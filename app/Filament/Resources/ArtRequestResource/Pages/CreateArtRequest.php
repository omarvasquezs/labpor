<?php

namespace App\Filament\Resources\ArtRequestResource\Pages;

use App\Filament\Resources\ArtRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateArtRequest extends CreateRecord
{
    protected static string $resource = ArtRequestResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?\Filament\Notifications\Notification
    {
        return \Filament\Notifications\Notification::make()
            ->success()
            ->title('Solicitud creada')
            ->body('La solicitud #' . $this->record->id . ' ha sido creada exitosamente.');
    }
}
