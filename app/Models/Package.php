<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'is_standard',
        'price_history',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_standard' => 'boolean',
        'price_history' => 'array',
    ];

    /**
     * Returns price history sorted by date ascending.
     */
    public function getSortedPriceHistoryAttribute(): array
    {
        $history = $this->price_history ?? [];
        usort($history, fn ($a, $b) => strcmp($a['date'], $b['date']));
        return $history;
    }
}
