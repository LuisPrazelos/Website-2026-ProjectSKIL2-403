<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Theme;

class ThemeManager extends Component
{
    public $themes = [];
    public $showForm = false;
    public $editingThemeId = null;
    public $themeName = '';
    public $searchQuery = '';

    protected $rules = [
        'themeName' => 'required|string|max:255|unique:themes,name',
    ];

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

        $this->themes = $query->get()->toArray();
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
    }

    public function editTheme($themeId)
    {
        $theme = Theme::findOrFail($themeId);
        $this->editingThemeId = $themeId;
        $this->themeName = $theme->name;
        $this->showForm = true;
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->editingThemeId = null;
        $this->themeName = '';
    }

    public function saveTheme()
    {
        // Adjust rules if editing
        if ($this->editingThemeId) {
            $this->rules['themeName'] = 'required|string|max:255|unique:themes,name,' . $this->editingThemeId;
        }

        $this->validate();

        if ($this->editingThemeId) {
            $theme = Theme::findOrFail($this->editingThemeId);
            $theme->update(['name' => $this->themeName]);
            $this->dispatch('theme-updated', 'Thema succesvol bijgewerkt!');
        } else {
            Theme::create(['name' => $this->themeName]);
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
}

