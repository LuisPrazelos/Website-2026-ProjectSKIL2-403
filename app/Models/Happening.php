<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\State;

class Happening extends Model
{
    /** @use HasFactory<\Database\Factories\HappeningFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'message',
        'remarks',
        'event_date',
        'person_count',
        'price_per_person',
        'user_id',
        'theme_id',
        'status_id',
        'on_location',
        'location',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'price_per_person' => 'float',
        'on_location' => 'boolean',
    ];

    public function getHasOwnerResponseAttribute(): bool
    {
        return $this->price_per_person > 0
            || filled($this->remarks)
            || $this->relationLoaded('desserts') && $this->desserts->isNotEmpty();
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    public function status()
    {
        return $this->belongsTo(State::class, 'status_id');
    }

    public function desserts()
    {
        return $this->belongsToMany(Dessert::class, 'happening_desserts')
            ->withPivot('quantity', 'allergies')
            ->withTimestamps();
    }
}
