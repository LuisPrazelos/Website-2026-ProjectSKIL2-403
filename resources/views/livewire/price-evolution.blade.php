<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <!-- Header -->
    <div class="relative w-full overflow-hidden rounded-xl bg-cover bg-center h-40" style="background-image: url('/pictures/Gebakjes.jpg');">
        <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
            <h1 class="text-4xl font-bold text-white drop-shadow-lg">Prijsevolutie</h1>
        </div>
    </div>

    <!-- Search and Chart Section -->
    <div class="relative flex flex-1 flex-col rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-800 overflow-hidden">

        <!-- Filters -->
        <div class="mb-6 space-y-4">
            <div class="w-full max-w-md">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Kies een ingrediënt:
                </label>

                <select
                    wire:model.live="selectedId"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-neutral-700 dark:text-white shadow-sm focus:border-neutral-500 focus:ring-neutral-500"
                >
                    <option value="">Selecteer...</option>
                    @foreach($ingredients as $ingredient)
                        <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Chart Container -->
        <div
            wire:key="chart-container-{{ $selectedId }}"
            class="flex-1 min-h-[400px] w-full relative"
            x-data="priceEvolutionChart({
                labels: @js($chartData['labels']),
                data: @js($chartData['data']),
                unit: @js($chartData['unit']),
                itemName: @js($itemName)
            })"
            @chart-updated.window="updateChart($event.detail)"
        >
            <!-- Canvas: Altijd tonen zodat Chart.js kan init-en, maar verbergen via CSS als er geen data is -->
            <div class="w-full h-full" x-show="labels.length > 0">
                <canvas x-ref="canvas"></canvas>
            </div>

            <!-- Lege staat -->
            <div x-show="labels.length === 0" class="absolute inset-0 flex items-center justify-center text-gray-500 bg-white dark:bg-neutral-800">
                <span>
                    @if($selectedId)
                        Geen prijsgegevens beschikbaar voor "{{ $itemName }}".
                    @else
                        Selecteer een item om de grafiek te zien.
                    @endif
                </span>
            </div>
        </div>
    </div>

    <!-- Alpine Component Definition -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('priceEvolutionChart', (config) => ({
                chart: null,
                labels: config.labels || [],
                data: config.data || [],
                unit: config.unit || '',
                itemName: config.itemName || '',

                init() {
                    if (typeof Chart === 'undefined') {
                        const script = document.createElement('script');
                        script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
                        script.onload = () => {
                            if (this.labels.length > 0) this.initChart();
                        };
                        document.head.appendChild(script);
                    } else {
                        if (this.labels.length > 0) this.initChart();
                    }
                },

                updateChart(detail) {
                    this.labels = detail.labels;
                    this.data = detail.data;
                    this.unit = detail.unit;

                    if (!this.chart) {
                        this.$nextTick(() => {
                            this.initChart();
                        });
                    } else {
                        this.chart.data.labels = this.labels;
                        this.chart.data.datasets[0].data = this.data;
                        this.chart.options.plugins.title.text = 'Prijsverloop per ' + (this.unit || 'eenheid') + ' voor ' + (this.itemName || 'Selectie');
                        this.chart.update();
                    }
                },

                initChart() {
                    if (!this.$refs.canvas || this.labels.length === 0) return;

                    const ctx = this.$refs.canvas.getContext('2d');
                    if (this.chart) {
                        this.chart.destroy();
                    }

                    this.chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: this.labels,
                            datasets: [{
                                label: 'Prijs (€)',
                                data: this.data,
                                borderColor: 'rgb(75, 192, 192)',
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.3
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                intersect: false,
                                mode: 'index',
                            },
                            scales: {
                                x: {
                                    grid: { display: false }
                                },
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: (value) => '€ ' + value.toFixed(2)
                                    }
                                }
                            },
                            plugins: {
                                legend: { display: true, position: 'top' },
                                title: {
                                    display: true,
                                    text: 'Prijsverloop per ' + (this.unit || 'eenheid') + ' voor ' + (this.itemName || 'Selectie')
                                },
                                tooltip: {
                                    callbacks: {
                                        label: (context) => {
                                            let price = new Intl.NumberFormat('nl-NL', { style: 'currency', currency: 'EUR' }).format(context.parsed.y);
                                            return `Prijs: ${price} / ${this.unit || 'eenheid'}`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            }));
        });
    </script>
</div>
