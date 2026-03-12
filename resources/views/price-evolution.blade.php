<x-layouts.app title="Prijsevolutie">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <!-- Header -->
        <div class="relative w-full overflow-hidden rounded-xl bg-cover bg-center h-40" style="background-image: url('/pictures/Gebakjes.jpg');">
            <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                <h1 class="text-4xl font-bold text-white drop-shadow-lg">Prijsevolutie</h1>
            </div>
        </div>

        <!-- Search and Chart Section -->
        <div class="relative flex flex-1 flex-col rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-800 overflow-hidden">
            <form method="GET" action="{{ route('price-evolution') }}" class="mb-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type:</label>
                    <div class="flex gap-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="type" value="ingredient" class="form-radio" onchange="this.form.submit()" {{ $selectedType === 'ingredient' ? 'checked' : '' }}>
                            <span class="ml-2">Ingrediënt</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="type" value="recept" class="form-radio" onchange="this.form.submit()" {{ $selectedType === 'recept' ? 'checked' : '' }}>
                            <span class="ml-2">Recept</span>
                        </label>
                    </div>
                </div>

                <div class="flex gap-2">
                    @if($selectedType === 'ingredient')
                        <x-layouts.app.dropdown
                            name="id"
                            :options="$ingredients"
                            valueField="id"
                            labelField="name"
                            placeholder="Selecteer een ingrediënt"
                            :value="$selectedId"
                        />
                    @else
                        <x-layouts.app.dropdown
                            name="id"
                            :options="$desserts"
                            valueField="id"
                            labelField="name"
                            placeholder="Selecteer een recept"
                            :value="$selectedId"
                        />
                    @endif
                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-neutral-900 px-4 py-2 text-sm font-medium text-white hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-neutral-900 focus:ring-offset-2 dark:bg-white dark:text-neutral-900 dark:hover:bg-neutral-200">
                        Zoeken
                    </button>
                </div>
            </form>

            @if(isset($priceEvolutions) && $priceEvolutions->count() > 0)
                <div style="width: 100%; height: 100%; margin: auto;">
                    <canvas id="priceChart"></canvas>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
                <script>
                    var ctx = document.getElementById('priceChart').getContext('2d');
                    var priceChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: {!! json_encode($priceEvolutions->pluck('date')->map(function($date) { return $date->format('Y-m-d'); })) !!},
                            datasets: [{
                                label: 'Prijsevolutie voor {{ $itemName }}',
                                data: {!! json_encode($priceEvolutions->pluck('price')) !!},
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 2,
                                fill: false,
                                tension: 0.1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                x: {
                                    type: 'time',
                                    time: {
                                        unit: 'month'
                                    },
                                    ticks: {
                                        color: document.documentElement.classList.contains('dark') ? 'white' : 'black'
                                    },
                                    grid: {
                                        color: document.documentElement.classList.contains('dark') ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)'
                                    }
                                },
                                y: {
                                    beginAtZero: false,
                                    ticks: {
                                        color: document.documentElement.classList.contains('dark') ? 'white' : 'black'
                                    },
                                    grid: {
                                        color: document.documentElement.classList.contains('dark') ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)'
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    labels: {
                                        color: document.documentElement.classList.contains('dark') ? 'white' : 'black'
                                    }
                                }
                            }
                        }
                    });
                </script>
            @elseif(isset($itemName))
                <p class="text-center text-gray-500 dark:text-gray-400">Geen prijsevolutie gegevens gevonden voor "{{ $itemName }}".</p>
            @else
                <p class="text-center text-gray-500 dark:text-gray-400">Selecteer een type en een item om de prijsevolutie te zien.</p>
            @endif
        </div>
    </div>
</x-layouts.app>
