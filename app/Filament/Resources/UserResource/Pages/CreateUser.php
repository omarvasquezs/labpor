<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

use Filament\Notifications\Notification;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        session()->flash('custom_success', '¡Usuario creado! El usuario ha sido registrado exitosamente en el sistema.');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return null;
    }
}
