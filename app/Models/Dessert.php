<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Surplus;

class Dessert extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'description',
        'preparation_method',
        'notes',
        'image',
        'portion_size',
        'measurement_unit_id',
    ];

    protected $casts = [
        'price' => 'float',
        'portion_size' => 'float',
    ];

    /**
     * One dessert can have many surpluses (leftovers).
     */
    public function surpluses()
    {
        return $this->hasMany(Surplus::class, 'dessert_id');
    }
}
