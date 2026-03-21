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
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Type:</label>
                <div class="flex gap-4">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" wire:model.live="type" value="ingredient" class="form-radio text-neutral-900 focus:ring-neutral-900">
                        <span class="ml-2 text-gray-700 dark:text-gray-300">Ingrediënt</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" wire:model.live="type" value="recept" class="form-radio text-neutral-900 focus:ring-neutral-900">
                        <span class="ml-2 text-gray-700 dark:text-gray-300">Recept</span>
                    </label>
                </div>
            </div>

            <div class="w-full max-w-md">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ $type === 'ingredient' ? 'Kies een ingrediënt:' : 'Kies een recept:' }}
                </label>

                <select
                    wire:model.live="selectedId"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-neutral-700 dark:text-white shadow-sm focus:border-neutral-500 focus:ring-neutral-500"
                >
                    <option value="">Selecteer...</option>
                    @if($type === 'ingredient')
                        @foreach($ingredients as $ingredient)
                            <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                        @endforeach
                    @else
                        @foreach($desserts as $dessert)
                            <option value="{{ $dessert->id }}">{{ $dessert->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>

        <!-- Chart Container -->
        <div
            wire:key="chart-{{ $type }}-{{ $selectedId }}"
            class="flex-1 min-h-[400px] w-full relative"
            x-data="priceEvolutionChart({
                labels: @js($chartData['labels']),
                data: @js($chartData['data']),
                itemName: @js($itemName)
            })"
        >
            <!-- Canvas: Toon alleen als er data is -->
            @if(count($chartData['labels']) > 0)
                <div class="w-full h-full">
                    <canvas x-ref="canvas"></canvas>
                </div>
            @else
                <!-- Lege staat -->
                <div class="absolute inset-0 flex items-center justify-center text-gray-500 bg-white dark:bg-neutral-800">
                    <span>
                        @if($selectedId)
                            Geen prijsgegevens beschikbaar voor "{{ $itemName }}".
                        @else
                            Selecteer een item om de grafiek te zien.
                        @endif
                    </span>
                </div>
            @endif
        </div>
    </div>

    <!-- Alpine Component Definition -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('priceEvolutionChart', (config) => ({
                chart: null,
                labels: config.labels || [],
                data: config.data || [],
                itemName: config.itemName || '',

                init() {
                    if (!this.labels || this.labels.length === 0) return;

                    if (typeof Chart === 'undefined') {
                        const script = document.createElement('script');
                        script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
                        script.onload = () => this.initChart();
                        document.head.appendChild(script);
                    } else {
                        this.initChart();
                    }
                },

                initChart() {
                    if (!this.$refs.canvas) return;

                    const ctx = this.$refs.canvas.getContext('2d');

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
                                    type: 'category',
                                    grid: {
                                        display: false
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return '€ ' + value.toFixed(2);
                                        }
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top'
                                },
                                title: {
                                    display: true,
                                    text: 'Prijsverloop voor ' + (this.itemName || 'Selectie')
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.dataset.label || '';
                                            if (label) {
                                                label += ': ';
                                            }
                                            if (context.parsed.y !== null) {
                                                label += new Intl.NumberFormat('nl-NL', { style: 'currency', currency: 'EUR' }).format(context.parsed.y);
                                            }
                                            return label;
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
