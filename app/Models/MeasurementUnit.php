<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MeasurementUnit extends Model
{
    use HasFactory;

    protected $primaryKey = 'measurementUnitId';

    protected $fillable = [
        'unitName',
    ];

    public function ingredients()
    {
        return $this->hasMany(Ingredient::class, 'standardUnitId');
    }
}
