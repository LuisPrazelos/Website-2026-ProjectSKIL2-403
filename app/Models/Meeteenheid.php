<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeteenheid extends Model
{
    protected $primaryKey = 'meeteenheidId';

    protected $fillable = [
        'eenheidNaam',
    ];

    public function ingredients()
    {
        return $this->hasMany(Ingredient::class, 'standaardEenheidId');
    }
}
