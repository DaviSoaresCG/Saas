<?php

namespace App\BelongsToTenant;
use App\Models\Scopes\TenantScope;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant()
    {
        static::addGlobalScope(new TenantScope);

        static::creating(function ($model) {
            $model->tenant_id = app()->make(\App\Models\Tenant::class)->id;
        });
    }
}
