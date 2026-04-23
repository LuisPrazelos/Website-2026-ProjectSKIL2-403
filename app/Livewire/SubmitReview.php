<?php

namespace App\Livewire;

use App\Models\Review;
use App\Models\Dessert;
use App\Models\Workshop;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class SubmitReview extends Component
{
    public $content;
    public $score = 5;
    public $subjectType = 'general'; // 'general', 'dessert', 'workshop'
    public $selectedId = null; // ID of selected dessert or workshop

    public $successMessage = false;

    protected $rules = [
        'content' => 'required|string|min:10',
        'score' => 'required|integer|min:1|max:5',
        'subjectType' => 'required|in:general,dessert,workshop',
        'selectedId' => 'nullable|required_if:subjectType,dessert,workshop',
    ];

    public function submit()
    {
        $this->validate();

        Review::create([
            'score' => $this->score,
            'content' => $this->content,
            'date' => now(),
            'userId' => Auth::id(),
            'desssertId' => $this->subjectType === 'dessert' ? $this->selectedId : null,
            'workshopId' => $this->subjectType === 'workshop' ? $this->selectedId : null,
        ]);

        $this->reset(['content', 'score', 'subjectType', 'selectedId']);
        $this->successMessage = true;
    }

    public function render()
    {
        $options = [];
        if ($this->subjectType === 'dessert') {
            $options = Dessert::all();
        } elseif ($this->subjectType === 'workshop') {
            $options = Workshop::all();
        }

        return view('livewire.submit-review', [
            'options' => $options
        ])->layout('components.layouts.app', ['title' => 'Schrijf een Review']);
    }
}
