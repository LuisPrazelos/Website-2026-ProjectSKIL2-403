<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Decoration extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'content',
        'themeId',
    ];

    protected $casts = [
        'price' => 'float',
    ];

    /**
     * Decoration optionally belongs to a theme
     */
    public function theme()
    {
        return $this->belongsTo(Theme::class, 'themeId');
    }
}
