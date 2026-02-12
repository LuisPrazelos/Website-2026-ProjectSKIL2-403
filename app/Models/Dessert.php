<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dessert extends Model
{
    use HasFactory;

    // Minimal Dessert model to satisfy foreign key relationship from Surplus.
    // Add fields and relationships as needed.

    protected $fillable = [
        'name',
        'price',
        'description',
    ];

    protected $casts = [
        'price' => 'float',
    ];

    public function surpluses()
    {
        return $this->hasMany(Surplus::class, 'dessert_id');
    }
}

