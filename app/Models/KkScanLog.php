<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class KkScanLog extends Model
{
    use BelongsToTenant;

    protected $guarded = ['id', 'tenant_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
