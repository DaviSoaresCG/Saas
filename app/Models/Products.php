<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Products extends Model
{
    use BelongsToTenant;
    
    protected $fillable = ['name', 'description', 'value', 'user_id', 'path'];

    protected function value(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => str_replace(['.', ','], ['', '.'], $value),

            get: fn ($value) => number_format($value, 2, ',', '.')
        );
    }
}
