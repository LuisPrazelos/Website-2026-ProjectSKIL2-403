<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    use HasFactory;

    /**
     * If your table is named "shopping_carts" you can REMOVE this.
     * If your table is named exactly "shoppingcart" or "shoppingcartS", adjust accordingly.
     */
    protected $table = 'shopping_carts';

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'orderId',
        'dessertId',
        'leftoverId',
        'amount',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'amount' => 'integer',
    ];

    /* =======================
       Relationships
    ======================== */

    /**
     * ShoppingCart belongs to one Order
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'orderId');
    }

    /**
     * ShoppingCart belongs to one Dessert
     */
    public function dessert()
    {
        return $this->belongsTo(Dessert::class, 'dessertId');
    }

    /**
     * ShoppingCart belongs to one Leftover
     */
    public function leftover()
    {
        return $this->belongsTo(Leftover::class, 'leftoverId');
    }
}
