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
        $user = auth()->user();
        $query = ArtRequest::query();

        if ($user && $user->isDesigner()) {
            $query->where('designer_id', $user->id);
        }

        return [
            Stat::make('Solicitudes Activas', (clone $query)->whereIn('status', ['PENDING', 'IN_PROGRESS', 'OBSERVED'])->count()),
            Stat::make('En Trabajo', (clone $query)->where('status', 'IN_PROGRESS')->count()),
        ];
    }
}
