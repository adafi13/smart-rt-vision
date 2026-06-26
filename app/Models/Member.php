<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use BelongsToTenant;

    protected $guarded = ['id', 'tenant_id'];

    public function family()
    {
        return $this->belongsTo(Family::class);
    }
}
