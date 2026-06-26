<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    use BelongsToTenant;

    protected $guarded = ['id', 'tenant_id'];

    public function members()
    {
        return $this->hasMany(Member::class);
    }
}
