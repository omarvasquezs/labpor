<?php

namespace App\Filament\Resources\ProductionStageResource\Pages;

use App\Filament\Resources\ProductionStageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductionStage extends EditRecord
{
    protected static string $resource = ProductionStageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
