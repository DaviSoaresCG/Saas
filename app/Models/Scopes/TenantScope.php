<?php

namespace App\Models\Scopes;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $user = app()->bound(User::class) ? app(User::class) : auth()->user();
        if ($user && isset($user->id)) {
            $builder->where($model->getTable().'.user_id', $user->id);
        }
    }
}
