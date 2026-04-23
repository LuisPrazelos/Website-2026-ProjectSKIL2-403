<?php

namespace App\Livewire;

use App\Models\Review;
use Livewire\Component;

class RecentReviews extends Component
{
    public function render()
    {
        $reviews = Review::with(['user', 'dessert', 'workshop'])
            ->where('is_visible', true)
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();

        return view('livewire.recent-reviews', [
            'reviews' => $reviews
        ]);
    }
}
