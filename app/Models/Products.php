<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use BelongsToTenant;

    protected $fillable = ['name', 'description', 'value', 'user_id', 'path'];

    public function productClicks()
    {
        return $this->hasMany(ProductClick::class, 'product_id');
    }

    protected function value(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => str_replace(['.', ','], ['', '.'], $value),

            get: fn ($value) => number_format($value, 2, ',', '.')
        );
    }
}
