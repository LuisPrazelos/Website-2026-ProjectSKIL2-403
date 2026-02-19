<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkshopUser extends Model
{
    use HasFactory;

    // Table name (plural) — adjust if your migrations use a different name
    protected $table = 'workshop_users';

    // Use default primary key 'id'

    protected $fillable = [
        'workshop_id',
        'user_id',
        'registration_date',
        'total_adults',
        'total_children',
        'comment',
        'is_present',
    ];

    protected $casts = [
        'registration_date' => 'date',
        'total_adults' => 'integer',
        'total_children' => 'integer',
        'is_present' => 'boolean',
    ];

    public function workshop()
    {
        // Workshop model uses custom primaryKey 'workshopId'
        return $this->belongsTo(Workshop::class, 'workshop_id', 'workshopId');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

