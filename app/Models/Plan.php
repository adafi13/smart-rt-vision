<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'features' => 'array',
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function isUnlimitedKk(): bool
    {
        return $this->max_kk === null;
    }

    public function isUnlimitedAi(): bool
    {
        return $this->max_ai_extractions_per_month === null;
    }

    public function isUnlimitedUsers(): bool
    {
        return $this->max_users === null;
    }
}
