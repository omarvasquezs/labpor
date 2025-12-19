<x-filament-widgets::widget>
    <x-filament::section>
        @if ($heading = $this->getHeading())
            <x-slot name="heading">
                {{ $heading }}
            </x-slot>
        @endif

        <div>
            <canvas 
                id="chart-{{ $this->getId() }}"
                @if ($maxHeight = $this->getMaxHeight())
                    style="max-height: {{ $maxHeight }}"
                @endif
            ></canvas>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.2.0/chartjs-plugin-datalabels.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const ctx = document.getElementById('chart-{{ $this->getId() }}');
        if (ctx && typeof Chart !== 'undefined') {
            // Fallback plugin to draw values when ChartDataLabels is unavailable
            const valueLabelsPlugin = {
                id: 'valueLabels',
                afterDatasetsDraw(chart) {
                    const { ctx: chartCtx } = chart;
                    const pluginOpts = chart.options.plugins?.valueLabels ?? {};
                    const color = pluginOpts.color ?? '#ffffff';
                    const fontSize = pluginOpts.fontSize ?? 14;
                    const fontWeight = pluginOpts.fontWeight ?? 'bold';
                    const offset = pluginOpts.offset ?? 12;

                    chartCtx.save();
                    chart.data.datasets.forEach((dataset, datasetIndex) => {
                        const meta = chart.getDatasetMeta(datasetIndex);
                        meta.data.forEach((element, index) => {
                            const value = dataset.data[index];
                            if (value === null || value === undefined) {
                                return;
                            }

                            const position = element.tooltipPosition();
                            chartCtx.fillStyle = color;
                            chartCtx.font = `${fontWeight} ${fontSize}px sans-serif`;
                            chartCtx.textAlign = 'center';
                            chartCtx.textBaseline = 'top';
                            chartCtx.fillText(value, position.x, position.y + offset);
                        });
                    });
                    chartCtx.restore();
                },
            };

            const plugins = [];
            if (typeof ChartDataLabels !== 'undefined') {
                plugins.push(ChartDataLabels);
            }
            plugins.push(valueLabelsPlugin);

            new Chart(ctx, {
                type: 'bar',
                data: @json($this->getCachedData()),
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        y: {
                            ticks: {
                                stepSize: 1,
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        datalabels: {
                            display: true,
                            anchor: 'center',
                            align: 'center',
                            color: 'white',
                            font: {
                                weight: 'bold',
                                size: 20
                            }
                        },
                        valueLabels: {
                            color: 'white',
                            fontSize: 14,
                            fontWeight: 'bold',
                            offset: 12
                        }
                    }
                },
                plugins
            });
        }
    }, 500);
});
</script>
