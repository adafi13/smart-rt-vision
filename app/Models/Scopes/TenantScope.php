<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (app()->bound('currentTenantId') && app('currentTenantId')) {
            $builder->where(function ($q) use ($model) {
                $q->where($model->getQualifiedTenantKeyName(), app('currentTenantId'));
                
                // Allow fetching global RW data if applicable
                if (app()->bound('currentRwId') && app('currentRwId')) {
                    if (\Illuminate\Support\Facades\Schema::hasColumn($model->getTable(), 'rw_id')) {
                        $q->orWhere(function ($q2) use ($model) {
                            $q2->where('rw_id', app('currentRwId'))
                               ->whereNull($model->getQualifiedTenantKeyName());
                        });
                    }
                }
            });
        }
    }
}
