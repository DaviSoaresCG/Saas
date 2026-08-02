<?php

namespace App\Traits;

use App\Models\Scopes\TenantScope;
use App\Models\User;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant()
    {
        static::addGlobalScope(new TenantScope);

        static::creating(function ($model) {
            if (empty($model->user_id)) {
                $tenantUser = app()->bound(User::class) ? app(User::class) : auth()->user();
                if ($tenantUser && isset($tenantUser->id)) {
                    $model->user_id = $tenantUser->id;
                }
            }
        });
    }
}

