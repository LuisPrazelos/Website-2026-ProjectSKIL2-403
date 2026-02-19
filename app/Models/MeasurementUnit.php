<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeasurementUnit extends Model
{
    protected $primaryKey = 'measurementUnitId';

    protected $fillable = [
        'unitName',
    ];

    public function ingredients()
    {
        return $this->hasMany(Ingredient::class, 'standardUnitId');
    }
}
