<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Picture extends Model
{
    use HasFactory;

    protected $table = 'pictures'; // DIT IS DE BELANGRIJKSTE LIJN

    protected $fillable = [
        'title',
        'hash',
    ];

    public function desserts()
    {
        return $this->hasMany(Dessert::class, 'picture_id');
    }
}
