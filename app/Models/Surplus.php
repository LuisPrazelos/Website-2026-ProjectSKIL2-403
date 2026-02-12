<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Dessert;

class Surplus extends Model
{
    use HasFactory;

    // Table name will default to 'surpluses' (plural). If you prefer a different table name,
    // set protected $table = 'overschot' or similar.

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'date',
        'total_amount',
        'sale',
        'expiration_date',
        'dessert_id',
        'comment',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'date' => 'date',
        'expiration_date' => 'date',
        'total_amount' => 'integer',
        'sale' => 'float',
    ];

    /**
     * If you have a Dessert model and a foreign key, define the relationship:
     */
    public function dessert()
    {
        return $this->belongsTo(Dessert::class, 'dessert_id');
    }
}
