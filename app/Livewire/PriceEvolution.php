<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ingredient;
use App\Models\PriceEvolution as PriceEvolutionModel;
use Livewire\Attributes\Url;
use Carbon\Carbon;

class PriceEvolution extends Component
{
    #[Url]
    public $selectedId = '';

    public $chartData = [
        'labels' => [],
        'data' => [],
        'unit' => '',
    ];

    public $itemName = '';

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

        $this->chartData = [
            'labels' => $priceEvolutions->map(function ($priceEvolution) {
                return Carbon::parse($priceEvolution->date)->locale('nl_NL')->translatedFormat('d M Y');
            })->toArray(),
            'data' => $priceEvolutions->pluck('price')->toArray(),
            'unit' => $unitName,
        ];

        $this->dispatch('chart-updated', $this->chartData);
    }

    public function resetChart()
    {
        $this->chartData = [
            'labels' => [],
            'data' => [],
            'unit' => '',
        ];
        $this->itemName = '';
        $this->dispatch('chart-updated', $this->chartData);
    }

    public function render()
    {
        $ingredients = Ingredient::orderBy('name')->get();

        return view('livewire.price-evolution', [
            'ingredients' => $ingredients,
        ])->layout('components.layouts.app', ['title' => 'Prijsevolutie']);
    }
}
