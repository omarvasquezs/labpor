<?php

namespace App\Filament\Widgets;

use App\Models\ArtRequest;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ArtRequestsChart extends ChartWidget
{
    protected static ?string $heading = 'Solicitudes por Área (En Trabajo)';

    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 1;

    protected static ?string $maxHeight = '250px';

    public function getView(): string
    {
        return $this->hasData() 
            ? 'filament-widgets::chart-widget' 
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
            ->join('orders', 'art_requests.order_id', '=', 'orders.id')
            ->where('art_requests.status', 'IN_PROGRESS')
            ->select('orders.type', DB::raw('count(*) as count'))
            ->groupBy('orders.type')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Solicitudes',
                    'data' => $data->pluck('count'),
                    'backgroundColor' => ['#f59e0b', '#10b981', '#3b82f6', '#ef4444'], // Optional: Add colors for better doughnut
                ],
            ],
            'labels' => $data->pluck('type'),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'x' => ['display' => false],
                'y' => ['display' => false],
            ],
            'plugins' => [
                'datalabels' => [
                    'display' => true,
                    'color' => 'white',
                    'font' => [
                        'weight' => 'bold',
                    ],
                ],
            ],
        ];
    }
}
