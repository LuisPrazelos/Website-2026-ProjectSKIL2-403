<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <!-- Header -->
    <div class="relative w-full overflow-hidden rounded-xl bg-cover bg-center h-40" style="background-image: url('/pictures/Gebakjes.jpg');">
        <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
            <h1 class="text-4xl font-bold text-white drop-shadow-lg">Prijsevolutie</h1>
        </div>
    </div>

    <!-- Main Section -->
    <div 
        class="relative flex flex-1 flex-col rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-800 overflow-hidden"
        x-data="priceEvolutionChart({
            labels: @js($chartData['labels']),
            datasets: @js($chartData['datasets']),
            itemName: @js($itemName)
        })"
        @chart-updated.window="updateChart($event.detail[0])"
    >
        <!-- Mode Tabs -->
        <div class="mb-6 flex gap-2 border-b border-gray-200 dark:border-neutral-700">
            <button
                wire:click="$set('mode', 'ingredients')"
                id="tab-ingredients"
                class="relative px-5 py-2.5 text-sm font-semibold transition-all duration-200 rounded-t-lg focus:outline-none
                    {{ $mode === 'ingredients'
                        ? 'text-orange-600 dark:text-orange-400 border-b-2 border-orange-500 -mb-px bg-orange-50 dark:bg-orange-900/20'
                        : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-neutral-700/50' }}"
            >
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                    Ingrediënten
                </span>
            </button>
            <button
                wire:click="$set('mode', 'packages')"
                id="tab-packages"
                class="relative px-5 py-2.5 text-sm font-semibold transition-all duration-200 rounded-t-lg focus:outline-none
                    {{ $mode === 'packages'
                        ? 'text-orange-600 dark:text-orange-400 border-b-2 border-orange-500 -mb-px bg-orange-50 dark:bg-orange-900/20'
                        : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-neutral-700/50' }}"
            >
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    Pakketten
                </span>
            </button>
        </div>

        <!-- Filters -->
        <div class="mb-6">
            @if($mode === 'ingredients')
                <div class="w-full max-w-md">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Kies een ingrediënt:
                    </label>
                    <select
                        wire:model.live="selectedId"
                        id="ingredient-select"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-neutral-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500"
                    >
                        <option value="">Selecteer een ingrediënt...</option>
                        @foreach($ingredients as $ingredient)
                            <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                        @endforeach
                    </select>
                </div>
            @else
                <div class="w-full max-w-md">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Kies een pakket:
                    </label>
                    <select
                        wire:model.live="selectedId"
                        id="package-select"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-neutral-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500"
                    >
                        <option value="">Selecteer een pakket...</option>
                        <option value="all">📊 Alle pakketten vergelijken</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}">
                                {{ $package->name }}
                                @if($package->is_standard)
                                    (gratis)
                                @else
                                    — €{{ number_format((float) $package->price, 2, ',', '.') }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>

        <!-- Chart Container -->
        <div class="flex-1 min-h-[420px] w-full relative">
            <!-- Canvas -->
            <div 
                class="w-full h-full" 
                x-show="labels && labels.length > 0" 
                wire:ignore
                x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 transform scale-[0.98]"
                x-transition:enter-end="opacity-100 transform scale-100"
            >
                <canvas x-ref="canvas"></canvas>
            </div>

            <!-- Lege staat -->
            <div x-show="!labels || labels.length === 0" class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 dark:text-gray-500 bg-white dark:bg-neutral-800">
                <svg class="w-16 h-16 mb-4 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span class="text-sm">
                    Selecteer een item om de grafiek te zien.
                </span>
            </div>
        </div>

        <!-- Package price history table -->
        @if($mode === 'packages' && $selectedId && $selectedId !== 'all' && count($chartData['datasets']) > 0)
            <div class="mt-6 border-t border-gray-200 dark:border-neutral-700 pt-4">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Prijshistoriek</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead>
                            <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <th class="pb-2 pr-8">Datum</th>
                                <th class="pb-2">Prijs</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-neutral-700">
                            @foreach($chartData['labels'] as $index => $label)
                                <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700/30 transition-colors">
                                    <td class="py-2 pr-8 text-gray-600 dark:text-gray-400">{{ $label }}</td>
                                    <td class="py-2 font-medium text-gray-900 dark:text-gray-100">
                                        @php $price = $chartData['datasets'][0]['data'][$index] ?? null; @endphp
                                        @if($price !== null)
                                            € {{ number_format($price, 2, ',', '.') }}
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <!-- Alpine Component Definition -->
    <script data-navigate-once>
        (function() {
            const registerChart = () => {
                if (window.priceEvolutionChartRegistered) return;
                window.priceEvolutionChartRegistered = true;

                Alpine.data('priceEvolutionChart', (config) => {
                    let chartInstance = null;

                    return {
                        labels: config.labels || [],
                        datasets: config.datasets || [],
                        itemName: config.itemName || '',

                        init() {
                            if (this.labels && this.labels.length > 0) {
                                this.$nextTick(() => this.initChart());
                            }
                        },

                        updateChart(data) {
                            this.labels = data.labels || [];
                            this.datasets = data.datasets || [];
                            this.itemName = data.itemName || '';

                            this.$nextTick(() => {
                                this.initChart();
                            });
                        },

                        buildDatasets() {
                            return this.datasets.map(ds => ({
                                label: ds.label,
                                data: ds.data,
                                borderColor: ds.borderColor,
                                backgroundColor: ds.backgroundColor,
                                borderWidth: ds.borderWidth ?? 2,
                                fill: ds.fill ?? false,
                                tension: ds.tension ?? 0.3,
                                spanGaps: ds.spanGaps ?? true,
                                pointRadius: 5,
                                pointHoverRadius: 7,
                            }));
                        },

                        chartTitle() {
                            return this.itemName ? 'Prijsverloop voor ' + this.itemName : 'Prijsevolutie';
                        },

                        initChart() {
                            if (!this.$refs.canvas || typeof Chart === 'undefined') return;

                            // STAP 1: Ruim ALLES op wat op dit canvas staat (zeer belangrijk!)
                            const existingChart = Chart.getChart(this.$refs.canvas);
                            if (existingChart) {
                                existingChart.destroy();
                            }

                            if (chartInstance) {
                                chartInstance.destroy();
                                chartInstance = null;
                            }

                            // STAP 2: Als we geen data hebben, stoppen we hier (grafiek is nu leeg)
                            if (!this.labels || this.labels.length === 0) {
                                return;
                            }

                            // STAP 3: Teken de nieuwe grafiek
                            const ctx = this.$refs.canvas.getContext('2d');
                            chartInstance = new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: this.labels,
                                    datasets: this.buildDatasets(),
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    animation: {
                                        duration: 1000,
                                        easing: 'easeOutQuart',
                                    },
                                    transitions: {
                                        active: {
                                            animation: {
                                                duration: 400
                                            }
                                        }
                                    },
                                    interaction: {
                                        intersect: false,
                                        mode: 'index',
                                    },
                                    scales: {
                                        x: {
                                            grid: { display: false },
                                            ticks: { maxRotation: 45 }
                                        },
                                        y: {
                                            beginAtZero: true,
                                            ticks: {
                                                callback: (value) => '€ ' + value.toFixed(2)
                                            },
                                            grid: {
                                                color: 'rgba(150,150,150,0.1)'
                                            }
                                        }
                                    },
                                    plugins: {
                                        legend: {
                                            display: true,
                                            position: 'top',
                                        },
                                        title: {
                                            display: true,
                                            text: this.chartTitle(),
                                            font: { size: 15, weight: 'bold' },
                                            padding: { bottom: 16 }
                                        },
                                        tooltip: {
                                            callbacks: {
                                                label: (context) => {
                                                    if (context.parsed.y === null) return null;
                                                    const price = new Intl.NumberFormat('nl-NL', {
                                                        style: 'currency',
                                                        currency: 'EUR'
                                                    }).format(context.parsed.y);
                                                    return ` ${context.dataset.label}: ${price}`;
                                                }
                                            }
                                        }
                                    }
                                }
                            });
                        }
                    };
                });
            };

            if (window.Alpine) {
                registerChart();
            } else {
                document.addEventListener('alpine:init', registerChart);
            }
        })();
    </script>
</div>
