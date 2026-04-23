<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * A theme can have many decorations
     */
    public function decorations()
    {
        return $this->hasMany(Decoration::class, 'themeId');
    }
}
