<?php

namespace App\Filament\Widgets;

use App\Models\ArtRequest;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ArtRequestsByDesignerChart extends ChartWidget
{
    protected static ?string $heading = 'Solicitudes por Diseñador (En Trabajo)';

    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 1;

    protected static ?string $maxHeight = '250px';

    public function getView(): string
    {
        return $this->hasData() 
            ? 'filament.widgets.bar-chart-with-labels'
            : 'filament.widgets.custom-chart-widget';
    }

    protected function hasData(): bool
    {
        $data = $this->getData();
        foreach ($data['datasets'] as $dataset) {
            $d = $dataset['data'] ?? [];
            if ($d instanceof \Illuminate\Support\Collection) {
                $d = $d->all();
            }
            if (array_sum($d) > 0) {
                return true;
            }
        }
        return false;
    }

    protected function getData(): array
    {
        $data = ArtRequest::query()
            ->leftJoin('users', 'art_requests.designer_id', '=', 'users.id')
            ->where('art_requests.status', 'IN_PROGRESS')
            ->select('users.name as designer_name', DB::raw('count(*) as count'))
            ->groupBy('users.name')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Solicitudes',
                    'data' => $data->pluck('count'),
                    'backgroundColor' => '#3b82f6',
                ],
            ],
            'labels' => $data->map(fn ($item) => $item->designer_name ?? 'Sin Asignar'),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'ticks' => [
                        'stepSize' => 1,
                        'precision' => 0,
                    ],
                ],
            ],
            'plugins' => [
                'datalabels' => [
                    'display' => true,
                    'anchor' => 'center',
                    'align' => 'center',
                    'color' => 'white',
                    'font' => [
                        'weight' => 'bold',
                        'size' => 20,
                    ],
                ],
            ],
        ];
    }
}
