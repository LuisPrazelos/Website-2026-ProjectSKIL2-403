<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingList extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'personId',
        'name',
        'isCompleted',
        'internalComment',
    ];

    /**
     * A shoppinglist belongs to one person
     */
    public function person()
    {
        return $this->belongsTo(Person::class, 'personId');
    }

    /**
     * A shoppinglist has many items
     */
    public function items()
    {
        return $this->hasMany(ShoppinglistItem::class, 'shoppinglistId');
    }
}
