<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnnouncementDismissal extends Model
{
    public $timestamps = false;
    protected $guarded = ['id'];

    protected $casts = [
        'dismissed_at' => 'datetime',
    ];

    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
