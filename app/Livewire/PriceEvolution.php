<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ingredient;
use App\Models\Package;
use App\Models\PriceEvolution as PriceEvolutionModel;
use Livewire\Attributes\Url;
use Carbon\Carbon;

class PriceEvolution extends Component
{
    #[Url]
    public string $mode = 'ingredients'; // 'ingredients' or 'packages'

    #[Url]
    public $selectedId = '';

    public $chartData = [
        'labels' => [],
        'datasets' => [],
    ];

    public $itemName = '';

    public function updatedMode()
    {
        $this->selectedId = '';
        $this->resetChart();
    }

    public function updatedSelectedId()
    {
        $this->updateChartData();
    }

    public function mount()
    {
        if ($this->selectedId) {
            $this->updateChartData();
        }
    }

    public function updateChartData()
    {
        if (!$this->selectedId) {
            $this->resetChart();
            return;
        }

        if ($this->mode === 'packages') {
            $this->updatePackageChartData();
        } else {
            $this->updateIngredientChartData();
        }
    }

    protected function updateIngredientChartData()
    {
        $priceEvolutions = collect();
        $this->itemName = '';
        $unitName = '';

        $ingredient = Ingredient::with('measurementUnit')->find($this->selectedId);
        if ($ingredient) {
            $this->itemName = $ingredient->name;
            $unitName = $ingredient->measurementUnit->name ?? '';
            $priceEvolutions = PriceEvolutionModel::where('ingredientId', $ingredient->id)
                ->orderBy('date', 'asc')
                ->get();
        }

        $labels = $priceEvolutions->map(function ($priceEvolution) {
            return Carbon::parse($priceEvolution->date)->locale('nl_NL')->translatedFormat('d M Y');
        })->toArray();

        $data = $priceEvolutions->pluck('price')->toArray();

        $this->chartData = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => $this->itemName . ($unitName ? ' (€/' . $unitName . ')' : ''),
                    'data' => $data,
                    'borderColor' => 'rgb(75, 192, 192)',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.15)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.3,
                    'unit' => $unitName,
                ],
            ],
        ];

        $this->dispatch('chart-updated', $this->chartData);
    }

    protected function updatePackageChartData()
    {
        $this->itemName = '';

        // If 'all' is selected, show all packages together
        if ($this->selectedId === 'all') {
            $packages = Package::all();
        } else {
            $packages = Package::where('id', $this->selectedId)->get();
        }

        if ($packages->isEmpty()) {
            $this->resetChart();
            return;
        }

        // Collect all unique dates across all packages
        $allDates = collect();
        foreach ($packages as $package) {
            $history = $package->sorted_price_history;
            foreach ($history as $entry) {
                $allDates->push($entry['date']);
            }
        }
        $allDates = $allDates->unique()->sort()->values();

        $labels = $allDates->map(function ($date) {
            return Carbon::parse($date)->locale('nl_NL')->translatedFormat('d M Y');
        })->toArray();

        $colors = [
            ['border' => 'rgb(249, 115, 22)',  'bg' => 'rgba(249, 115, 22, 0.15)'],   // orange
            ['border' => 'rgb(99, 102, 241)',   'bg' => 'rgba(99, 102, 241, 0.15)'],   // indigo
            ['border' => 'rgb(16, 185, 129)',   'bg' => 'rgba(16, 185, 129, 0.15)'],   // emerald
            ['border' => 'rgb(239, 68, 68)',    'bg' => 'rgba(239, 68, 68, 0.15)'],    // red
            ['border' => 'rgb(234, 179, 8)',    'bg' => 'rgba(234, 179, 8, 0.15)'],    // yellow
        ];

        $datasets = [];
        foreach ($packages as $index => $package) {
            $history = collect($package->sorted_price_history)->keyBy('date');
            $data = $allDates->map(fn($date) => isset($history[$date]) ? (float) $history[$date]['price'] : null)->toArray();

            $color = $colors[$index % count($colors)];
            $datasets[] = [
                'label' => $package->name . ($package->is_standard ? ' (gratis)' : ''),
                'data' => $data,
                'borderColor' => $color['border'],
                'backgroundColor' => $color['bg'],
                'borderWidth' => 2,
                'fill' => false,
                'tension' => 0.3,
                'spanGaps' => true,
            ];
        }

        if ($packages->count() === 1) {
            $this->itemName = $packages->first()->name;
        } else {
            $this->itemName = 'Alle pakketten';
        }

        $this->chartData = [
            'labels' => $labels,
            'datasets' => $datasets,
        ];

        $this->dispatch('chart-updated', $this->chartData);
    }

    public function resetChart()
    {
        $this->chartData = [
            'labels' => [],
            'datasets' => [],
        ];
        $this->itemName = '';
        $this->dispatch('chart-updated', $this->chartData);
    }

    public function render()
    {
        $ingredients = Ingredient::orderBy('name')->get();
        $packages = Package::orderByDesc('is_standard')->orderBy('name')->get();

        return view('livewire.price-evolution', [
            'ingredients' => $ingredients,
            'packages' => $packages,
        ])->layout('components.layouts.app', ['title' => 'Prijsevolutie']);
    }
}
