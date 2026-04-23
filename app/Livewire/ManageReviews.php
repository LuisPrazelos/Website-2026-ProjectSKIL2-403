<?php

namespace App\Livewire;

use App\Models\Review;
use Livewire\Component;
use Livewire\WithPagination;

class ManageReviews extends Component
{
    use WithPagination;

    public function toggleVisibility($reviewId)
    {
        $review = Review::find($reviewId);
        if ($review) {
            $review->is_visible = !$review->is_visible;
            $review->save();
            session()->flash('message', 'Zichtbaarheid van review bijgewerkt.');
        }
    }

    public function deleteReview($reviewId)
    {
        $review = Review::find($reviewId);
        if ($review) {
            $review->delete();
            session()->flash('message', 'Review is verwijderd.');
        }
    }

    public function render()
    {
        return view('livewire.manage-reviews', [
            'reviews' => Review::with(['user', 'dessert', 'workshop'])->orderBy('date', 'desc')->paginate(10),
        ]);
    }
}
