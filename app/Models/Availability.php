<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'exceptionAvailabilityDate',
        'pickUpTimeStart',
        'pickUpTimeStop',
    ];

    protected $casts = [
        'date' => 'date',
        'exceptionAvailabilityDate' => 'datetime',
        'pickUpTimeStart' => 'datetime',
        'pickUpTimeStop' => 'datetime',
    ];

    /**
     * An availability can be linked to multiple orders
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'availability');
    }
}
