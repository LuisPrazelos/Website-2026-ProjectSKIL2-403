<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ingredient;
use App\Models\Dessert;
use App\Models\PriceEvolution as PriceEvolutionModel;
use Livewire\Attributes\Url;
use Carbon\Carbon;

class PriceEvolution extends Component
{
    #[Url]
    public $type = 'ingredient'; // 'ingredient' of 'recept'

    #[Url]
    public $selectedId = '';

    public $chartData = [
        'labels' => [],
        'data' => [],
    ];

    public $itemName = '';

    public function updatedType()
    {
        $this->selectedId = ''; // Reset selectie bij wisselen van type
        $this->resetChart();
    }

    public function updatedSelectedId()
    {
        $this->updateChartData();
    }

    public function mount()
    {
        // Initial load als er al parameters in de URL staan
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

        if ($this->type === 'ingredient') {
            $ingredient = Ingredient::find($this->selectedId);
            if ($ingredient) {
                $this->itemName = $ingredient->name;
                $priceEvolutions = PriceEvolutionModel::where('ingredientId', $ingredient->id)
                    ->orderBy('date', 'asc')
                    ->get();
            }
        } elseif ($this->type === 'recept') {
            $dessert = Dessert::find($this->selectedId);
            if ($dessert) {
                $this->itemName = $dessert->name;
                // Logica voor recepten (indien beschikbaar)
            }
        }

        $this->chartData = [
            'labels' => $priceEvolutions->map(function ($priceEvolution) {
                return Carbon::parse($priceEvolution->date)->locale('nl_NL')->translatedFormat('d M Y');
            })->toArray(),
            'data' => $priceEvolutions->pluck('price')->toArray(),
        ];

        // Stuur expliciet een event naar de frontend om de grafiek te updaten
        $this->dispatch('chart-updated', $this->chartData);
    }

    public function resetChart()
    {
        $this->chartData = [
            'labels' => [],
            'data' => [],
        ];
        $this->itemName = '';
        $this->dispatch('chart-updated', $this->chartData);
    }

    public function render()
    {
        $ingredients = Ingredient::orderBy('name')->get();
        $desserts = Dessert::orderBy('name')->get();

        return view('livewire.price-evolution', [
            'ingredients' => $ingredients,
            'desserts' => $desserts,
        ])->layout('components.layouts.app', ['title' => 'Prijsevolutie']);
    }
}
