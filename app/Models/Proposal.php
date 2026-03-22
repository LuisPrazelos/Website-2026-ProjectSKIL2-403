<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'person_count',
        'delivery_date',
        'on_location',
        'user_id',
        'theme_id',
        'price_per_person',
        'remarks',
    ];

    protected $casts = [
        'delivery_date' => 'datetime',
        'on_location' => 'boolean',
        'price_per_person' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    public function desserts()
    {
        return $this->belongsToMany(Dessert::class, 'proposal_desserts')
            ->withPivot(['quantity', 'allergies'])
            ->withTimestamps();
    }
}
