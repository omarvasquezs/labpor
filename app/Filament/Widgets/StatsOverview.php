<?php

namespace App\Filament\Widgets;

use App\Models\ArtRequest;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';

    protected function getColumns(): int
    {
        return 2;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Solicitudes Activas', ArtRequest::whereIn('status', ['PENDING', 'IN_PROGRESS', 'OBSERVED'])->count()),
            Stat::make('En Trabajo', ArtRequest::where('status', 'IN_PROGRESS')->count()),
        ];
    }
}
