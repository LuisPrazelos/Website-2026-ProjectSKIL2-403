<?php

namespace App\Livewire;

use App\Models\review;
use Livewire\Component;

class RecentReviews extends Component
{
    public function render()
    {
        $reviews = review::with(['user', 'dessert', 'workshop'])
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();

        return view('livewire.recent-reviews', [
            'reviews' => $reviews
        ]);
    }
}
