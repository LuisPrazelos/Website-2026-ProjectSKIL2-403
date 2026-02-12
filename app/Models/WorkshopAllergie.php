<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkshopAllergie extends Model
{
    protected $primaryKey = 'workshopAllergieId';

    protected $fillable = [
        'allergieId',
        'workshopid',
    ];

    public function allergie()
    {
        return $this->belongsTo(Allergie::class, 'allergieId');
    }

    public function workshop()
    {
        return $this->belongsTo(Workshop::class, 'workshopid');
    }
}
