<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Theme;
use Illuminate\Validation\Rule;

class ThemeManager extends Component
{
    public $themes = [];
    public $showForm = false;
    public $editingThemeId = null;
    public $themeName = '';
    public $themeDescription = '';
    public $themePrice = '';
    public $searchQuery = '';

    public function mount()
    {
        $this->loadThemes();
    }

    public function loadThemes()
    {
        $query = Theme::query();

        if ($this->searchQuery) {
            $query->where('name', 'like', '%' . $this->searchQuery . '%');
        }

        $this->themes = $query
            ->get()
            ->map(fn (Theme $theme) => [
                'id' => $theme->id,
                'name' => $theme->name,
                'description' => $theme->description,
                'price' => $theme->price,
                'created_at' => $theme->created_at,
            ])
            ->toArray();
    }

    public function updatedSearchQuery()
    {
        $this->loadThemes();
    }

    public function openCreateForm()
    {
        $this->showForm = true;
        $this->editingThemeId = null;
        $this->themeName = '';
        $this->themeDescription = '';
        $this->themePrice = '';
    }

    public function editTheme($themeId)
    {
        $theme = Theme::findOrFail($themeId);
        $this->editingThemeId = $themeId;
        $this->themeName = $theme->name;
        $this->themeDescription = $theme->description ?? '';
        $this->themePrice = $theme->price !== null ? number_format((float) $theme->price, 2, '.', '') : '';
        $this->showForm = true;
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->editingThemeId = null;
        $this->themeName = '';
        $this->themeDescription = '';
        $this->themePrice = '';
    }

    public function saveTheme()
    {
        $validated = $this->validate($this->rules());
        $themeData = [
            'name' => $validated['themeName'],
            'description' => $validated['themeDescription'] ?: null,
            'price' => $validated['themePrice'],
        ];

        if ($this->editingThemeId) {
            $theme = Theme::findOrFail($this->editingThemeId);
            $theme->update($themeData);
            $this->dispatch('theme-updated', 'Thema succesvol bijgewerkt!');
        } else {
            Theme::create($themeData);
            $this->dispatch('theme-created', 'Thema succesvol aangemaakt!');
        }

        $this->closeForm();
        $this->loadThemes();
    }

    public function deleteTheme($themeId)
    {
        try {
            $theme = Theme::findOrFail($themeId);
            $theme->delete();
            $this->dispatch('theme-deleted', 'Thema succesvol verwijderd!');
            $this->loadThemes();
        } catch (\Exception $e) {
            $this->dispatch('error', 'Er is een fout opgetreden bij het verwijderen van het thema.');
        }
    }

    public function render()
    {
        return view('livewire.theme-manager')
            ->layout('components.layouts.app', ['title' => 'Thema\'s Beheren']);
    }

    protected function rules()
    {
        return [
            'themeName' => [
                'required',
                'string',
                'max:255',
                Rule::unique('themes', 'name')->ignore($this->editingThemeId),
            ],
            'themeDescription' => 'nullable|string',
            'themePrice' => 'required|numeric|min:0',
        ];
    }
}
