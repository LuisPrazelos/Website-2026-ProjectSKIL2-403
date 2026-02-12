<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'notification_category_id',
        'notification_channel_id',
        'received',
    ];

    protected $casts = [
        'received' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(NotificationCategory::class, 'notification_category_id');
    }

    public function channel()
    {
        return $this->belongsTo(NotificationChannel::class, 'notification_channel_id');
    }
}

