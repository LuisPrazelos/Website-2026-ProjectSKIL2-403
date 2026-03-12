<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Happening;

class ShowHappenings extends Component
{
    public $happenings;
    public $selectedHappening = null;
    public $acceptedHappenings = [];

    public function mount()
    {
        $this->happenings = Happening::with(['status', 'theme'])->latest()->get();
    }

    public function showDetails($id)
    {
        $this->selectedHappening = Happening::with(['status', 'theme'])->find($id);
    }

    public function closeDetails()
    {
        $this->selectedHappening = null;
    }

    public function delete($id)
    {
        $happening = Happening::find($id);
        if ($happening) {
            $happening->delete();
        }

        // If the deleted happening was the selected one, close the details view
        if ($this->selectedHappening && $this->selectedHappening->id === $id) {
            $this->closeDetails();
        }

        // Refresh the list of happenings
        $this->happenings = Happening::with(['status', 'theme'])->latest()->get();

        // Optionally, you can dispatch a browser event to show a success message
        $this->dispatch('happening-deleted', 'Evenement succesvol verwijderd.');
    }

    public function acceptHappening($id)
    {
        if (($key = array_search($id, $this->acceptedHappenings)) !== false) {
            // If the ID is already in the array, remove it (toggle off)
            unset($this->acceptedHappenings[$key]);
        } else {
            // Otherwise, add it to the array (toggle on)
            $this->acceptedHappenings[] = $id;
        }
    }

    public function render()
    {
        return view('livewire.show-happenings')
            ->layout('components.layouts.app', ['title' => 'Evenementen Beheer']);
    }
}
