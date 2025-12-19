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
        if (ctx && typeof Chart !== 'undefined' && typeof ChartDataLabels !== 'undefined') {
            new Chart(ctx, {
                type: 'doughnut',
                data: @json($this->getCachedData()),
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        x: { display: false },
                        y: { display: false }
                    },
                    plugins: {
                        legend: {
                            position: 'right'
                        },
                        datalabels: {
                            display: true,
                            color: 'white',
                            font: {
                                weight: 'bold',
                                size: 14
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });
        }
    }, 500);
});
</script>
