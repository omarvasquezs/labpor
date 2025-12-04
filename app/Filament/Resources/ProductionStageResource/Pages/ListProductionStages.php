<?php

namespace App\Filament\Resources\ProductionStageResource\Pages;

use App\Filament\Resources\ProductionStageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductionStages extends ListRecords
{
    protected static string $resource = ProductionStageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
