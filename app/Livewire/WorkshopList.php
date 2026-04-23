<?php

namespace App\Livewire;

use App\Models\Workshop;
use App\Models\WorkshopUser;
use Livewire\Component;
use Livewire\WithPagination;

class WorkshopList extends Component
{
    use WithPagination;

    public $search = '';
    public $showRegistrationModal = false;
    public $selectedWorkshop = null;

    // Registration form properties
    public $total_adults = 1;
    public $total_children = 0;
    public $comment = '';

    protected $rules = [
        'total_adults' => 'required|integer|min:0',
        'total_children' => 'required|integer|min:0',
        'comment' => 'nullable|string|max:500',
    ];

    public function render()
    {
        $workshops = Workshop::with('registrations')
            ->where('date', '>=', now())
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%");
            })
            ->orderBy('date', 'asc')
            ->paginate(9);

        return view('livewire.workshop-list', [
            'workshops' => $workshops,
        ])->layout('components.layouts.app', ['title' => __('Beschikbare Workshops')]);
    }

    public function openRegistrationModal(Workshop $workshop)
    {
        $this->selectedWorkshop = $workshop;
        $this->total_adults = 1;
        $this->total_children = 0;
        $this->comment = '';
        $this->showRegistrationModal = true;
    }

    public function register()
    {
        $this->validate();

        if (!$this->selectedWorkshop) return;

        // Check if there's enough space
        $newParticipants = $this->total_adults + $this->total_children;
        $currentParticipants = $this->selectedWorkshop->participant_count;

        if (($currentParticipants + $newParticipants) > $this->selectedWorkshop->max_participants) {
            $this->addError('max_participants', __('Helaas zijn er niet genoeg plekken meer beschikbaar.'));
            return;
        }

        WorkshopUser::create([
            'workshop_id' => $this->selectedWorkshop->workshopId,
            'user_id' => auth()->id(),
            'registration_date' => now(),
            'total_adults' => $this->total_adults,
            'total_children' => $this->total_children,
            'comment' => $this->comment,
            'is_present' => false,
        ]);

        $this->showRegistrationModal = false;
        session()->flash('success', __('Je bent succesvol ingeschreven voor de workshop!'));
    }
}
