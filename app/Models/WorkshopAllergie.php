<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkshopAllergie extends Model
{
    protected $primaryKey = 'workshopAllergyId';

    protected $fillable = [
        'allergyId',
        'workshopId',
    ];

    public function allergy()
    {
        return $this->belongsTo(Allergie::class, 'allergyId');
    }

    public function workshop()
    {
        return $this->belongsTo(Workshop::class, 'workshopId');
    }
}
