<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews';

    protected $primaryKey = 'reviewId';

    protected $fillable = [
        'score',
        'content',
        'date',
        'userId',
        'desssertId',
        'workshopId',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function dessert()
    {
        return $this->belongsTo(dessert::class, 'desssertId');
    }

    public function workshop()
    {
        return $this->belongsTo(workshop::class, 'workshopId');
    }
}
